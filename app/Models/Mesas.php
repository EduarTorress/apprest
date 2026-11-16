<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDOException;
use PDO;

class Mesas extends Modelo
{
    var $txtnombre = "";
    var $cmbalmacen = "";

    function listar(string $codt)
    {
        try {
            $sql = 'select * from fe_mesas where mesa_acti="A" AND mesa_codt=:codt';
            $query = $this->prepare($sql);
            $query->execute([
                'codt' => $codt
            ]);
            $lista = $query->fetchAll(PDO::FETCH_ASSOC);
            return $lista;
        } catch (PDOException $e) {
            echo 'Hubo un error' . $e;
        }
    }
    function buscarid($id)
    {
        $sql = "SELECT * FROM fe_mesas WHERE idmesa=:id AND mesa_acti='A'";
        $exec = $this->prepare($sql);
        $exec->execute(["id" => $id]);
        $resultado = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $resultado[0];
    }
    function save()
    {
        $sql = " INSERT INTO fe_mesas (mesa_codt,mesa_refe) VALUES (:cmbalmacen,:txtnombre)";
        $query = $this->prepare($sql);
        $query->execute([
            'txtnombre' => $this->txtnombre,
            'cmbalmacen' => $this->cmbalmacen
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
    function update($id)
    {
        $sql = "UPDATE fe_mesas SET mesa_refe=:txtnombre WHERE idmesa=:idmesa ";
        $query = $this->prepare($sql);
        $query->execute([
            'txtnombre' => $this->txtnombre,
            'idmesa' => $id
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
}
