const { connection } = require('../config');
const bcrypt = require('bcryptjs');

class User {
    static create(userData, callback) {
        bcrypt.genSalt(10, (err, salt) => {
            if (err) return callback(err);
            
            bcrypt.hash(userData.Clave, salt, (err, hash) => {
                if (err) return callback(err);
                
                userData.Clave = hash;
                
                const query = `
                    INSERT INTO registro (
                        idRol, PrimerNombre, SegundoNombre, PrimerApellido, SegundoApellido,
                        apartamento, Correo, Usuario, Clave, Id_tipoDocumento, numeroDocumento,
                        telefonoUno, telefonoDos, tipo_propietario
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                `;
                
                const values = [
                    userData.idRol,
                    userData.PrimerNombre,
                    userData.SegundoNombre,
                    userData.PrimerApellido,
                    userData.SegundoApellido,
                    userData.apartamento,
                    userData.Correo,
                    userData.Usuario,
                    userData.Clave,
                    userData.Id_tipoDocumento,
                    userData.numeroDocumento,
                    userData.telefonoUno,
                    userData.telefonoDos || null,
                    userData.tipo_propietario
                ];
                
                connection.query(query, values, (err, result) => {
                    if (err) return callback(err);
                    callback(null, result.insertId);
                });
            });
        });
    }

    
    static findByEmail(email, callback) {
        connection.query(
            'SELECT * FROM registro WHERE Correo = ? LIMIT 1', 
            [email], 
            (err, results) => {
                if (err) return callback(err);
                if (results[0]) {
                    results[0].id = results[0].id_Registro;
                }
                callback(null, results[0]);
            }
        );
    }

    static findByUsername(username, callback) {
        const query = `
            SELECT 
                r.id_Registro, 
                r.idRol, 
                rol.Roldescripcion as rolNombre,
                r.PrimerNombre, 
                r.SegundoNombre, 
                r.PrimerApellido, 
                r.SegundoApellido, 
                r.apartamento, 
                r.Correo, 
                r.Usuario, 
                r.Clave, 
                r.Id_tipoDocumento, 
                r.numeroDocumento, 
                r.telefonoUno, 
                r.telefonoDos, 
                r.tipo_propietario 
            FROM 
                registro r
            JOIN 
                rol ON r.idRol = rol.id
            WHERE 
                r.Usuario = ? 
            LIMIT 1
        `;
        
        connection.query(query, [username], (err, results) => {
            if (err) return callback(err);
            if (results[0]) {
                results[0].id = results[0].id_Registro;
            }
            callback(null, results[0]);
        });
    }

    static findById(id, callback) {
        connection.query(
            'SELECT * FROM registro WHERE id_Registro = ? LIMIT 1', 
            [id], 
            (err, results) => {
                if (err) return callback(err);
                callback(null, results[0]);
            }
        );
    }
}


module.exports = User;