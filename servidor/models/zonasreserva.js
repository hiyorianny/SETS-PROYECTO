const db = require('../db/db');

class ReservaModel {
    static getByApartamento(apartamento, callback) {
        const query = `
            SELECT sz.*, zc.descripcion as nombre_zona, zc.costo_alquiler
            FROM solicitud_zona sz
            JOIN zona_comun zc ON sz.ID_zonaComun = zc.idZona
            WHERE sz.ID_Apartamentooss = ?
            ORDER BY sz.fechainicio DESC, sz.Hora_inicio DESC
        `;
        
        db.query(query, [apartamento], callback);
    }

    static checkDisponibilidad({ ID_zonaComun, fechainicio, Hora_inicio, Hora_final }, callback) {
        const query = `
            SELECT * FROM solicitud_zona 
            WHERE ID_zonaComun = ? 
            AND fechainicio = ? 
            AND (
                (Hora_inicio < ? AND Hora_final > ?) OR
                (Hora_inicio < ? AND Hora_final > ?) OR
                (Hora_inicio >= ? AND Hora_final <= ?)
            )
            AND estado != 'RECHAZADA'
        `;
        
        db.query(query, [
            ID_zonaComun, fechainicio,
            Hora_final, Hora_inicio,
            Hora_final, Hora_inicio,
            Hora_inicio, Hora_final
        ], callback);
    }

    static crearReserva(reservaData, callback) {
        const { ID_Apartamentooss, ID_zonaComun, fechainicio, fechafinal, Hora_inicio, Hora_final } = reservaData;
        
        const query = `
            INSERT INTO solicitud_zona 
            (ID_Apartamentooss, ID_zonaComun, fechainicio, fechafinal, Hora_inicio, Hora_final, estado)
            VALUES (?, ?, ?, ?, ?, ?, 'PENDIENTE')
        `;
        
        db.query(query, [
            ID_Apartamentooss, ID_zonaComun, 
            fechainicio, fechafinal, 
            Hora_inicio, Hora_final
        ], callback);
    }

    static getReservaDetalles(reservaData, callback) {
        const { ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio } = reservaData;
        
        const query = `
            SELECT sz.*, zc.descripcion as nombre_zona, zc.costo_alquiler
            FROM solicitud_zona sz
            JOIN zona_comun zc ON sz.ID_zonaComun = zc.idZona
            WHERE ID_Apartamentooss = ? AND ID_zonaComun = ? AND fechainicio = ? AND Hora_inicio = ?
        `;
        
        db.query(query, [ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio], callback);
    }

    static cancelarReserva(reservaData, callback) {
        const { ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio } = reservaData;
        
        const query = `
            DELETE FROM solicitud_zona 
            WHERE ID_Apartamentooss = ? 
            AND ID_zonaComun = ? 
            AND fechainicio = ?
            AND Hora_inicio = ?
            AND estado = 'PENDIENTE'
        `;
        
        db.query(query, [ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio], callback);
    }
}

module.exports = ReservaModel;