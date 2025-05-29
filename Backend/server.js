const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const path = require('path');
const authRoutes = require('./routes/authRoutes');
const anuncioRoutes = require('./routes/anunciroutes'); 
const torreRoutes = require('./routes/torre');
const reservaRoutes = require('./routes/reservarroutes');
const pagoRoutes = require('./routes/pagosroue');
const citaRoutes = require('./routes/citasroutes');
const ingresoRoutes = require('./routes/ingresosroutes');
const contactoRoutes = require('./routes/contactrouters');
const parqueaderoRoutes = require('./routes/parqueaderoRoutes');
const usuarioRoutes = require('./routes/usuarioRoutes');
const zonaComunRoutes = require('./routes/zonaComunRoutes');
const solicitudZonaRoutes = require('./routes/solicitudZonaRoutes');
const solicitudParqueaderoRoutes = require('./routes/solicitudParqueaderoRoutes');
const mysql = require('mysql');
const multer = require('multer');



const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'sets'
});


db.connect(err => {
    if (err) {
        console.error('Error conectando a la base de datos:', err);
    } else {
        console.log('Conectado a la base de datos MySQL');
    }
});



const app = express();


app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));


const uploadsPath = path.join(__dirname, '..', 'uploads');
app.use('/uploads', express.static(uploadsPath, {
  setHeaders: (res) => {
    res.set('Access-Control-Allow-Origin', '*');
    res.set('Cache-Control', 'no-store, no-cache, must-revalidate, private');
  }
}));

app.use('/api/auth', authRoutes);

app.use('/api', torreRoutes); 
app.use('/api/auth', authRoutes);
app.use('/api/auth', authRoutes);
app.use('/api', anuncioRoutes);
app.use('/api', torreRoutes);
app.use('/api', reservaRoutes);
app.use('/api/auth', authRoutes);

app.use('/api', torreRoutes);
app.use('/api/pagos', pagoRoutes);
app.use('/api/auth', authRoutes);

app.use('/api', torreRoutes);
app.use('/api', pagoRoutes);
app.use('/api/citas', citaRoutes); 
app.use('/api/ingresos', ingresoRoutes);
app.use('/api/contactarnos', contactoRoutes);
app.use('/api/parqueaderos', parqueaderoRoutes); 
app.use('/api/usuarios', usuarioRoutes);
app.use('/api/zonas-comunes', zonaComunRoutes);
app.use('/api/solicitudes-zonas', solicitudZonaRoutes);
app.use('/api/solicitudes-parqueadero', solicitudParqueaderoRoutes);


app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({ error: 'Algo salió mal en el servidor' });
});

app.use(cors({
  origin: 'http://localhost', 
  methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
  allowedHeaders: ['Content-Type', 'Authorization'],
  credentials: true
}));

app.options('*', cors());


const port = process.env.PORT || 3001;
app.listen(port, '192.168.1.102', () => {
    console.log(`Servidor ejecutándose en http://192.168.1.102:${port}`);
});