<?
require_once "./core/configGeneral.php";
//require_once "models/viewsModels.php";
//require_once "models/loginModels.php";
//require_once "models/sliderModelsM.php";
//require_once "models/noticiaModelsM.php";
//require_once "models/adminListModelsM.php";


//require_once "./controllers/sliderControllersC.php";
require_once "./controllers/viewsControllers.php";
//require_once "controllers/loginControllers.php";

//require_once "controllers/sliderControllersC.php";
//require_once "controllers/noticiassControllersC.phphp";
//require_once "controllers/adminListControllersC.php";



$template = new viewsControllersEnlaces();
$template -> viewstemplate();

