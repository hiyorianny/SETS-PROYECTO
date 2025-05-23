const AnuncioModel = require('../models/anuncio');

class AnuncioController {
    static async getAll(req, res) {
        try {
            AnuncioModel.getAll((err, anuncios) => {
                if (err) {
                    console.error('Error en la consulta:', err);
                    return res.status(500).json({ error: err.message });
                }
                res.json(anuncios);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async create(req, res) {
        try {
            // Obtener los datos del formulario
            const { titulo, descripcion, persona, apart } = req.body;

            // Validar campos obligatorios
            if (!titulo || !descripcion || !persona) {
                return res.status(400).json({
                    error: 'Faltan campos obligatorios (título, descripción o persona)'
                });
            }

            // Establecer img_anuncio como null ya que no lo estás usando
            const img_anuncio = null;

            AnuncioModel.create({
                titulo,
                descripcion,
                persona,
                apart: apart || null,
                img_anuncio
            }, (err, id) => {
                if (err) {
                    console.error('Error al insertar anuncio:', err);
                    return res.status(500).json({
                        error: err.message,
                        sqlError: err.sqlMessage
                    });
                }
                res.status(201).json({
                    id,
                    message: 'Anuncio creado exitosamente'
                });
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