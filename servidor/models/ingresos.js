const db = require('../db/db');

class IngresoModel {
    static create({ tipo_ingreso, placa, personasIngreso, documento, horaFecha }, callback) {
        const query = `
            INSERT INTO ingreso_peatonal 
            (personasIngreso, horaFecha, documento, tipo_ingreso, placa) 
            VALUES (?, ?, ?, ?, ?)
        `;

        db.query(query,
            [personasIngreso, horaFecha, documento, tipo_ingreso, placa],
            (err, results) => {
                if (err) return callback(err, null);
                callback(null, results.insertId);
            }
        );
    }

    static getAll(callback) {
        const query = 'SELECT * FROM ingreso_peatonal';
        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }

    static delete(idIngreso, callback) {
        const query = 'DELETE FROM ingreso_peatonal WHERE idIngreso_Peatonal = ?';
        db.query(query, [idIngreso], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results.affectedRows);
        });
    }
}

module.exports = IngresoModel;