const mysql = require('mysql');


const db = mysql.createConnection({
    host: 'sets.mysql.database.azure.com',
    user: 'wolwerine24',
    password: 'Apartamento12',
    database: 'sets',
    ssl: {
        ca: fs.readFileSync(__dirname + '/BaltimoreCyberTrustRoot.crt.pem')
    },
    connectTimeout: 60000
})


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