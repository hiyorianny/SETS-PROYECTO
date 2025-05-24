<?php
require __DIR__ . '/authMiddleware.php';

function getAuthenticatedUser() {
    $decoded = authenticate();
    
    return [
        'id' => $decoded->id ?? $decoded->id_Registro,
        'role' => $decoded->idRol,
        'username' => $decoded->Usuario
    ];
}