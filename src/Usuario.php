<?php

namespace Src;

use \PDO;
use \PDOException;

class Usuario extends Conexion
{
    private int $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private  string $is_admin;
    private float $sueldo;

    public function __construct()
    {
        parent::__construct();
    }
    //------------------------------------CRUD-----------------------------------------------
    public static function read()
    {
        parent::crearConexion();
        $q = "select * from usuarios order by id desc";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en read" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function create()
    {
        parent::crearConexion();
        $q = "insert into usuarios(nombre, apellidos, email, is_admin, sueldo)
        values (:n,:a,:e,:i,:s)";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':a' => $this->apellidos,
                ':e' => $this->email,
                ':i' => $this->is_admin,
                ':s' => $this->sueldo,
            ]);
        } catch (PDOException $ex) {
            die("Error en create" . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    public static function delete($id)
    {
        parent::crearConexion();
        $q = "delete from usuarios where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':i' => $id]);
        } catch (PDOException $ex) {
            die("Error en delete" . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    public static function readUsuario($id)
    {
        parent::crearConexion();
        $q = "select * from usuarios where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([":i" => $id]);
        } catch (PDOException $ex) {
            die("Error en read" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function update($id)
    {
        $q = "update usuarios set nombre=:n, apellidos=:a, email=:e, is_admin=:is, sueldo=:s where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':a' => $this->apellidos,
                ':e' => $this->email,
                ':is' => $this->is_admin,
                ':s' => $this->sueldo,
                ':i' => $id
            ]);
        } catch (PDOException $ex) {
            die("Error en update" . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    //------------------------------------OTROS METODOS--------------------------------------
    public static function existeEmail(string $email, int $id=null): bool
    {
        parent::crearConexion();
        $q = ($id == null) ? "select id from usuarios where email=:e" :
            "select id from usuarios where email=:e AND id!=:i";
        $stmt = parent::$conexion->prepare($q);
        $opciones = ($id == null) ? [':e' => $email] : [':e' => $email, ':i' => $id];
        try {
            $stmt->execute($opciones);
        } catch (PDOException $ex) {
            die("Error en existeEmail" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount();
    }
    //------------------------------------SETTERS--------------------------------------------

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of apellidos
     *
     * @return  self
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of is_admin
     *
     * @return  self
     */
    public function setIs_admin($is_admin)
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    /**
     * Set the value of sueldo
     *
     * @return  self
     */
    public function setSueldo($sueldo)
    {
        $this->sueldo = $sueldo;

        return $this;
    }
}
