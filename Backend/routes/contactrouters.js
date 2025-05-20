const express = require('express');
const router = express.Router();
const ContactoController = require('../controllers/contactotoller');

router.get('/', ContactoController.getAll);
router.delete('/:id', ContactoController.delete);
router.post('/', ContactoController.create); 
module.exports = router;