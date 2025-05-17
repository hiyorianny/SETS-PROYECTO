const ContactoModel = require('../models/contacto');

class ContactoController {
    static async getAll(req, res) {
        try {
            ContactoModel.getAll((err, contactos) => {
                if (err) {
                    console.error('Error al obtener contactos:', err);
                    return res.status(500).json({ error: 'Error al obtener contactos' });
                }
                res.json(contactos);
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }

    static async delete(req, res) {
        try {
            const { id } = req.params;
            
            ContactoModel.delete(id, (err, affectedRows) => {
                if (err) {
                    console.error('Error al eliminar contacto:', err);
                    return res.status(500).json({ error: 'Error al eliminar contacto' });
                }
                
                if (affectedRows === 0) {
                    return res.status(404).json({ error: 'Contacto no encontrado' });
                }
                
                res.json({ success: true });
            });
        } catch (error) {
            res.status(500).json({ error: error.message });
        }
    }
}

module.exports = ContactoController;