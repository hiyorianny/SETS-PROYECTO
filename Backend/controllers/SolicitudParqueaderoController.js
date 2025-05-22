const SolicitudParqueaderoModel = require('../models/SolicitudParqueaderoModel');

class SolicitudParqueaderoController {
    static getAll(req, res) {
        SolicitudParqueaderoModel.getAll((err, results) => {
            if (err) {
                return res.status(500).json({ error: err.message });
            }
            res.json(results);
        });
    }

    static getEstadoParqueaderos(req, res) {
        SolicitudParqueaderoModel.getEstadoParqueaderos((err, results) => {
            if (err) {
                return res.status(500).json({ error: err.message });
            }
            res.json(results);
        });
    }

    static create(req, res) {
        SolicitudParqueaderoModel.create(req.body, (err, id) => {
            if (err) {
                return res.status(500).json({ error: err.message });
            }
            res.status(201).json({ id_solicitud: id });
        });
    }

    static delete(req, res) {
        SolicitudParqueaderoModel.delete(req.params.id, (err, affectedRows) => {
            if (err) {
                return res.status(500).json({ error: err.message });
            }
            if (affectedRows === 0) {
                return res.status(404).json({ error: 'Solicitud no encontrada' });
            }
            res.json({ message: 'Solicitud eliminada correctamente' });
        });
    }
}

module.exports = SolicitudParqueaderoController;