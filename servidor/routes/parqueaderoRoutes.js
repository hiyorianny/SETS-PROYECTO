const express = require('express');
const router = express.Router();
const ParqueaderoController = require('../controllers/parkingcontroller');

router.get('/', ParqueaderoController.getAll);

module.exports = router;