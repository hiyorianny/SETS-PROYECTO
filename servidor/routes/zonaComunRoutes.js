const express = require('express');
const router = express.Router();
const ZonaComunController = require('../controllers/ZonaComunController');

router.get('/', ZonaComunController.getAll);

module.exports = router;