<?
if($AjaxRequest){
    require_once "../core/mainModel.php";
}else{
    require_once "./core/mainModel.php";
}

class GestorsliderM extends mainModels {

    public function guardarSliderModel($datosModel){

            $stmt=mainModels::conectar()->prepare("INSERT INTO slider (titulo_slider,ruta_slider)
                                            VALUES (:tituloSlider,:rutaSlider)");
            $stmt->bindParam(":tituloSlider",$datosModel["stitulo"],PDO::PARAM_STR);
            $stmt->bindParam(":rutaSlider",$datosModel["sruta"],PDO::PARAM_STR);
            $stmt->execute();
            //$stmt->close();
            return $stmt;



    }
    #--------------ACTUALIZAR---------------------
    public function datos_Slider_modelo($codigo){

        $query=mainModels::conectar()->prepare("SELECT * FROM slider WHERE idslider=:Codigo");
        $query->bindParam(":Codigo",$codigo);
        $query->execute();
        return $query;
    }
    public function datos_SliderConteo_modelo($tipo){
        if($tipo=="Conteos") {
            $query = mainModels::conectar()->prepare("SELECT * FROM slider WHERE idslider!='1'");
        }
        $query->execute();
        return $query;
    }

    public function actualizarSliderrModel($datosModels){


        $stmt=mainModels::conectar()->prepare("UPDATE slider SET titulo_slider=:tit,ruta_slider=:ruta WHERE idslider=:id");
        $stmt->bindParam(":id",$datosModels["ID"],PDO::PARAM_INT);
        $stmt->bindParam(":tit",$datosModels["TITULO"],PDO::PARAM_STR);
        $stmt->bindParam(":ruta",$datosModels["IMAGEN"],PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;


    }
    #BORRAR Noticia
    #-----------------------------------------------------
    public function borrarSliderModel($dat){

        $stmt = mainModels::conectar()->prepare("DELETE FROM slider WHERE idslider = :id");
        $stmt->bindParam(":id", $dat, PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}

		else{

			return "error";

        }
       // $stmt->execute();

       // return $stmt;

		$stmt->close();

    }
    public function deleteSlidermodel($code){
        $query=mainModels::conectar()->prepare("DELETE FROM slider WHERE idslider=:id");
        $query->bindParam(":id",$code);
        $query->execute();
        return $query;
    }

}

