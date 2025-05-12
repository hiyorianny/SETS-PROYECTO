
const express = require('express');
const mysql = require('mysql');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'sets'
});

// Verificar conexión a la base de datos
db.connect(err => {
    if (err) {
        console.error('Error conectando a la base de datos:', err);
    } else {
        console.log('Conectado a la base de datos MySQL');
    }
});

app.get('/api/anuncios', (req, res) => {
    const query = 'SELECT * FROM anuncio ORDER BY fechaPublicacion DESC, horaPublicacion DESC';
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error en la consulta:', err);
            return res.status(500).json({ error: err.message });
        }
        console.log('Anuncios recuperados:', results.length);
        res.json(results);
    });
});

app.post('/api/anunciossubir', (req, res) => {
    const { titulo, descripcion, persona, apart, img_anuncio } = req.body;
    const fechaPublicacion = new Date().toISOString().split('T')[0];
    const horaPublicacion = new Date().toLocaleTimeString();
    
    const query = 'INSERT INTO anuncio (titulo, descripcion, fechaPublicacion, horaPublicacion, persona, apart, img_anuncio) VALUES (?, ?, ?, ?, ?, ?, ?)';
    
    db.query(query, [titulo, descripcion, fechaPublicacion, horaPublicacion, persona, apart, img_anuncio], (err, result) => {
        if (err) {
            console.error('Error al insertar anuncio:', err);
            return res.status(500).json({ error: err.message });
        }
        res.status(201).json({ id: result.insertId, message: 'Anuncio creado exitosamente' });
    });
});

app.delete('/api/elanuncios/:id', (req, res) => {
    const { id } = req.params;
    const query = 'DELETE FROM anuncio WHERE idAnuncio = ?';
    
    db.query(query, [id], (err, result) => {
        if (err) {
            console.error('Error al eliminar anuncio:', err);
            return res.status(500).json({ error: err.message });
        }
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Anuncio no encontrado' });
        }
        res.json({ message: 'Anuncio eliminado exitosamente' });
    });
});

app.get('/api/torres', (req, res) => {
    const query = `
        SELECT numApartamento, pisos, torre 
        FROM apartamento 
        ORDER BY torre, pisos, numApartamento
    `;

    db.query(query, (err, results) => {
        if (err) {
            console.error('Error en la consulta:', err);
            return res.status(500).json({ error: err.message });
        }

        // Organizar los datos por torres y pisos
        const torres = {};
        results.forEach(item => {
            const torre = item.torre;
            const piso = item.pisos;

            if (!torres[torre]) {
                torres[torre] = {};
            }

            if (!torres[torre][piso]) {
                torres[torre][piso] = [];
            }

            torres[torre][piso].push({
                numApartamento: item.numApartamento
            });
        });

        res.json({
            torres: torres,
            torresList: Object.keys(torres).sort()
        });
    });
});


app.post('/api/ingresos', (req, res) => {
    const { tipo_ingreso, placa, personasIngreso, documento, horaFecha } = req.body;

    const query = `
      INSERT INTO ingreso_peatonal 
      (personasIngreso, horaFecha, documento, tipo_ingreso, placa) 
      VALUES (?, ?, ?, ?, ?)
    `;

    db.query(query,
        [personasIngreso, horaFecha, documento, tipo_ingreso, placa],
        (err, results) => {
            if (err) {
                console.error('Error al insertar ingreso:', err);
                return res.status(500).json({ error: 'Error al registrar el ingreso' });
            }
            res.json({ success: true, id: results.insertId });
        }
    );
});


app.get('/api/ingresos', (req, res) => {
    const query = 'SELECT * FROM ingreso_peatonal';

    db.query(query, (err, results) => {
        if (err) {
            console.error('Error al obtener ingresos:', err);
            return res.status(500).json({ error: 'Error al obtener ingresos' });
        }
        res.json(results);
    });
});

// Agrega esto a tu servidor (index.js)
app.delete('/api/ingresos/:id', (req, res) => {
    const { id } = req.params;
    
    const query = 'DELETE FROM ingreso_peatonal WHERE idIngreso_Peatonal = ?';
    db.query(query, [id], (err, results) => {
        if (err) {
            console.error('Error al eliminar ingreso:', err);
            return res.status(500).json({ error: 'Error al eliminar ingreso' });
        }
        
        if (results.affectedRows === 0) {
            return res.status(404).json({ error: 'Ingreso no encontrado' });
        }
        
        res.json({ success: true });
    });
});

// Obtener parqueaderos
app.get('/api/parqueaderos', (req, res) => {
    const query = `SELECT * FROM parqueadero`;
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error al obtener parqueaderos:', err);
            return res.status(500).json({ error: 'Error al obtener parqueaderos' });
        }
        res.json(results);
    });
});






