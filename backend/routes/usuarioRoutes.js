const express = require('express');
const router = express.Router();
const UsuarioController = require('../controllers/UsuarioController');

router.get('/', UsuarioController.getAll);
router.delete('/:id', UsuarioController.delete);

module.exports = router;