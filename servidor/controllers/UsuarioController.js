const UsuarioModel = require('../models/UsuarioModel');

class UsuarioController {
    static async getAll(req, res) {
        try {
            UsuarioModel.getAll((err, usuarios) => {
                if (err) {
                    console.error('Error al obtener usuarios:', err);
                    return res.status(500).json({ error: 'Error al obtener usuarios' });
                }
                res.json(usuarios);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async delete(req, res) {
        try {
            const { id } = req.params;
            
            UsuarioModel.delete(id, (err, affectedRows) => {
                if (err) {
                    console.error('Error al eliminar usuario:', err);
                    return res.status(500).json({ error: 'Error al eliminar usuario' });
                }
                
                if (affectedRows === 0) {
                    return res.status(404).json({ error: 'Usuario no encontrado' });
                }
                
                res.json({ success: true });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = UsuarioController;