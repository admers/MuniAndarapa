<?
require "conexion.php";

class loginModelsM extends  Conexion {

    public function ingresoModelss($datosModel,$tabla){
        $stmt = Conexion::conectar()->prepare("SELECT dni_usuario, contr_usuario,nombre_usuario,apellido_usuario FROM $tabla
                                              WHERE dni_usuario = :usuari AND contr_usuario=:pass");
        $stmt->bindParam(":usuari", $datosModel["loginUsuar"], PDO::PARAM_STR);
        $stmt->bindParam(":pass", $datosModel["loginPass"], PDO::PARAM_STR);
        $stmt->execute();
        /*-------------*/
        return $stmt->fetch();
        $stmt->close();

        //return $stmt;


    }


}