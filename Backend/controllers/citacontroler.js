const CitaModel = require('../models/cita');

class CitaController {
    static async getAll(req, res) {
        try {
            CitaModel.getAll((err, citas) => {
                if (err) {
                    console.error('Error al obtener citas:', err);
                    return res.status(500).json({ error: 'Error al obtener citas' });
                }
                res.json(citas);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async create(req, res) {
        try {
            const { fechacita, horacita, tipocita, apa, estado } = req.body;
            
            CitaModel.create(
                { fechacita, horacita, tipocita, apa, estado },
                (err, id) => {
                    if (err) {
                        console.error('Error en la consulta SQL:', err);
                        return res.status(500).json({ 
                            error: err.message === 'Faltan campos requeridos' ? 
                                   'Faltan campos requeridos' : 'Error al crear cita',
                            sqlMessage: err.sqlMessage 
                        });
                    }
                    res.json({ success: true, id });
                }
            );
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async responder(req, res) {
        try {
            const { idcita, respuesta } = req.body;
            
            CitaModel.responder(idcita, respuesta, (err, affectedRows) => {
                if (err) {
                    console.error('Error al responder cita:', err);
                    return res.status(500).json({ error: 'Error al responder cita' });
                }
                res.json({ success: true });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async delete(req, res) {
        try {
            const { id } = req.params;
            
            CitaModel.delete(id, (err, affectedRows) => {
                if (err) {
                    console.error('Error al eliminar cita:', err);
                    return res.status(500).json({ error: 'Error al eliminar cita' });
                }
                
                if (affectedRows === 0) {
                    return res.status(404).json({ error: 'Cita no encontrada' });
                }
                
                res.json({ success: true });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = CitaController;