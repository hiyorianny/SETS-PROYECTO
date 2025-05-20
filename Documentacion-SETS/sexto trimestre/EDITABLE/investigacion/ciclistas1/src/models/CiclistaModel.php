<?php
namespace App\Models;

class CiclistaModel {
    private $base_de_datos;

    public function __construct($base_de_datos) {
        $this->base_de_datos = $base_de_datos;
    }

    public function getAll($especialidad = null) {
        $where = "";
        $params = [];
        
        if ($especialidad) {
            $where = " WHERE especialidad = :especialidad";
            $params[':especialidad'] = $especialidad;
        }
        
        $stmt = $this->base_de_datos->prepare("SELECT * FROM ciclistas" . $where);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->base_de_datos->prepare("SELECT * FROM ciclistas WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO ciclistas (
                nombre, apellido, edad, pais, especialidad, 
                salario, peso, altura, potencia_maxima, vo2_max, 
                fecha_contrato, equipo_anterior
                ) VALUES (
                :nombre, :apellido, :edad, :pais, :especialidad, 
                :salario, :peso, :altura, :potencia_maxima, :vo2_max, 
                :fecha_contrato, :equipo_anterior
                )";
                
        $stmt = $this->base_de_datos->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $data[':id'] = $id;
        $sql = "UPDATE ciclistas SET 
                nombre = :nombre,
                apellido = :apellido,
                edad = :edad,
                pais = :pais,
                especialidad = :especialidad,
                salario = :salario,
                peso = :peso,
                altura = :altura,
                potencia_maxima = :potencia_maxima,
                vo2_max = :vo2_max,
                fecha_contrato = :fecha_contrato,
                equipo_anterior = :equipo_anterior
                WHERE id = :id";
                
        $stmt = $this->base_de_datos->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->base_de_datos->prepare("DELETE FROM ciclistas WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getTotalPresupuesto() {
        $stmt = $this->base_de_datos->query("SELECT SUM(salario) as total FROM ciclistas");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (float) ($result['total'] ?? 0);
    }
}