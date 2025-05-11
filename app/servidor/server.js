const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const path = require('path');
const authRoutes = require('./routes/authRoutes');

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


app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({ error: 'Algo salió mal en el servidor' });
});

const port = process.env.PORT || 3000;
app.listen(port, '192.168.1.105', () => {
    console.log(`Servidor ejecutándose en http://192.168.1.105:${port}`);
});