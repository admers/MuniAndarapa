<?php
$AjaxRequest=true;
require_once "../core/configGeneral.php";

if( isset($_POST['idObrasPUP']) || isset($_POST['idObrasP-del'])) {

    require_once "../controllers/obrasPControllersC.php";

    $insAdmin = new obrasPControllerssC();
    echo $_POST['idObrasPUP'] . '<br>';
    //echo $_POST['imag-del'];


    if (isset($_POST['idObrasPUP'])){
        //echo $_POST['idObrasPUP'];
        echo $insAdmin->actualizar_ObrasPController();
    }
    if (isset($_POST['idObrasP-del'])){
        echo $insAdmin->deleteObrasPcontroller();
    }



}else{
    session_start(['name'=>'SMA']);
    session_unset();
    session_destroy();
    echo '<script> window.location.href="'.SERVERURL.'login/"; </script>';
}