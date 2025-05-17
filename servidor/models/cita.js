const db = require('../db/db');

class CitaModel {
    static getAll(callback) {
        const query = 'SELECT * FROM cita';
        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }

    static create({ fechacita, horacita, tipocita, apa, estado = 'pendiente' }, callback) {
        if (!fechacita || !horacita || !tipocita || !apa) {
            return callback(new Error('Faltan campos requeridos'), null);
        }

        const query = `
            INSERT INTO cita 
            (fechacita, horacita, tipocita, apa, estado) 
            VALUES (?, ?, ?, ?, ?)
        `;
        
        db.query(query, 
            [fechacita, horacita, tipocita, apa, estado], 
            (err, results) => {
                if (err) return callback(err, null);
                callback(null, results.insertId);
            }
        );
    }

    static responder(idcita, respuesta, callback) {
        const query = `
            UPDATE cita 
            SET respuesta = ?, estado = 'respondida' 
            WHERE idcita = ?
        `;

        db.query(query, [respuesta, idcita], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results.affectedRows);
        });
    }

    static delete(idcita, callback) {
        const query = 'DELETE FROM cita WHERE idcita = ?';
        db.query(query, [idcita], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results.affectedRows);
        });
    }
}

module.exports = CitaModel;