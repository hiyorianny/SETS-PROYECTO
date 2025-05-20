const express = require('express');
const router = express.Router();
const AnuncioController = require('../controllers/controleranuncios');

router.get('/anuncios', AnuncioController.getAll);
router.post('/anunciossubir', AnuncioController.create);
router.delete('/elanuncios/:id', AnuncioController.delete);

module.exports = router;
