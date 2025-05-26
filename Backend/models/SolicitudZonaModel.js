const db = require('../db/db');

class SolicitudZonaModel {
    static getByZona(zonaId, callback) {
        const query = 'SELECT * FROM solicitud_zona WHERE ID_zonaComun  = ?';
        db.query(query, [zonaId], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }


    static updateStatusById(ID_Apartamentooss, estado, callback) {
        if (!ID_Apartamentooss || !estado) {
            return callback(new Error('ID_Apartamentooss y estado son requeridos'), null);
        }

        const query = `
            UPDATE solicitud_zona 
            SET estado = ? 
            WHERE ID_Apartamentooss = ?
        `;

        db.query(query, [estado, ID_Apartamentooss], (err, results) => {
            if (err) {
                console.error('Error en updateStatusById:', err);
                return callback(err, null);
            }
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