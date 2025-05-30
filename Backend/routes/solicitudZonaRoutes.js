const express = require('express');
const router = express.Router();
const SolicitudZonaController = require('../controllers/SolicitudZonaController');

router.get('/', SolicitudZonaController.getAll);
router.post('/actualizar-estado', SolicitudZonaController.updateStatus);
router.put('/:ID_Apartamentooss/actualizar-estado', SolicitudZonaController.updateStatusById);
router.put('/actualizar', SolicitudZonaController.actualizarSolicitud);
router.delete('/cancelar', SolicitudZonaController.cancelarSolicitud);
router.get('/limitadas', SolicitudZonaController.getLimited);


module.exports = router;