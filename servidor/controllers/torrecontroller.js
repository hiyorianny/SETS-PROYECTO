const TorreModel = require('../models/torre');

class TorreController {
    static async getAll(req, res) {
        try {
            TorreModel.getAll((err, data) => {
                if (err) {
                    console.error('Error en la consulta:', err);
                    return res.status(500).json({ error: err.message });
                }
                res.json(data);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = TorreController;
