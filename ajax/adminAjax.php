<?php
$AjaxRequest=true;
require_once "../core/configGeneral.php";

if(isset($_POST['idNotDel']) || isset($_POST['idNoticiaUP']) || isset($_POST['id-del']) || isset($_POST['idoup']) || isset($_POST['idObrasPUP']) || isset($_POST['idObrasP-del'])
    || isset($_POST['idGober-del']) || isset($_POST['idGoberUp']) || isset($_POST['idMVUP'])  || isset($_POST['idpdUP'])
    || isset($_POST['idpdUN']) || isset($_POST['idO-del']) ) {

    require_once "../controllers/adminListControllersC.php";

    $insAdmin = new adminListControllerssC();
/*-----------------NOTICIAS--------------------------------*/
    if (isset($_POST['idNotDel'])){
        echo $insAdmin->deleteNoticiacontroller();
    }
    if (isset($_POST['idNoticiaUP'])){
        echo $insAdmin->actualizar_NoticiaController();
    }
/*-----------------Organigrama---------------------*/
    if (isset($_POST['id-del'])){
        echo $insAdmin->deleteOrganigramacontroller();
    }
    if (isset($_POST['idoup'])){
        echo $insAdmin->actualizarOrganigramaController();
    }
    /*------------Obras y Proyectos------------------*/
    if (isset($_POST['idObrasPUP'])){
        //echo $_POST['idObrasPUP'];
        echo $insAdmin->actualizar_ObrasPController();
    }
    if (isset($_POST['idObrasP-del'])){
        echo $insAdmin->deleteObrasPcontroller();
    }
    /*----------gobernante--------<<*/
    if (isset($_POST['idGoberUp'])){
        echo $insAdmin->actualizarGobernanteController();
    }
    if (isset($_POST['idGober-del'])){
        echo $insAdmin->deleteGobernantecontroller();
    }
    /*--------------Mision y Vision----------*/
    if (isset($_POST['idMVUP'])){
        echo $insAdmin->actualizarMVController();
    }
    /*---------------Plan de Desarrollo-----------------*/
    if (isset($_POST['idpdUP'])){
        echo $insAdmin->actualizarPlanDController();
    }
    /*--------------Novedades--------------------*/
    if (isset($_POST['idpdUN'])){
        echo $insAdmin->actualizarNovedadesController();
    }
    if (isset($_POST['idO-del'])){
        echo $insAdmin->deletenNovedadescontroller();
    }


}else{
    session_start(['name'=>'SMA']);
    session_unset();
    session_destroy();
    echo '<script> window.location.href="'.SERVERURL.'login/"; </script>';
}