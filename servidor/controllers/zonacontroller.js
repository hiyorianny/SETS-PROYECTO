
const ReservaModel = require('../models/zonasreserva');

class ReservaController {
    static async getMisReservas(req, res) {
        try {
            const { apartamento } = req.params;
            
            ReservaModel.getByApartamento(apartamento, (err, results) => {
                if (err) {
                    console.error('Error al obtener reservas:', err);
                    return res.status(500).json({ error: 'Error al obtener reservas' });
                }
                res.json(results);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async crearReserva(req, res) {
        try {
            const { ID_Apartamentooss, ID_zonaComun, fechainicio, fechafinal, Hora_inicio, Hora_final } = req.body;
            
            // Validaciones básicas
            if (!ID_Apartamentooss || !ID_zonaComun || !fechainicio || !fechafinal || !Hora_inicio || !Hora_final) {
                return res.status(400).json({ error: 'Todos los campos son requeridos' });
            }
            
            // Verificar disponibilidad
            ReservaModel.checkDisponibilidad({
                ID_zonaComun, fechainicio, Hora_inicio, Hora_final
            }, (err, results) => {
                if (err) {
                    console.error('Error al verificar disponibilidad:', err);
                    return res.status(500).json({ error: 'Error al verificar disponibilidad' });
                }
                
                if (results.length > 0) {
                    return res.status(400).json({ 
                        error: 'La zona ya está reservada en ese horario',
                        conflicto: results[0]
                    });
                }
                
                // Crear reserva
                ReservaModel.crearReserva({
                    ID_Apartamentooss, ID_zonaComun, fechainicio, fechafinal, Hora_inicio, Hora_final
                }, (err, results) => {
                    if (err) {
                        console.error('Error al crear reserva:', err);
                        return res.status(500).json({ error: 'Error al crear reserva' });
                    }
                    
                    // Obtener detalles completos de la reserva
                    ReservaModel.getReservaDetalles({
                        ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio
                    }, (err, reserva) => {
                        if (err || reserva.length === 0) {
                            console.error('Error al obtener reserva:', err);
                            return res.status(500).json({ 
                                success: true,
                                message: 'Reserva creada pero no se pudieron obtener los detalles completos'
                            });
                        }
                        
                        res.json({ 
                            success: true, 
                            message: 'Reserva solicitada correctamente',
                            reserva: reserva[0]
                        });
                    });
                });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async cancelarReserva(req, res) {
        try {
            const { ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio } = req.body;
            
            if (!ID_Apartamentooss || !ID_zonaComun || !fechainicio || !Hora_inicio) {
                return res.status(400).json({ error: 'Datos incompletos para cancelar reserva' });
            }
            
            ReservaModel.cancelarReserva({
                ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio
            }, (err, results) => {
                if (err) {
                    console.error('Error al cancelar reserva:', err);
                    return res.status(500).json({ error: 'Error al cancelar reserva' });
                }
                
                if (results.affectedRows === 0) {
                    return res.status(404).json({ 
                        error: 'Reserva no encontrada o no se puede cancelar',
                        details: 'Solo se pueden cancelar reservas pendientes'
                    });
                }
                
                res.json({ success: true });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = ReservaController;