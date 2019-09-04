<!DOCTYPE html>
<html lang="es">
<head>
    <title>Inicio</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?php echo SERVERURL; ?>views/css/main.css">

    <script src="<?php echo SERVERURL; ?>views/js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo SERVERURL; ?>views/js/sweetalert2.min.js"></script>
    <script src="<?php echo SERVERURL; ?>views/js/bootstrap.min.js"></script>

    <script src="<?php echo SERVERURL; ?>views/js/jquery.dataTables.min.js"></script>

    <script src="<?php echo SERVERURL; ?>views/js/material.min.js"></script>
    <script src="<?php echo SERVERURL; ?>views/js/ripples.min.js"></script>
    <script src="<?php echo SERVERURL; ?>views/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="<?php echo SERVERURL; ?>views/js/main.js"></script>


</head>
<body>
 <?

 //$modulos = new viewsControllersEnlaces();
 //$modulos -> viewsController();
    

?>
 <?
 $AjaxRequest=false;


 require_once "./controllers/viewsControllers.php";
 $vt = new viewsControllersEnlaces();
 $viewsR=$vt->viewsController();
 if ($viewsR=="login"){
     require_once "./views/contenido/$viewsR.php";
 }else{
     session_start(["name"=>"SMA"]);

     /*----------  Check Access  ----------*/
     require_once "./controllers/loginControllers.php";
     $sc = new loginControllersC();
     if(!isset($_SESSION['nombres'])){
         echo $sc->force_close_session_controller();
     }


     ?>

     <?

     /* <!-- SideBar -->*/

     include "views/modules/navLateral.php";
     ?>

     <!-- Content page-->
     <section class="full-box dashboard-contentPage">
         <!-- NavBar -->
         <?
         include "views/modules/navTop.php";


         require_once $viewsR;
         ?>
         <!-- Content page -->

     </section>

 <?
     include "./views/modules/logoutScript.php";

 }?>



<!--====== Scripts -->
 <script src="<?php echo SERVERURL; ?>views/js/gestorNoticia.js"></script>
 <script src="<?php echo SERVERURL; ?>views/js/gestorSlider.js"></script>
 <script src="<?php echo SERVERURL; ?>views/js/gestorObrasP.js"></script>
<!--<script src="./views/js/jquery-3.1.1.min.js"></script>
<script src="./views/js/sweetalert2.min.js"></script>
<script src="./views/js/bootstrap.min.js"></script>
<script src="./views/js/material.min.js"></script>
<script src="./views/js/ripples.min.js"></script>
<script src="./views/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="./views/js/main.js"></script>
-->
<script>
    $.material.init();
</script>
</body>
</html>