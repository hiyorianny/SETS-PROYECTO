const mysql = require('mysql');
const fs = require('fs');
const path = require('path');
const sslCert = fs.readFileSync(path.join(__dirname, '../ssl/DigiCertGlobalRootCA.crt.pem'));

const db = mysql.createConnection({
    host: 'sets.mysql.database.azure.com',
    user: 'wolwerine24',
    password: 'Apartamento12',
    database: 'sets',
    connectTimeout: 60000,
    ssl: {
        ca: sslCert, // Certificado SSL
        rejectUnauthorized: true
    }
});

db.connect((err) => {
    if (err) {
        console.error('Error conectando a la base de datos:', err);
    } else {
        console.log('Base de datos conectada con SSL personalizado');
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