const mysql = require('mysql');


const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '', 
    database: 'sets'
});


db.connect((err) => {
    if (err) {
        console.error('Error conectando a la base de datos:', err);
    } else {
        console.log('Base de datos conectada');
    }
});


const jwtConfig = {
    secretKey: 'tu_clave_secreta_para_jwt', 
    tokenExpiration: '24h'
};


module.exports = {
    connection: db, 
    jwtConfig
};