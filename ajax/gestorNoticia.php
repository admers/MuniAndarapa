<?php

$AjaxRequest=true;
require_once "../core/configGeneral.php";

class Ajax{
    public $imagenTemporal;
    public function gestorNoticiaAjax(){
        $datos=$this->imagenTemporal;
        require_once "../controllers/adminListControllersC.php";


        //echo $datos;
        // $respuesta= GestorSlidersC::mostrarImagenController($datos);
        $respuesta= adminListControllerssC::mostrarImagenNoticiaController($datos);
        echo $respuesta;
    }
}

#----------objetos-----------
if (isset($_FILES["imagen"]["tmp_name"])){
    $a=new Ajax();
    $a->imagenTemporal=$_FILES["imagen"]["tmp_name"];
    $a->gestorNoticiaAjax();

}


if(isset($_POST['idNoticia-del'])  ) {

    require_once "../controllers/adminListControllersC.php";

    $insAdmin = new adminListControllerssC();
    echo $_POST['idNoticia-del'].'<br>';
   // echo $_POST['idSliderUp'];

    if (isset($_POST['idNoticia-del'])){
        echo $insAdmin->deleteNoticiaController();
    }




}
else{
    session_start(['name'=>'SMA']);
    session_unset();
    session_destroy();
    echo '<script> window.location.href="'.SERVERURL.'login/"; </script>';
}