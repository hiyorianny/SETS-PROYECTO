const User = require('../models/User');
const Token = require('../models/Token');
const bcrypt = require('bcryptjs');
const { connection } = require('../db/config');
const multer = require('multer');
const path = require('path');
const fs = require('fs');


const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        const uploadPath = path.join(__dirname, '../../uploads/profile-images');
        if (!fs.existsSync(uploadPath)) {
            fs.mkdirSync(uploadPath, { recursive: true });
        }
        cb(null, uploadPath);
    },
    filename: (req, file, cb) => {
        const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
        cb(null, 'profile-' + uniqueSuffix + path.extname(file.originalname));
    }
});

const upload = multer({
    storage: storage,
    limits: { fileSize: 5 * 1024 * 1024 }, // 5MB
    fileFilter: (req, file, cb) => {
        if (file.mimetype.startsWith('image/')) {
            cb(null, true);
        } else {
            cb(new Error('Solo se permiten imágenes'), false);
        }
    }
}).single('image');



class AuthController {
    static async register(req, res) {
        try {
            if (req.body.Clave !== req.body.confirmPassword) {
                return res.status(400).json({ error: 'Las contraseñas no coinciden' });
            }

            const roleExists = await new Promise((resolve) => {
                connection.query(
                    'SELECT id, Roldescripcion FROM rol WHERE id = ?',
                    [req.body.idRol],
                    (_err, results) => resolve(results[0] || null)
                );
            });

            if (!roleExists) {
                return res.status(400).json({ error: 'El rol seleccionado no es válido' });
            }

            const [existingEmail, existingUser] = await Promise.all([
                new Promise((resolve) => User.findByEmail(req.body.Correo, (err, user) => resolve(user))),
                new Promise((resolve) => User.findByUsername(req.body.Usuario, (err, user) => resolve(user)))
            ]);

            if (existingEmail) return res.status(400).json({ error: 'El correo electrónico ya está registrado' });
            if (existingUser) return res.status(400).json({ error: 'El nombre de usuario ya está en uso' });

            User.create(req.body, async (err, userId) => {
                if (err) {
                    console.error('Error al crear usuario:', err);
                    return res.status(500).json({ error: 'Error al crear usuario' });
                }

                Token.generate(userId, (err, token) => {
                    if (err) {
                        console.error('Error generando token:', err);
                        return res.status(500).json({ error: 'Error al generar token' });
                    }

                    // Estructura de respuesta mejorada
                    res.status(201).json({
                        success: true,
                        token,
                        user: {
                            id_Registro: userId,
                            Usuario: req.body.Usuario,
                            PrimerNombre: req.body.PrimerNombre,
                            PrimerApellido: req.body.PrimerApellido,
                            rol: {
                                id: roleExists.id,
                                nombre: roleExists.Roldescripcion
                            }
                        },
                        message: 'Registro exitoso'
                    });
                });
            });
        } catch (error) {
            console.error('Error en registro:', error);
            res.status(500).json({ error: 'Error interno del servidor' });
        }
    }

    static async login(req, res) {
        try {
            const { Usuario, Clave } = req.body;

            if (!Usuario || !Clave) {
                return res.status(400).json({ error: 'Usuario y contraseña son requeridos' });
            }

            User.findByUsername(Usuario, async (err, user) => {
                if (err) {
                    console.error('Error buscando usuario:', err);
                    return res.status(500).json({ error: 'Error en el servidor' });
                }

                if (!user) {
                    return res.status(401).json({ error: 'Credenciales inválidas' });
                }

                const isMatch = await bcrypt.compare(Clave, user.Clave);
                if (!isMatch) {
                    return res.status(401).json({ error: 'Credenciales inválidas' });
                }

                // Buscar token existente
                connection.query(
                    'SELECT token FROM tokens WHERE id_Registro = ? AND fecha_expiracion > NOW() ORDER BY fecha_expiracion DESC LIMIT 1',
                    [user.id_Registro],
                    (err, tokenResults) => {
                        if (err) {
                            console.error('Error buscando token:', err);
                            return res.status(500).json({ error: 'Error en el servidor' });
                        }

                        if (tokenResults.length === 0) {
                            return res.status(401).json({ error: 'No hay token válido. Por favor registrese nuevamente.' });
                        }

                        const token = tokenResults[0].token;

                        // Obtener información del rol
                        connection.query(
                            'SELECT id, Roldescripcion FROM rol WHERE id = ?',
                            [user.idRol],
                            (err, roleResults) => {
                                if (err) {
                                    console.error('Error obteniendo rol:', err);
                                    return res.status(500).json({ error: 'Error en el servidor' });
                                }

                                const roleInfo = roleResults[0] ? {
                                    id: roleResults[0].id,
                                    nombre: roleResults[0].Roldescripcion
                                } : { id: user.idRol, nombre: 'Desconocido' };

                                // Preparar respuesta
                                const responseData = {
                                    success: true,
                                    token,
                                    user: {
                                        id_Registro: user.id_Registro,
                                        Usuario: user.Usuario,
                                        PrimerNombre: user.PrimerNombre,
                                        PrimerApellido: user.PrimerApellido,
                                        rol: roleInfo
                                    },
                                    message: 'Inicio de sesión exitoso'
                                };

                                res.json(responseData);
                            }
                        );
                    }
                );
            });
        } catch (error) {
            console.error('Error en login:', error);
            res.status(500).json({ error: 'Error interno del servidor' });
        }
    }
    static async getRoles(req, res) {
        try {
            connection.query(
                'SELECT id, Roldescripcion FROM rol ORDER BY Roldescripcion',
                (err, results) => {
                    if (err) {
                        console.error('Error obteniendo roles:', err);
                        return res.status(500).json({
                            success: false,
                            error: 'Error al obtener roles de la base de datos'
                        });
                    }

                    res.json({
                        success: true,
                        roles: results
                    });
                }
            );
        } catch (error) {
            console.error('Error en getRoles:', error);
            res.status(500).json({
                success: false,
                error: 'Error interno del servidor al obtener roles'
            });
        }
    }


