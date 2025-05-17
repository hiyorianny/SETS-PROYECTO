// En tu archivo principal (app.js o index.js)
const mysql = require('mysql');

// Configuración mejorada con manejo de errores
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'sets',
  connectTimeout: 10000 // 10 segundos de timeout
});

// Conexión con manejo de errores
db.connect((err) => {
  if (err) {
    console.error('Error de conexión a MySQL:', err.stack);
    process.exit(1); // Termina la aplicación si no puede conectar
  }
  console.log('Conectado a MySQL como ID', db.threadId);
});

// Exporta la conexión para usarla en otras rutas
module.exports = db;