app.get('/api/contactarnos', (req, res) => {
    const query = 'SELECT * FROM contactarnos';
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error al obtener contactos:', err);
            return res.status(500).json({ error: 'Error al obtener contactos' });
        }
        res.json(results);
    });
});


app.delete('/api/contactarnos/:id', (req, res) => {
    const { id } = req.params;
    
    const query = 'DELETE FROM contactarnos WHERE idcontactarnos = ?';
    db.query(query, [id], (err, results) => {
        if (err) {
            console.error('Error al eliminar contacto:', err);
            return res.status(500).json({ error: 'Error al eliminar contacto' });
        }
        
        if (results.affectedRows === 0) {
            return res.status(404).json({ error: 'Contacto no encontrado' });
        }
        
        res.json({ success: true });
    });
});


app.get('/api/usuarios', (req, res) => {
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
            r.tipo_propietario,
            r.Usuario,
            r.Clave,
            r.imagenPerfil,
            r.numeroDocumento,
            rol.Roldescripcion
        FROM registro r
        LEFT JOIN rol ON r.idROL = rol.id
        ORDER BY r.PrimerNombre, r.PrimerApellido
    `;

    db.query(query, (err, results) => {
        if (err) {
            console.error('Error al obtener usuarios:', err);
            return res.status(500).json({ error: 'Error al obtener usuarios' });
        }
        res.json(results);
    });
});

app.delete('/api/usuarios/:id', (req, res) => {
    const { id } = req.params;
    
    const query = 'DELETE FROM registro WHERE id_Registro = ?';
    db.query(query, [id], (err, results) => {
        if (err) {
            console.error('Error al eliminar usuario:', err);
            return res.status(500).json({ error: 'Error al eliminar usuario' });
        }
        
        if (results.affectedRows === 0) {
            return res.status(404).json({ error: 'Usuario no encontrado' });
        }
        
        res.json({ success: true });
    });
});


// Rutas para citas
app.get('/api/citas', (req, res) => {
    const query = 'SELECT * FROM cita ';
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error al obtener citas:', err);
            return res.status(500).json({ error: 'Error al obtener citas' });
        }
        res.json(results);
    });
});

app.post('/api/citassolicitud', (req, res) => {
    const { fechacita, horacita, tipocita, apa, estado } = req.body;
    
    // Validación básica
    if (!fechacita || !horacita || !tipocita || !apa) {
      return res.status(400).json({ error: 'Faltan campos requeridos' });
    }
  
    const query = `
      INSERT INTO cita 
      (fechacita, horacita, tipocita, apa, estado) 
      VALUES (?, ?, ?, ?, ?)
    `;
    
    db.query(query, 
      [fechacita, horacita, tipocita, apa, estado || 'pendiente'], 
      (err, results) => {
        if (err) {
          console.error('Error en la consulta SQL:', err);
          return res.status(500).json({ 
            error: 'Error al crear cita',
            sqlMessage: err.sqlMessage 
          });
        }
        res.json({ success: true, id: results.insertId });
      }
    );
});

app.post('/api/citas/responder', (req, res) => {
    const { idcita, respuesta } = req.body;

    const query = `
        UPDATE cita 
        SET respuesta = ?, estado = 'respondida' 
        WHERE idcita = ?
    `;

    db.query(query, [respuesta, idcita], (err, results) => {
        if (err) {
            console.error('Error al responder cita:', err);
            return res.status(500).json({ error: 'Error al responder cita' });
        }
        res.json({ success: true });
    });
});

app.delete('/api/citas/:id', (req, res) => {
    const { id } = req.params;
    
    const query = 'DELETE FROM cita WHERE idcita = ?';
    db.query(query, [id], (err, results) => {
        if (err) {
            console.error('Error al eliminar cita:', err);
            return res.status(500).json({ error: 'Error al eliminar cita' });
        }
        
        if (results.affectedRows === 0) {
            return res.status(404).json({ error: 'Cita no encontrada' });
        }
        
        res.json({ success: true });
    });
});


app.get('/api/zonas-comunes', (req, res) => {
    const query = 'SELECT idZona, descripcion, costo_alquiler FROM zona_comun';
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error al obtener zonas comunes:', err);
            return res.status(500).json({ error: 'Error al obtener zonas comunes' });
        }
        res.json(results);
    });
});

// Obtener todas las solicitudes de zonas comunes
app.get('/api/solicitudes-zonas', (req, res) => {
    const query = 'SELECT * FROM solicitud_zona';
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error al obtener solicitudes de zonas:', err);
            return res.status(500).json({ error: 'Error al obtener solicitudes' });
        }
        res.json(results);
    });
});

// Actualizar estado de una solicitud
app.post('/api/actualizar-estado-solicitud', (req, res) => {
    const { ID_Apartamentooss, ID_zonaComun, fechainicio, estado } = req.body;
    
    console.log('Datos recibidos:', { ID_Apartamentooss, ID_zonaComun, fechainicio, estado });
    
    // CONSULTA CORREGIDA CON CLAUSULA WHERE COMPLETA
    const query = `
        UPDATE solicitud_zona 
        SET estado = ? 
        WHERE ID_Apartamentooss = ? 
        AND ID_zonaComun = ? 
        AND fechainicio = ?
    `;
    
    db.query(query, [estado, ID_Apartamentooss, ID_zonaComun, fechainicio], (err, results) => {
        if (err) {
            console.error('Error en la consulta SQL:', err);
            return res.status(500).json({ 
                error: 'Error al actualizar estado',
                sqlMessage: err.sqlMessage,
                sql: err.sql 
            });
        }
        
        console.log('Resultados de la actualización:', results);
        
        if (results.affectedRows === 0) {
            return res.status(404).json({ 
                error: 'Solicitud no encontrada',
                details: `No se encontró la solicitud con los parámetros proporcionados`
            });
        }
        
        res.json({ 
            success: true,
            affectedRows: results.affectedRows
        });
    });
});




// Obtener todos los pagos
app.get('/api/pagos', (req, res) => {
    const query = `
        SELECT p.*, r.PrimerNombre, r.PrimerApellido, r.apartamento 
        FROM pagos p
        LEFT JOIN registro r ON p.apart = r.apartamento
        ORDER BY p.fechaPago DESC
    `;
    
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error al obtener pagos:', err);
            return res.status(500).json({ error: 'Error al obtener pagos' });
        }
        res.json(results);
    });
});

// Crear un nuevo pago (para el admin)
app.post('/api/pagos', (req, res) => {
    const { pagoPor, cantidad, mediopago, apart, fechaPago, estado, referenciaPago } = req.body;
    
    const query = `
        INSERT INTO pagos 
        (pagoPor, cantidad, mediopago, apart, fechaPago, estado, referenciaPago) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    `;
    
    db.query(query, 
        [pagoPor, cantidad, mediopago, apart, fechaPago, estado, referenciaPago], 
        (err, results) => {
            if (err) {
                console.error('Error al crear pago:', err);
                return res.status(500).json({ error: 'Error al crear pago' });
            }
            res.json({ success: true, id: results.insertId });
        }
    );
});

app.put('/api/pagos/:idPagos', (req, res) => {
    const { idPagos } = req.params;
    const { estado } = req.body;
    

    if (!['Pendiente', 'Pagado', 'Vencido'].includes(estado)) {
        return res.status(400).json({ error: 'Estado no válido' });
    }


    const query = `UPDATE pagos SET estado = ? WHERE idPagos = ?`;
    
    db.query(query, [estado, idPagos], (err, results) => {
        if (err) {
            console.error('Error al actualizar pago:', err);
            return res.status(500).json({ 
                error: 'Error al actualizar pago',
                details: err.message 
            });
        }
        if (results.affectedRows === 0) {
            return res.status(404).json({ error: 'Pago no encontrado' });
        }
        res.json({ 
            success: true,
            message: 'Estado actualizado correctamente' 
        });
    });
});

// Eliminar un pago
app.delete('/api/pagos/:id', (req, res) => {
    const { id } = req.params;
    
    const query = 'DELETE FROM pagos WHERE idPagos = ?';
    db.query(query, [id], (err, results) => {
        if (err) {
            console.error('Error al eliminar pago:', err);
            return res.status(500).json({ error: 'Error al eliminar pago' });
        }
        res.json({ success: true });
    });
});

// Agrega o reemplaza estas rutas en tu servidor

// Obtener zonas comunes con imágenes
// Obtener zonas comunes
app.get('/api/zonas-comunes', (req, res) => {
    const query = 'SELECT idZona, descripcion, costo_alquiler, url_videos FROM zona_comun';
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error al obtener zonas comunes:', err);
            return res.status(500).json({ error: 'Error al obtener zonas comunes' });
        }
        res.json(results);
    });
});

// Obtener reservas de usuario
app.get('/api/mis-reservas/:apartamento', (req, res) => {
    const { apartamento } = req.params;
    
    const query = `
        SELECT sz.*, zc.descripcion as nombre_zona, zc.costo_alquiler
        FROM solicitud_zona sz
        JOIN zona_comun zc ON sz.ID_zonaComun = zc.idZona
        WHERE sz.ID_Apartamentooss = ?
        ORDER BY sz.fechainicio DESC, sz.Hora_inicio DESC
    `;
    
    db.query(query, [apartamento], (err, results) => {
        if (err) {
            console.error('Error al obtener reservas:', err);
            return res.status(500).json({ error: 'Error al obtener reservas' });
        }
        res.json(results);
    });
});

// Crear nueva reserva
app.post('/api/reservar-zona', (req, res) => {
    const { ID_Apartamentooss, ID_zonaComun, fechainicio, fechafinal, Hora_inicio, Hora_final } = req.body;
    
    // Validaciones básicas
    if (!ID_Apartamentooss || !ID_zonaComun || !fechainicio || !fechafinal || !Hora_inicio || !Hora_final) {
        return res.status(400).json({ error: 'Todos los campos son requeridos' });
    }
    
    // Verificar disponibilidad
    const checkQuery = `
        SELECT * FROM solicitud_zona 
        WHERE ID_zonaComun = ? 
        AND fechainicio = ? 
        AND (
            (Hora_inicio < ? AND Hora_final > ?) OR
            (Hora_inicio < ? AND Hora_final > ?) OR
            (Hora_inicio >= ? AND Hora_final <= ?)
        )
        AND estado != 'RECHAZADA'
    `;
    
    db.query(checkQuery, [
        ID_zonaComun, fechainicio,
        Hora_final, Hora_inicio,
        Hora_final, Hora_inicio,
        Hora_inicio, Hora_final
    ], (err, results) => {
        if (err) {
            console.error('Error al verificar disponibilidad:', err);
            return res.status(500).json({ error: 'Error al verificar disponibilidad' });
        }
        
        if (results.length > 0) {
            return res.status(400).json({ 
                error: 'La zona ya está reservada en ese horario',
                conflicto: results[0]
            });
        }
        
        // Crear reserva
        const insertQuery = `
            INSERT INTO solicitud_zona 
            (ID_Apartamentooss, ID_zonaComun, fechainicio, fechafinal, Hora_inicio, Hora_final, estado)
            VALUES (?, ?, ?, ?, ?, ?, 'PENDIENTE')
        `;
        
        db.query(insertQuery, [
            ID_Apartamentooss, ID_zonaComun, 
            fechainicio, fechafinal, 
            Hora_inicio, Hora_final
        ], (err, results) => {
            if (err) {
                console.error('Error al crear reserva:', err);
                return res.status(500).json({ error: 'Error al crear reserva' });
            }
            
            // Obtener detalles completos de la reserva
            const selectQuery = `
                SELECT sz.*, zc.descripcion as nombre_zona, zc.costo_alquiler
                FROM solicitud_zona sz
                JOIN zona_comun zc ON sz.ID_zonaComun = zc.idZona
                WHERE ID_Apartamentooss = ? AND ID_zonaComun = ? AND fechainicio = ? AND Hora_inicio = ?
            `;
            
            db.query(selectQuery, [ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio], 
            (err, reserva) => {
                if (err || reserva.length === 0) {
                    console.error('Error al obtener reserva:', err);
                    return res.status(500).json({ 
                        success: true,
                        message: 'Reserva creada pero no se pudieron obtener los detalles completos'
                    });
                }
                
                res.json({ 
                    success: true, 
                    message: 'Reserva solicitada correctamente',
                    reserva: reserva[0]
                });
            });
        });
    });
});

// Cancelar reserva
app.delete('/api/cancelar-reserva', (req, res) => {
    const { ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio } = req.body;
    
    if (!ID_Apartamentooss || !ID_zonaComun || !fechainicio || !Hora_inicio) {
        return res.status(400).json({ error: 'Datos incompletos para cancelar reserva' });
    }
    
    const query = `
        DELETE FROM solicitud_zona 
        WHERE ID_Apartamentooss = ? 
        AND ID_zonaComun = ? 
        AND fechainicio = ?
        AND Hora_inicio = ?
        AND estado = 'PENDIENTE'
    `;
    
    db.query(query, [ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio], (err, results) => {
        if (err) {
            console.error('Error al cancelar reserva:', err);
            return res.status(500).json({ error: 'Error al cancelar reserva' });
        }
        
        if (results.affectedRows === 0) {
            return res.status(404).json({ 
                error: 'Reserva no encontrada o no se puede cancelar',
                details: 'Solo se pueden cancelar reservas pendientes'
            });
        }
        
        res.json({ success: true });
    });
});

const PORT = 3001;
app.listen(PORT, '0.0.0.0', () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);
});