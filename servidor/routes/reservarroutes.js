const express = require('express');
const router = express.Router();
const ReservaController = require('../controllers/zonacontroller');

router.get('/mis-reservas/:apartamento', ReservaController.getMisReservas);
router.post('/reservar-zona', ReservaController.crearReserva);
router.delete('/cancelar-reserva', ReservaController.cancelarReserva);

module.exports = router;