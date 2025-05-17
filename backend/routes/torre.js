const express = require('express');
const router = express.Router();
const TorreController = require('../controllers/torrecontroller');

router.get('/torres', TorreController.getAll);

module.exports = router;
