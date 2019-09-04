<?
require_once "./models/viewsModels.php";
class viewsControllersEnlaces extends viewsEnlaceModels {
    
    public function viewstemplate(){

		return require_once "./views/template.php";

	}
    

	public function viewsController(){

		/*if(isset($_GET["action"])){

			$enlaces = $_GET["action"];

		}

		else{

			$enlaces = "index";

		}

		$respuesta = viewsEnlaceModels::obtenerViewsenlacesModel($enlaces);

		include $respuesta;*/

        if (isset($_GET['action'])) {
            $ruta = explode("/", $_GET['action']);
            $respuesta = viewsEnlaceModels::obtenerViewsenlacesModel($ruta[0]);
        } else {
            $respuesta = "login";
        }
        return $respuesta;

	}


}

