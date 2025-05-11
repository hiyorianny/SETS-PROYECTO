<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;
use App\Models\CiclistaModel;

abstract class DatabaseTest extends TestCase
{
    protected static $base_de_datos;
    protected $model;

    public static function setUpBeforeClass(): void
    {
        try {
            echo "Intentando conectar a la base de datos...\n";
            self::$base_de_datos = new PDO(
                'mysql:host=localhost;dbname=equipo_ciclistas_test',
                'root',
                '',
                [
                    PDO::ATTR_TIMEOUT => 5,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
            echo "Conexión exitosa\n";


            self::$base_de_datos->exec("CREATE TABLE IF NOT EXISTS ciclistas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(100) NOT NULL,
                apellido VARCHAR(100) NOT NULL,
                edad INT NOT NULL,
                pais VARCHAR(50) NOT NULL,
                especialidad VARCHAR(100) NOT NULL,
                salario DECIMAL(10,2) NOT NULL,
                peso DECIMAL(5,2) NOT NULL,
                altura DECIMAL(5,2) NOT NULL,
                potencia_maxima INT,
                vo2_max DECIMAL(5,2),
                fecha_contrato DATE NOT NULL,
                equipo_anterior VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    protected function setUp(): void
    {
        self::$base_de_datos->exec("TRUNCATE TABLE ciclistas");
        self::$base_de_datos->exec("INSERT INTO ciclistas 
            (nombre, apellido, edad, pais, especialidad, salario, peso, altura, fecha_contrato) 
            VALUES 
            ('Test', 'Ciclista', 25, 'Testlandia', 'Escalador', 100000, 65.5, 1.75, '2023-01-01'),
            ('Otro', 'Ciclista', 28, 'Testlandia', 'Sprinter', 150000, 70.0, 1.80, '2023-01-01')");
        
        $this->model = new CiclistaModel(self::$base_de_datos);
    }

    public static function tearDownAfterClass(): void
    {
        self::$base_de_datos->exec("DROP TABLE IF EXISTS ciclistas");
        self::$base_de_datos = null;
    }
}
