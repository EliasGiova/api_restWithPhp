<?php

$host = "localhost";
$user = "root";
$pass = "root";
$bd = "api_web";

$conexion = new mysqli($host, $user, $pass, $bd);

if ($conexion->connect_error) {
    die("Conexion no establecida: " . $conexion->connect_error);
}

header("Content-Type: application/json"); //si o si, para que devuelva un archivo json

$metodo = $_SERVER["REQUEST_METHOD"]; //recibimos el metodo que querramos (put, get, post, etc.)

$path= isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] :'/'; //

$buscarId = explode('/',$path);

$id=($path!=='/') ? end($buscarId):null;

switch ($metodo) {
        //SELECT
    case 'GET':
        consultar($conexion, $id);
        break;
        //INSERT
    case 'POST':
        insertar($conexion);
        break;
        //UPDATE
    case 'PUT':
        modificar($conexion, $id);
        break;
        //DELETE
    case 'DELETE':
        borrar($conexion, $id);
        break;
    default:
        echo "Metodo no permitido";
        break;
}

function consultar($conexion, $id){
    $sql = ($id===null) ? "SELECT * From usuarios" : "SELECT * FROM usuarios WHERE id=$id";
    $resultado= $conexion->query($sql);
    
    if($resultado){
        $datos= array();
        while($fila= $resultado->fetch_assoc()){
        $datos[]=$fila;
        }
        echo json_encode($datos);
    }
}

function insertar($conexion){
    $dato = json_decode(file_get_contents('php://input'), true);
    $nombre = $dato['nombre'];

    $sql = "INSERT INTO usuarios(nombre) VALUES ('$nombre')";
    $resultado= $conexion->query($sql);
    if($resultado){
        $dato['id'] = $conexion->insert_id;
        echo json_encode($dato);

    }else{
        echo json_encode(array('error' => 'Error al crear usuarios'));
    }
}

function borrar($conexion, $id){
    $sql = "DELETE FROM usuarios WHERE id=$id";
    $resultado= $conexion->query($sql);
    if($resultado){   
        echo json_encode(array('mensaje' => 'Usuario borrado'));
    }else{
        echo json_encode(array('error' => 'Error al eliminar el usuario'));
    }
}

function modificar($conexion, $id){
    $dato = json_decode(file_get_contents('php://input'), true);
    $nombre = $dato['nombre'];

    $sql = "UPDATE usuarios SET nombre = '$nombre' WHERE id=$id";
    $resultado= $conexion->query($sql);
    if($resultado){   
        echo json_encode(array('mensaje' => 'Usuario Actualizado'));
    }else{
        echo json_encode(array('error' => 'Error al actualizar usuario'));
    }
}


