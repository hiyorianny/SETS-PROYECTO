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
            const { ID_Apartamentooss, estado } = req.body;

            if (!ID_Apartamentooss || !estado) {
                return res.status(400).json({
                    error: 'Datos incompletos',
                    details: 'Se requieren ID_Apartamentooss y estado'
                });
            }

            SolicitudZonaModel.updateStatusById(
                ID_Apartamentooss,
                estado,
                (err, results) => {
                    if (err) {
                        console.error('Error al actualizar estado:', err);
                        return res.status(500).json({
                            error: 'Error al actualizar estado',
                            details: err.message
                        });
                    }

                    if (results.affectedRows === 0) {
                        return res.status(404).json({
                            error: 'Solicitud no encontrada'
                        });
                    }

                    res.json({ success: true });
                }
            );
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async updateStatusById(req, res) {
        try {
            const { ID_Apartamentooss } = req.params;
            const { estado } = req.body;

            if (!ID_Apartamentooss || !estado) {
                return res.status(400).json({
                    error: 'Datos incompletos',
                    details: 'Se requieren ID_Apartamentooss y estado'
                });
            }

            SolicitudZonaModel.updateStatusById(
                ID_Apartamentooss,
                estado,
                (err, results) => {
                    if (err) {
                        console.error('Error en la consulta SQL:', err);
                        return res.status(500).json({
                            error: 'Error al actualizar estado',
                            details: err.message
                        });
                    }

                    if (results.affectedRows === 0) {
                        return res.status(404).json({
                            error: 'Solicitud no encontrada',
                            details: `No se encontró la solicitud con ID_Apartamentooss ${ID_Apartamentooss}`
                        });
                    }

                    res.json({ success: true });
                }
            );
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async actualizarSolicitud(req, res) {
        try {
            const {
                ID_Apartamentooss,
                ID_zonaComun,
                fechainicio,
                Hora_inicio,
                fechafinal,
                Hora_final,
                fecha_original,
                hora_original
            } = req.body;

            // Validaciones básicas
            if (!ID_Apartamentooss || !ID_zonaComun || !fechainicio || !Hora_inicio || !fechafinal || !Hora_final) {
                return res.status(400).json({
                    success: false,
                    error: 'Todos los campos son requeridos'
                });
            }

            // Validación de fechas
            if (new Date(fechafinal) < new Date(fechainicio)) {
                return res.status(400).json({
                    success: false,
                    error: 'La fecha final no puede ser anterior a la fecha de inicio'
                });
            }

            // Validación de horas si es el mismo día
            if (fechainicio === fechafinal && Hora_inicio >= Hora_final) {
                return res.status(400).json({
                    success: false,
                    error: 'La hora de inicio debe ser anterior a la hora final'
                });
            }

            SolicitudZonaModel.actualizarSolicitud(
                {
                    ID_Apartamentooss,
                    ID_zonaComun,
                    fechainicio,
                    Hora_inicio,
                    fechafinal,
                    Hora_final,
                    fecha_original,
                    hora_original
                },
                (err, results) => {
                    if (err) {
                        console.error('Error en la base de datos:', err);
                        return res.status(500).json({
                            success: false,
                            error: 'Error en la base de datos',
                            details: err.message
                        });
                    }

                    if (results.affectedRows === 0) {
                        return res.status(404).json({
                            success: false,
                            error: 'No se encontró la solicitud para actualizar'
                        });
                    }

                    res.json({ success: true });
                }
            );
        } catch (error) {
            console.error('Error en el controlador:', error);
            res.status(500).json({
                success: false,
                error: error.message
            });
        }
    }
    static async cancelarSolicitud(req, res) {
        try {
            const { ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio } = req.body;

            if (!ID_Apartamentooss || !ID_zonaComun || !fechainicio || !Hora_inicio) {
                return res.status(400).json({ error: 'Datos incompletos' });
            }

            SolicitudZonaModel.cancelarSolicitud(
                { ID_Apartamentooss, ID_zonaComun, fechainicio, Hora_inicio },
                (err, results) => {
                    if (err) {
                        console.error('Error al cancelar solicitud:', err);
                        return res.status(500).json({ error: 'Error al cancelar solicitud' });
                    }

                    if (results.affectedRows === 0) {
                        return res.status(404).json({ error: 'Solicitud no encontrada' });
                    }

                    res.json({ success: true });
                }
            );
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
    static async getLimited(req, res) {
        try {
            const limit = parseInt(req.query.limit) || 10;

            if (isNaN(limit) || limit <= 0) {
                return res.status(400).json({ error: 'El parámetro limit debe ser un número positivo' });
            }

            SolicitudZonaModel.getWithLimit(limit, (err, solicitudes) => {
                if (err) {
                    console.error('Error al obtener solicitudes limitadas:', err);
                    return res.status(500).json({ error: 'Error al obtener solicitudes' });
                }
                res.json(solicitudes); 
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

}

module.exports = SolicitudZonaController;