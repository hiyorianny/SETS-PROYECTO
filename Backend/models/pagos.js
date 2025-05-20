const db = require('../db/db');

class PagoModel {
    static getAll(callback) {
        const query = `
            SELECT p.*, r.PrimerNombre, r.PrimerApellido, r.apartamento 
            FROM pagos p
            LEFT JOIN registro r ON p.apart = r.apartamento
            ORDER BY p.fechaPago DESC
        `;
        
        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }

    static create({ pagoPor, cantidad, mediopago, apart, fechaPago, estado, referenciaPago }, callback) {
        const query = `
            INSERT INTO pagos 
            (pagoPor, cantidad, mediopago, apart, fechaPago, estado, referenciaPago) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        `;
        
        db.query(query, 
            [pagoPor, cantidad, mediopago, apart, fechaPago, estado, referenciaPago], 
            (err, results) => {
                if (err) return callback(err, null);
                callback(null, results.insertId);
            }
        );
    }

    static updateStatus(idPagos, estado, callback) {
        if (!['Pendiente', 'Pagado', 'Vencido'].includes(estado)) {
            return callback(new Error('Estado no válido'), null);
        }

        const query = `UPDATE pagos SET estado = ? WHERE idPagos = ?`;
        
        db.query(query, [estado, idPagos], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results.affectedRows);
        });
    }

    static delete(idPagos, callback) {
        const query = 'DELETE FROM pagos WHERE idPagos = ?';
        db.query(query, [idPagos], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results.affectedRows);
        });
    }
}

module.exports = PagoModel;