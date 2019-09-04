<?
class viewsEnlaceModels{

	public function obtenerViewsenlacesModel($views){

        $listBlack= ["inicio","login","gobernante","misionVision","noticias","obrasProyectos",
            "organigrama","novedades","planDesarrollo","slider","usuario","salir","upNoticias",
            "upOrganigrama","upSlider","upObrasP","upNoticia","upNovedades","upGobernante","upMision","upplanDesarrollo"];

        if (in_array($views,$listBlack)){
            if (is_file("./views/contenido/".$views.".php")){
                $contenido="./views/contenido/".$views.".php";
            }
            else{
                $contenido="login";
            }
        }elseif ($views=="login"){
            $contenido="login";
        }elseif ($views=="index"){
            $contenido="login";
        }else{
            $contenido="login";
        }

        return $contenido;

	}


}
