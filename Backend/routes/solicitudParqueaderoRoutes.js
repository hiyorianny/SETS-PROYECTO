const express = require('express');
const router = express.Router();
const SolicitudParqueaderoController = require('../controllers/SolicitudParqueaderoController');

router.get('/', SolicitudParqueaderoController.getAll);

router.get('/estado', SolicitudParqueaderoController.getEstadoParqueaderos);

router.post('/', SolicitudParqueaderoController.create);

router.delete('/:id', SolicitudParqueaderoController.delete);
router.put('/:id/estado', SolicitudParqueaderoController.updateEstado);

module.exports = router;