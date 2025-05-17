const db = require('../db/db');

class ZonaComunModel {
    static getAll(callback) {
        const query = 'SELECT idZona, descripcion, costo_alquiler, url_videos FROM zona_comun';
        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }
}

module.exports = ZonaComunModel;