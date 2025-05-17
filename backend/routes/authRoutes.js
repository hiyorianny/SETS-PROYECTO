const express = require('express');
const router = express.Router();
const AuthController = require('../controllers/AuthController');

router.post('/register', AuthController.register);
router.post('/login', AuthController.login);
router.get('/roles', AuthController.getRoles);
router.get('/user/:userId', AuthController.getUserProfile);
router.put('/user/:userId', AuthController.updateUserProfile); // Nueva ruta
router.post('/upload-profile-image', AuthController.uploadProfileImage);


module.exports = router;