const express = require('express');
const router = express.Router();
const SolicitudZonaController = require('../controllers/SolicitudZonaController');

router.get('/', SolicitudZonaController.getAll);
router.post('/actualizar-estado', SolicitudZonaController.updateStatus);

module.exports = router;