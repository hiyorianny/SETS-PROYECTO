const express = require('express');
const router = express.Router();
const IngresoController = require('../controllers/ingresoscontroller');

router.post('/', IngresoController.create);
router.get('/', IngresoController.getAll);
router.delete('/:id', IngresoController.delete);

module.exports = router;
