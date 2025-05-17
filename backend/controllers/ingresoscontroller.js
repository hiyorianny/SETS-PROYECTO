const IngresoModel = require('../models/ingresos');

class IngresoController {
    static async create(req, res) {
        try {
            const { tipo_ingreso, placa, personasIngreso, documento, horaFecha } = req.body;

            IngresoModel.create(
                { tipo_ingreso, placa, personasIngreso, documento, horaFecha },
                (err, id) => {
                    if (err) {
                        console.error('Error al insertar ingreso:', err);
                        return res.status(500).json({ error: 'Error al registrar el ingreso' });
                    }
                    res.json({ success: true, id });
                }
            );
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async getAll(req, res) {
        try {
            IngresoModel.getAll((err, ingresos) => {
                if (err) {
                    console.error('Error al obtener ingresos:', err);
                    return res.status(500).json({ error: 'Error al obtener ingresos' });
                }
                res.json(ingresos);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async delete(req, res) {
        try {
            const { id } = req.params;
            
            IngresoModel.delete(id, (err, affectedRows) => {
                if (err) {
                    console.error('Error al eliminar ingreso:', err);
                    return res.status(500).json({ error: 'Error al eliminar ingreso' });
                }
                
                if (affectedRows === 0) {
                    return res.status(404).json({ error: 'Ingreso no encontrado' });
                }
                
                res.json({ success: true });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = IngresoController;