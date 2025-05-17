const ParqueaderoModel = require('../models/park');

class ParqueaderoController {
    static async getAll(req, res) {
        try {
            ParqueaderoModel.getAll((err, parqueaderos) => {
                if (err) {
                    console.error('Error al obtener parqueaderos:', err);
                    return res.status(500).json({ error: 'Error al obtener parqueaderos' });
                }
                res.json(parqueaderos);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = ParqueaderoController;