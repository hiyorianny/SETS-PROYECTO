const db = require('../db/db');

class SolicitudZonaModel {
   static getByZona(zonaId, callback) {
    const query = 'SELECT * FROM solicitud_zona WHERE ID_zonaComun  = ?';
    db.query(query, [zonaId], (err, results) => {
        if (err) return callback(err, null);
        callback(null, results);
    });
}

    static updateStatus({ ID_Apartamentooss, ID_zonaComun, fechainicio, estado }, callback) {
        const query = `
            UPDATE solicitud_zona 
            SET estado = ? 
            WHERE ID_Apartamentooss = ? 
            AND ID_zonaComun = ? 
            AND fechainicio = ?
        `;
        
        db.query(query, [estado, ID_Apartamentooss, ID_zonaComun, fechainicio], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }
}

module.exports = SolicitudZonaModel;