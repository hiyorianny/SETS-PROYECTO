const db = require('../db/db');

class SolicitudParqueaderoModel {
    static getAll(callback) {
        const query = `
            SELECT * FROM solicitud_parqueadero 
            ORDER BY fecha_inicio DESC
        `;
        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }

    static getEstadoParqueaderos(callback) {
        const query = `
            SELECT 
                p.parqueadero_visitante AS parqueadero,
                CASE 
                    WHEN p.estado = 'aprobado' AND NOW() BETWEEN p.fecha_inicio AND p.fecha_final THEN 'ocupado'
                    WHEN p.estado = 'aprobado' AND NOW() < p.fecha_inicio THEN 'reservado'
                    ELSE 'disponible'
                END AS estado,
                IFNULL(p.nombre_visitante, '') AS visitante,
                IFNULL(p.placaVehiculo, '') AS placa,
                IFNULL(CONCAT(DATE_FORMAT(p.fecha_inicio, '%d/%m/%Y %H:%i'), ' - ', DATE_FORMAT(p.fecha_final, '%d/%m/%Y %H:%i')), '') AS horario
            FROM 
                (SELECT 'V1' AS parqueadero_visitante UNION SELECT 'V2' UNION SELECT 'V3' UNION 
                 SELECT 'V4' UNION SELECT 'V5' UNION SELECT 'V6' UNION 
                 SELECT 'V7' UNION SELECT 'V8' UNION SELECT 'V9' UNION SELECT 'V10') AS todos_p
            LEFT JOIN solicitud_parqueadero p ON 
                todos_p.parqueadero_visitante = p.parqueadero_visitante AND
                p.estado = 'aprobado' AND
                NOW() <= p.fecha_final
            GROUP BY 
                todos_p.parqueadero_visitante
            ORDER BY parqueadero
        `;
        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }

    static create(data, callback) {
        const query = `
            INSERT INTO solicitud_parqueadero 
            (id_apartamento, parqueadero_visitante, nombre_visitante, placaVehiculo, 
             colorVehiculo, tipoVehiculo, modelo, marca, fecha_inicio, fecha_final, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        `;
        const values = [
            data.id_apartamento,
            data.parqueadero_visitante,
            data.nombre_visitante,
            data.placaVehiculo,
            data.colorVehiculo,
            data.tipoVehiculo,
            data.modelo,
            data.marca,
            data.fecha_inicio,
            data.fecha_final,
            data.estado || 'pendiente'
        ];

        db.query(query, values, (err, result) => {
            if (err) return callback(err, null);
            callback(null, result.insertId);
        });
    }

    static delete(id, callback) {
        const query = 'DELETE FROM solicitud_parqueadero WHERE id_solicitud = ?';
        db.query(query, [id], (err, result) => {
            if (err) return callback(err, null);
            callback(null, result.affectedRows);
        });
    }
}

module.exports = SolicitudParqueaderoModel;