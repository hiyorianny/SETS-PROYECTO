const express = require('express');
const router = express.Router();
const SolicitudZonaController = require('../controllers/SolicitudZonaController');

router.get('/', SolicitudZonaController.getAll);
router.post('/actualizar-estado', SolicitudZonaController.updateStatus);
router.put('/:ID_Apartamentooss/actualizar-estado', SolicitudZonaController.updateStatusById);

module.exports = router;