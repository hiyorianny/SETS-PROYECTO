const db = require('../db/db'); 

class AnuncioModel {
    static getAll(callback) {
        const query = 'SELECT * FROM anuncio ORDER BY fechaPublicacion DESC, horaPublicacion DESC';
        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            callback(null, results);
        });
    }

    static create({ titulo, descripcion, persona, apart, img_anuncio }, callback) {
        const fechaPublicacion = new Date().toISOString().split('T')[0];
        const horaPublicacion = new Date().toLocaleTimeString();
        
        const query = 'INSERT INTO anuncio (titulo, descripcion, fechaPublicacion, horaPublicacion, persona, apart, img_anuncio) VALUES (?, ?, ?, ?, ?, ?, ?)';
        
        db.query(query, [titulo, descripcion, fechaPublicacion, horaPublicacion, persona, apart, img_anuncio], (err, result) => {
            if (err) return callback(err, null);
            callback(null, result.insertId);
        });
    }

    static delete(id, callback) {
        const query = 'DELETE FROM anuncio WHERE idAnuncio = ?';
        db.query(query, [id], (err, result) => {
            if (err) return callback(err, null);
            callback(null, result.affectedRows);
        });
    }
}

module.exports = AnuncioModel;