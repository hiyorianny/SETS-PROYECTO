const { connection, jwtConfig } = require('../config');
const jwt = require('jsonwebtoken');

class Token {
    static generate(userId, callback) {
        if (!jwtConfig.secretKey) {
            return callback(new Error('La clave secreta no está configurada'));
        }

        const token = jwt.sign({ id: userId }, jwtConfig.secretKey, {
            expiresIn: jwtConfig.tokenExpiration || '24h'
        });

        const expirationDate = new Date();
        expirationDate.setHours(expirationDate.getHours() + 24);

        connection.query(
            'INSERT INTO tokens (id_Registro, token, fecha_expiracion) VALUES (?, ?, ?)',
            [userId, token, expirationDate],
            (err) => {
                if (err) return callback(err);
                callback(null, token);
            }
        );
    }

    static verify(token, callback) {
        jwt.verify(token, jwtConfig.secretKey, (err, decoded) => {
            if (err) return callback(err);
            
            connection.query(
                'SELECT * FROM tokens WHERE token = ? AND id_Registro = ? AND fecha_expiracion > NOW() LIMIT 1',
                [token, decoded.id],
                (err, results) => {
                    if (err) return callback(err);
                    callback(null, results.length > 0 ? decoded : null);
                }
            );
        });
    }
}

module.exports = Token;