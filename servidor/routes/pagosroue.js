const express = require('express');
const router = express.Router();
const PagoController = require('../controllers/pagoscontroller');

router.get('/', PagoController.getAll);
router.post('/', PagoController.create);
router.put('/:idPagos', PagoController.updateStatus);
router.delete('/:id', PagoController.delete);

module.exports = router;