    static async getUserProfile(req, res) {
        try {
            const userId = req.params.userId;

            const query = `
            SELECT 
              r.id_Registro,
              r.PrimerNombre,
              r.SegundoNombre,
              r.PrimerApellido,
              r.SegundoApellido,
              r.apartamento,
              r.Correo,
              r.telefonoUno,
              r.telefonoDos,
              r.tipo_propietario,
              r.Usuario,
              r.imagenPerfil,
              r.numeroDocumento,
              r.Id_tipoDocumento,
              rol.Roldescripcion,
              rol.id as rolId
            FROM 
              registro r
            JOIN 
              rol ON r.idRol = rol.id
            WHERE 
              r.id_Registro = ?
            LIMIT 1
          `;

            connection.query(query, [userId], (err, results) => {
                if (err) {
                    console.error('Error fetching user:', err);
                    return res.status(500).json({
                        success: false,
                        error: 'Error al obtener datos del usuario'
                    });
                }

                if (results.length === 0) {
                    return res.status(404).json({
                        success: false,
                        error: 'Usuario no encontrado'
                    });
                }

                const user = results[0];

                res.json({
                    success: true,
                    user: {
                        ...user,
                        rol: {
                            id: user.rolId,
                            nombre: user.Roldescripcion
                        }
                    }
                });
            });
        } catch (error) {
            console.error('Error in getUserProfile:', error);
            res.status(500).json({
                success: false,
                error: 'Error interno del servidor'
            });
        }
    }
    static async uploadProfileImage(req, res) {
        try {
            upload(req, res, async (err) => {
                if (err) {
                    console.error('Error uploading image:', err);
                    return res.status(400).json({
                        success: false,
                        error: err.message || 'Error al subir la imagen'
                    });
                }

                if (!req.file) {
                    return res.status(400).json({
                        success: false,
                        error: 'No se proporcionó ninguna imagen'
                    });
                }

                const userId = req.body.userId;
                if (!userId) {
                    fs.unlinkSync(req.file.path);
                    return res.status(400).json({
                        success: false,
                        error: 'ID de usuario no proporcionado'
                    });
                }


                const imageUrl = `/profile-images/${req.file.filename}`;

                connection.query(
                    'UPDATE registro SET imagenPerfil = ? WHERE id_Registro = ?',
                    [imageUrl, userId],
                    (err, result) => {
                        if (err) {
                            console.error('Error updating profile image:', err);
                            fs.unlinkSync(req.file.path);
                            return res.status(500).json({
                                success: false,
                                error: 'Error al actualizar el perfil'
                            });
                        }

                        res.json({
                            success: true,
                            imageUrl,
                            message: 'Imagen de perfil actualizada correctamente'
                        });
                    }
                );
            });
        } catch (error) {
            console.error('Error in uploadProfileImage:', error);
            res.status(500).json({
                success: false,
                error: 'Error interno del servidor'
            });
        }
    }
    static async updateUserProfile(req, res) {
    try {
        const userId = req.params.userId;
        const updateData = req.body;


        if (!updateData.PrimerNombre || !updateData.PrimerApellido || !updateData.Correo) {
            return res.status(400).json({
                success: false,
                error: 'Datos requeridos faltantes'
            });
        }

        connection.query(
            'UPDATE registro SET ? WHERE id_Registro = ?',
            [updateData, userId],
            (err, result) => {
                if (err) {
                    console.error('Error updating user:', err);
                    return res.status(500).json({
                        success: false,
                        error: 'Error al actualizar el perfil'
                    });
                }

                if (result.affectedRows === 0) {
                    return res.status(404).json({
                        success: false,
                        error: 'Usuario no encontrado'
                    });
                }

                res.json({
                    success: true,
                    message: 'Perfil actualizado correctamente'
                });
            }
        );
    } catch (error) {
        console.error('Error in updateUserProfile:', error);
        res.status(500).json({
            success: false,
            error: 'Error interno del servidor'
        });
    }
}

 static async uploadProfileImage(req, res) {
    try {
        upload(req, res, async (err) => {
            if (err) {
                console.error('Error uploading image:', err);
                return res.status(400).json({
                    success: false,
                    error: err.message || 'Error al subir la imagen'
                });
            }

            if (!req.file) {
                return res.status(400).json({
                    success: false,
                    error: 'No se proporcionó ninguna imagen'
                });
            }

            const userId = req.body.userId;
            if (!userId) {
                fs.unlinkSync(req.file.path);
                return res.status(400).json({
                    success: false,
                    error: 'ID de usuario no proporcionado'
                });
            }


            const imageUrl = `/uploads/profile-images/${req.file.filename}`;
            const fullUrl = `http://192.168.1.102:3001${imageUrl}`;

            connection.query(
                'UPDATE registro SET imagenPerfil = ? WHERE id_Registro = ?',
                [fullUrl, userId],
                (err, result) => {
                    if (err) {
                        console.error('Error updating profile image:', err);
                        fs.unlinkSync(req.file.path);
                        return res.status(500).json({
                            success: false,
                            error: 'Error al actualizar el perfil'
                        });
                    }

                    res.json({
                        success: true,
                        imageUrl: fullUrl,
                        message: 'Imagen de perfil actualizada correctamente'
                    });
                }
            );
        });
    } catch (error) {
        console.error('Error in uploadProfileImage:', error);
        res.status(500).json({
            success: false,
            error: 'Error interno del servidor'
        });
    }
}
}

module.exports = AuthController;