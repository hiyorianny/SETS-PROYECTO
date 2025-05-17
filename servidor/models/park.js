const db = require('../db/db');

class ParqueaderoModel {
    static getAll(callback) {
        const query = `SELECT * FROM parqueadero`;
        
        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }
}

module.exports = ParqueaderoModel;