<?php
$AjaxRequest=true;
require_once "../core/configGeneral.php";




class Ajax{
    public $imagenTemporal;
    public function gestorSliderAjax(){
        $datos=$this->imagenTemporal;
        require_once "../controllers/sliderControllersC.php";


        //echo $datos;
       // $respuesta= GestorSlidersC::mostrarImagenController($datos);
        $respuesta= GestorSlidersC::mostrarImagenController($datos);
        echo $respuesta;
    }
}

#----------objetos-----------
if (isset($_FILES["imagen"]["tmp_name"])){
    $a=new Ajax();
    $a->imagenTemporal=$_FILES["imagen"]["tmp_name"];
    $a->gestorSliderAjax();

}


/*---------------------------------------------------------------------*/

if(isset($_POST['idslider-del']) || isset($_POST['idSliderUp']) ) {

    require_once "../controllers/sliderControllersC.php";

    $insAdmin = new GestorSlidersC();
    echo $_POST['idslider-del'] . '<br>';
    echo $_POST['idSliderUp'];

    if (isset($_POST['idslider-del'])){
        echo $insAdmin->deleteSliderController();
    }
    if (isset($_POST['idSliderUp'])){
        $imagen=$_FILES["imgSliderup"]["tmp_name"];
        $name=$_FILES["imgSliderup"]["name"];
        echo $insAdmin->actualizarSliderController();



        //echo  $ruta;
    }



}else{
    session_start(['name'=>'SMA']);
    session_unset();
    session_destroy();
    echo '<script> window.location.href="'.SERVERURL.'login/"; </script>';
}