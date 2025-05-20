<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\CiclistaModel;

class CiclistaModelTest extends DatabaseTest
{
    public function testGetAll()
    {
        $result = $this->model->getAll();
        $this->assertCount(2, $result);
        $this->assertEquals('Test', $result[0]['nombre']);
    }

    public function testGetByEspecialidad()
    {
        $result = $this->model->getAll('Escalador');
        $this->assertCount(1, $result);
        $this->assertEquals('Escalador', $result[0]['especialidad']);
    }

    public function testGetById()
    {
        $id = self::$base_de_datos->query("SELECT id FROM ciclistas LIMIT 1")->fetchColumn();
        $result = $this->model->getById($id);
        $this->assertEquals('Test', $result['nombre']);
    }

    public function testCreate()
    {
        $newCiclista = [
            ':nombre' => 'Nuevo',
            ':apellido' => 'Ciclista',
            ':edad' => 30,
            ':pais' => 'Testlandia',
            ':especialidad' => 'Contrarrelojista',
            ':salario' => 200000,
            ':peso' => 68.0,
            ':altura' => 1.78,
            ':potencia_maxima' => 400,
            ':vo2_max' => 80.0,
            ':fecha_contrato' => '2023-01-01',
            ':equipo_anterior' => 'Test Team'
        ];
        
        $result = $this->model->create($newCiclista);
        $this->assertTrue($result);
        
        $count = self::$base_de_datos->query("SELECT COUNT(*) FROM ciclistas")->fetchColumn();
        $this->assertEquals(3, $count);
    }

    public function testUpdate()
    {
        $id = self::$base_de_datos->query("SELECT id FROM ciclistas WHERE nombre = 'Test'")->fetchColumn();
        
        $updateData = [
            ':nombre' => 'Test Actualizado',
            ':apellido' => 'Ciclista',
            ':edad' => 26,
            ':pais' => 'Testlandia',
            ':especialidad' => 'Escalador',
            ':salario' => 110000,
            ':peso' => 66.0,
            ':altura' => 1.75,
            ':potencia_maxima' => 420,
            ':vo2_max' => 82.0,
            ':fecha_contrato' => '2023-01-01',
            ':equipo_anterior' => 'Test Team'
        ];
        
        $result = $this->model->update($id, $updateData);
        $this->assertTrue($result);
        
        $updated = $this->model->getById($id);
        $this->assertEquals('Test Actualizado', $updated['nombre']);
    }

    public function testDelete()
    {
        $id = self::$base_de_datos->query("SELECT id FROM ciclistas WHERE nombre = 'Test'")->fetchColumn();
        $result = $this->model->delete($id);
        $this->assertTrue($result);
        
        $count = self::$base_de_datos->query("SELECT COUNT(*) FROM ciclistas")->fetchColumn();
        $this->assertEquals(1, $count);
    }

    public function testGetTotalPresupuesto()
    {
        $total = $this->model->getTotalPresupuesto();
        $this->assertEquals(250000, $total);
    }
}