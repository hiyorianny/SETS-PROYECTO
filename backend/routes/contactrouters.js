const express = require('express');
const router = express.Router();
const ContactoController = require('../controllers/contactotoller');

router.get('/', ContactoController.getAll);
router.delete('/:id', ContactoController.delete);

module.exports = router;