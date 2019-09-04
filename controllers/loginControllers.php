<?
require_once "./models/loginModels.php";

class loginControllersC extends loginModelsM{

    public function iniciar_session_controller(){

        if (isset($_POST["usuarioIngreso"])) {

            if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["usuarioIngreso"]) && preg_match('/^[a-zA-Z0-9]+$/', $_POST["passwordIngreso"])) {
                /*--------------------------------------*/

                $datosController = array("loginUsuar" => $_POST["usuarioIngreso"], "loginPass" => $_POST["passwordIngreso"]);

                $respuesta = loginModelsM::ingresoModelss($datosController,"usuario");

                /*-------------------------------------*/
                if ($respuesta["dni_usuario"] == $_POST["usuarioIngreso"] && $respuesta["contr_usuario"] == $_POST["passwordIngreso"]) {
                    //$row = $respuesta->fetch();
                    session_start(["name"=>"SMA"]);

                    $_SESSION["validar"] = true;
                    $_SESSION["nombres"] = $respuesta["nombre_usuario"];

                    $_SESSION["nombre"] = $respuesta["nombre_usuario"]." ".$respuesta["apellido_usuario"];
                   // header("location:inicio/");
                    header("location:inicio/");
                }
                else {

                    
                    echo '<div class="alert alert-danger">Error al ingresar</div>';

                }

            }
        }

    }

    /*=== Force Close Session Controller ====*/
    public function force_close_session_controller(){
        session_start(['name'=>'SMA']);
        session_unset();
        session_destroy();
        return header("Location: ".SERVERURL."login/");
    }

}