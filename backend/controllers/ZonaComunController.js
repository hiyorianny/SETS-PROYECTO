const ZonaComunModel = require('../models/ZonaComunModel');

class ZonaComunController {
    static async getAll(req, res) {
        try {
            ZonaComunModel.getAll((err, zonas) => {
                if (err) {
                    console.error('Error al obtener zonas comunes:', err);
                    return res.status(500).json({ error: 'Error al obtener zonas comunes' });
                }
                res.json(zonas);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = ZonaComunController;