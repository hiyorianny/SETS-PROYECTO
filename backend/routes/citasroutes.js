const express = require('express');
const router = express.Router();
const CitaController = require('../controllers/citacontroler');

router.get('/', CitaController.getAll);
router.post('/solicitud', CitaController.create);
router.post('/responder', CitaController.responder);
router.delete('/:id', CitaController.delete);

module.exports = router;