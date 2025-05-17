const db = require('../db/db');

class ContactoModel {
    static getAll(callback) {
        const query = 'SELECT * FROM contactarnos';
        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }

    static delete(id, callback) {
        const query = 'DELETE FROM contactarnos WHERE idcontactarnos = ?';
        db.query(query, [id], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results.affectedRows);
        });
    }
}

module.exports = ContactoModel;