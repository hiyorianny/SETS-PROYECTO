const PagoModel = require('../models/pagos');

class PagoController {
    static async getAll(req, res) {
        try {
            PagoModel.getAll((err, pagos) => {
                if (err) {
                    console.error('Error al obtener pagos:', err);
                    return res.status(500).json({ error: 'Error al obtener pagos' });
                }
                res.json(pagos);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async create(req, res) {
        try {
            const { pagoPor, cantidad, mediopago, apart, fechaPago, estado, referenciaPago } = req.body;
            
            PagoModel.create(
                { pagoPor, cantidad, mediopago, apart, fechaPago, estado, referenciaPago },
                (err, id) => {
                    if (err) {
                        console.error('Error al crear pago:', err);
                        return res.status(500).json({ error: 'Error al crear pago' });
                    }
                    res.json({ success: true, id });
                }
            );
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async updateStatus(req, res) {
        try {
            const { idPagos } = req.params;
            const { estado } = req.body;
            
            PagoModel.updateStatus(idPagos, estado, (err, affectedRows) => {
                if (err) {
                    console.error('Error al actualizar pago:', err);
                    return res.status(500).json({ 
                        error: 'Error al actualizar pago',
                        details: err.message 
                    });
                }
                if (affectedRows === 0) {
                    return res.status(404).json({ error: 'Pago no encontrado' });
                }
                res.json({ 
                    success: true,
                    message: 'Estado actualizado correctamente' 
                });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async delete(req, res) {
        try {
            const { id } = req.params;
            
            PagoModel.delete(id, (err, affectedRows) => {
                if (err) {
                    console.error('Error al eliminar pago:', err);
                    return res.status(500).json({ error: 'Error al eliminar pago' });
                }
                res.json({ success: true });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = PagoController;