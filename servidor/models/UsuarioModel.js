const db = require('../db/db');

class UsuarioModel {
    static getAll(callback) {
        const query = `
            SELECT 
                r.id_Registro,
                r.PrimerNombre,
                r.SegundoNombre,
                r.PrimerApellido,
                r.SegundoApellido,
                r.apartamento,
                r.Correo,
                r.telefonoUno,
                r.tipo_propietario,
                r.Usuario,
                r.Clave,
                r.imagenPerfil,
                r.numeroDocumento,
                rol.Roldescripcion
            FROM registro r
            LEFT JOIN rol ON r.idROL = rol.id
            ORDER BY r.PrimerNombre, r.PrimerApellido
        `;

        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }

    static delete(id, callback) {
        const query = 'DELETE FROM registro WHERE id_Registro = ?';
        db.query(query, [id], (err, results) => {
            if (err) return callback(err, null);
            callback(null, results.affectedRows);
        });
    }
}

module.exports = UsuarioModel;