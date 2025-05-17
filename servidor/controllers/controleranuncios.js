const AnuncioModel = require('../models/anuncio');

class AnuncioController {
    static async getAll(req, res) {
        try {
            AnuncioModel.getAll((err, anuncios) => {
                if (err) {
                    console.error('Error en la consulta:', err);
                    return res.status(500).json({ error: err.message });
                }
                console.log('Anuncios recuperados:', anuncios.length);
                res.json(anuncios);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async create(req, res) {
        try {
            const { titulo, descripcion, persona, apart, img_anuncio } = req.body;
            AnuncioModel.create({ titulo, descripcion, persona, apart, img_anuncio }, (err, id) => {
                if (err) {
                    console.error('Error al insertar anuncio:', err);
                    return res.status(500).json({ error: err.message });
                }
                res.status(201).json({ id, message: 'Anuncio creado exitosamente' });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async delete(req, res) {
        try {
            const { id } = req.params;
            AnuncioModel.delete(id, (err, affectedRows) => {
                if (err) {
                    console.error('Error al eliminar anuncio:', err);
                    return res.status(500).json({ error: err.message });
                }
                if (affectedRows === 0) {
                    return res.status(404).json({ message: 'Anuncio no encontrado' });
                }
                res.json({ message: 'Anuncio eliminado exitosamente' });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = AnuncioController;