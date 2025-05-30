const db = require('../db/db');

class SolicitudZonaModel {
    static getByZona(zonaId, callback) {
        const query = 'SELECT * FROM solicitud_zona WHERE ID_zonaComun = ?';
        db.query(query, [zonaId], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }
    static getAll(callback) {
        const query = 'SELECT * FROM solicitud_zona';
        db.query(query, (err, results) => {
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

    static actualizarSolicitud({ ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio, fechafinal, Hora_final, fecha_original, hora_original }, callback) {
        const query = ` UPDATE solicitud_zona  SET fechainicio = ?,      Hora_inicio = ?,  fechafinal = ?,  Hora_final = ?  WHERE ID_Apartamentooss = ?  AND ID_zonaComun = ?
        AND fechainicio = ?
        AND Hora_inicio = ?
    `;

        db.query(query, [
            fechainicio,
            Hora_inicio,
            fechafinal,
            Hora_final,
            ID_Apartamentooss,
            ID_zonaComun,
            fecha_original,
            hora_original
        ], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }

    static cancelarSolicitud({ ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio }, callback) {
        const query = `
            DELETE FROM solicitud_zona 
            WHERE ID_Apartamentooss = ? 
            AND ID_zonaComun = ?
            AND fechainicio = ?
            AND Hora_inicio = ?
        `;

        db.query(query, [ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }
    static getWithLimit(limit, callback) {
        const query = 'SELECT * FROM solicitud_zona LIMIT ?';
        db.query(query, [limit], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }
}

module.exports = SolicitudZonaModel;