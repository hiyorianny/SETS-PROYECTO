const SolicitudZonaModel = require('../models/SolicitudZonaModel');

class SolicitudZonaController {
   static async getAll(req, res) {
    try {
        const { zona } = req.query;
        
        SolicitudZonaModel.getByZona(zona, (err, solicitudes) => {
            if (err) {
                console.error('Error al obtener solicitudes de zonas:', err);
                return res.status(500).json({ error: 'Error al obtener solicitudes' });
            }
            res.json(solicitudes);
        });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
}

    static async updateStatus(req, res) {
        try {
            const { ID_Apartamentooss, ID_zonaComun, fechainicio, estado } = req.body;
            
            console.log('Datos recibidos:', { ID_Apartamentooss, ID_zonaComun, fechainicio, estado });
            
            SolicitudZonaModel.updateStatus(
                { ID_Apartamentooss, ID_zonaComun, fechainicio, estado },
                (err, results) => {
                    if (err) {
                        console.error('Error en la consulta SQL:', err);
                        return res.status(500).json({ 
                            error: 'Error al actualizar estado',
                            sqlMessage: err.sqlMessage,
                            sql: err.sql 
                        });
                    }
                    
                    console.log('Resultados de la actualización:', results);
                    
                    if (results.affectedRows === 0) {
                        return res.status(404).json({ 
                            error: 'Solicitud no encontrada',
                            details: `No se encontró la solicitud con los parámetros proporcionados`
                        });
                    }
                    
                    res.json({ 
                        success: true,
                        affectedRows: results.affectedRows
                    });
                }
            );
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = SolicitudZonaController;