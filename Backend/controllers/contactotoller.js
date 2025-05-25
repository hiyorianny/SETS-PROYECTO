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

    static async create(req, res) {
        try {
            const { nombre, correo, telefono, comentario } = req.body;
            

            if (!nombre || !correo || !comentario) {
                return res.status(400).json({ error: 'Faltan campos obligatorios' });
            }

            ContactoModel.create(
                { nombre, correo, telefono, comentario },
                (err, insertId) => {
                    if (err) {
                        console.error('Error al crear contacto:', err);
                        return res.status(500).json({ error: 'Error al crear contacto' });
                    }
                    res.status(201).json({ 
                        success: true,
                        id: insertId,
                        message: 'Contacto creado exitosamente' 
                    });
                }
            );
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