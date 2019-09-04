<?php
if($AjaxRequest){
    require_once "../core/mainModel.php";
}else{
    require_once "./core/mainModel.php";
}
//require_once "conexion.php";

class adminListModelssM extends mainModels{

/*--------------------NOTICIAS-----------------------------*/

    public function guardarNoticiaModel($datosModel){

        $stmt=mainModels::conectar()->prepare("INSERT INTO noticias (titulo_noti,introduccion_noti,ruta,contenido,fecha_publi)
                                            VALUES (:titulo,:introduccion,:ruta,:contenido ,:fechaP)");
        $stmt->bindParam(":titulo",$datosModel["atitulo"],PDO::PARAM_STR);
        $stmt->bindParam(":introduccion",$datosModel["aintroduccion"],PDO::PARAM_STR);
        $stmt->bindParam(":ruta",$datosModel["aruta"],PDO::PARAM_STR);
        $stmt->bindParam(":contenido",$datosModel["acontenido"],PDO::PARAM_STR);
        $stmt->bindParam(":fechaP",$datosModel["dato"],PDO::PARAM_STR);
        $stmt->execute();
        //$stmt->close();
        return $stmt;

    }
    public function datos_Noticia_modelo($codigo){

        $query=mainModels::conectar()->prepare("SELECT * FROM noticias WHERE id_noticias=:Codigo");
        $query->bindParam(":Codigo",$codigo);
        $query->execute();
        return $query;

    }
    public function datos_NoticiaConteo_modelo($tipo){
        if($tipo=="Conteos") {
            $query = mainModels::conectar()->prepare("SELECT * FROM noticias WHERE id_noticias!='1'");
        }
        $query->execute();
        return $query;
    }
    public function deleteNoticiamodel($code){
        $query=mainModels::conectar()->prepare("DELETE FROM noticias WHERE id_noticias=:id");
        $query->bindParam(":id",$code);
        $query->execute();
        return $query;
    }
    public function actualizar_NoticiaModel($datosModels){

        $stmt=mainModels::conectar()->prepare("UPDATE noticias SET titulo_noti=:tit,introduccion_noti=:introd,ruta=:ruta ,contenido=:contenido,fecha_publi=:fecha WHERE id_noticias=:id");
        $stmt->bindParam(":id",$datosModels["ID"],PDO::PARAM_INT);
        $stmt->bindParam(":tit",$datosModels["TITULO"],PDO::PARAM_STR);
        $stmt->bindParam(":introd",$datosModels["INTRODUCCION"],PDO::PARAM_STR);
        $stmt->bindParam(":ruta",$datosModels["IMAGEN"],PDO::PARAM_STR);
        $stmt->bindParam(":contenido",$datosModels["CONTENIDO"],PDO::PARAM_STR);
        $stmt->bindParam(":fecha",$datosModels["FECHA"],PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;


    }
/*    public function mostrarNoticiaModel($tabla){
        $stmt=mainModels::conectar()->prepare("SELECT id_noticias,titulo_noti,introduccion_noti,ruta,contenido
         FROM $tabla ORDER BY id_noticias DESC ");
        $stmt->execute();
        return $stmt->fetchAll();
        $stmt->close();

    }*/
    /*------------------------OBRAS Y PROYECTOS------------------------*/
    public function guardarObrasProyectosModels($datosModel){
        $stmt=mainModels::conectar()->prepare("INSERT INTO obras_proy (titulo_op,codigo_op,ruta_op,contenido_op,estado)
                                            VALUES (:tituloOP,codigoOP,:rutaOP,:contenidoOP,:estadoOP)");
        $stmt->bindParam(":tituloOP",$datosModel["OPtitulo"],PDO::PARAM_STR);
        $stmt->bindParam(":codigoOP",$datosModel["OPcodigo"],PDO::PARAM_STR);
        $stmt->bindParam(":rutaOP",$datosModel["OPruta"],PDO::PARAM_STR);
        $stmt->bindParam(":contenidoOP",$datosModel["OPcontenido"],PDO::PARAM_STR);
        $stmt->bindParam(":estadoOP",$datosModel["OPcontenido"],PDO::PARAM_STR);
        $stmt->execute();
        //$stmt->close();
        return $stmt;

    }
    public function guardarOPModel($datosModel){
        $stmt=mainModels::conectar()->prepare("INSERT INTO obras_proy (titulo_op,codigo_op,ruta_op,contenido_op,estado)
                                            VALUES (:titulo,:codigo,:ruta,:contenido,:estado)");
        $stmt->bindParam(":titulo",$datosModel["optitulo"],PDO::PARAM_STR);
        $stmt->bindParam(":codigo",$datosModel["opcodigo"],PDO::PARAM_STR);
        $stmt->bindParam(":ruta",$datosModel["opruta"],PDO::PARAM_STR);
        $stmt->bindParam(":contenido",$datosModel["opcontenido"],PDO::PARAM_STR);
        $stmt->bindParam(":estado",$datosModel["opestado"],PDO::PARAM_STR);
        $stmt->execute();
        //$stmt->close();
        return $stmt;

    }

    #--------------ACTUALIZAR---------------------
    public function datos_ObrasP_modelo($codigo){

        $query=mainModels::conectar()->prepare("SELECT * FROM obras_proy WHERE id_op=:Codigo");
        $query->bindParam(":Codigo",$codigo);
        $query->execute();
        return $query;

    }
    public function datos_ObrasPConteo_modelo($tipo){

        if($tipo=="Conteo") {
            $query = mainModels::conectar()->prepare("SELECT * FROM obras_proy WHERE id_op!='1'");
        }
        $query->execute();
        return $query;

    }
    public function actualizar_ObrasPModel($datosModels){

        $stmt=mainModels::conectar()->prepare("UPDATE obras_proy SET titulo_op=:tit,codigo_op=:codOP,ruta_op=:ruta ,contenido_op=:cont_op,estado=:estado WHERE id_op=:id");
        $stmt->bindParam(":id",$datosModels["ID"],PDO::PARAM_INT);
        $stmt->bindParam(":tit",$datosModels["TITULO"],PDO::PARAM_STR);
        $stmt->bindParam(":codOP",$datosModels["CODIGO"],PDO::PARAM_STR);
        $stmt->bindParam(":ruta",$datosModels["IMAGEN"],PDO::PARAM_STR);
        $stmt->bindParam(":cont_op",$datosModels["CONTENIDO"],PDO::PARAM_STR);
        $stmt->bindParam(":estado",$datosModels["ESTADO"],PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;


    }
    public function deleteObrasPmodel($code){
        $query=mainModels::conectar()->prepare("DELETE FROM obras_proy WHERE id_op=:Code");
        $query->bindParam(":Code",$code);
        $query->execute();
        return $query;
    }


/*    public function actualizarObrasPModel($idop,$titulo,$codigo,$rutaimage,$contenido,$estado){

        $stmt=mainModels::conectar()->prepare("UPDATE obras_proy SET titulo_op='$titulo',codigo_op='$codigo',ruta_op='$rutaimage',contenido_op='$contenido',estado='$estado' WHERE id_op='$idop'");
         $stmt->bindParam(":id",$datosModels["id"],PDO::PARAM_INT);
         $stmt->bindParam(":tituloSlider",$datosModels["titulo"],PDO::PARAM_STR);
         $stmt->bindParam(":rutaSlider",$datosModels["rutaimage"],PDO::PARAM_STR);
        if( $stmt->execute()){
            return "ok";
        }
        else{
            return "error";
        }
        //$stmt->close();
        $stmt->close();


    }*/
    /*----------------------------------------GOBERNANTE--------------------------------------------------*/
    public function guardarGobernanteModel($datosModel){
        $stmt=mainModels::conectar()->prepare("INSERT INTO gobernante (dni_gobernante,nombre_gober,apellido_gober,cargo_gober,mensage,gestion,rutaGobernante)
                                            VALUES (:dni,:nombre,:apellido,:cargo,:mensaje,:gestion,:rutaImag)");
        $stmt->bindParam(":dni",$datosModel["dnig"],PDO::PARAM_STR);
        $stmt->bindParam(":nombre",$datosModel["nombreg"],PDO::PARAM_STR);
        $stmt->bindParam(":apellido",$datosModel["apellidog"],PDO::PARAM_STR);
        $stmt->bindParam(":cargo",$datosModel["cargog"],PDO::PARAM_STR);
        $stmt->bindParam(":mensaje",$datosModel["mensajeg"],PDO::PARAM_STR);
        $stmt->bindParam(":gestion",$datosModel["gestiong"],PDO::PARAM_STR);
        $stmt->bindParam(":rutaImag",$datosModel["rutag"],PDO::PARAM_STR);
        $stmt->execute();
        //$stmt->close();
        return $stmt;

    }
    public function datos_Gobernante_modelo($codigo){

        $query=mainModels::conectar()->prepare("SELECT * FROM gobernante WHERE idgobernante=:Codigo");
        $query->bindParam(":Codigo",$codigo);
        $query->execute();
        return $query;
    }
    public function datos_GobernanteConteo_modelo($tipo){

        if($tipo=="Conteos") {
            $query = mainModels::conectar()->prepare("SELECT * FROM gobernante WHERE idgobernante!='1'");
        }
        $query->execute();
        return $query;
    }
    public function actualizarGobernanteModel($datosModels){

        $stmt=mainModels::conectar()->prepare("UPDATE gobernante SET dni_gobernante=:dni,nombre_gober=:nombre,apellido_gober=:apellido,cargo_gober=:cargo,mensage=:mensaje,gestion=:gestion ,rutaGobernante=:ruta WHERE idgobernante=:id");
        $stmt->bindParam(":id",$datosModels["ID"],PDO::PARAM_INT);
        $stmt->bindParam(":dni",$datosModels["DNI"],PDO::PARAM_INT);
        $stmt->bindParam(":nombre",$datosModels["NOMBRE"],PDO::PARAM_STR);
        $stmt->bindParam(":apellido",$datosModels["APELLIDO"],PDO::PARAM_STR);
        $stmt->bindParam(":cargo",$datosModels["CARGO"],PDO::PARAM_STR);
        $stmt->bindParam(":mensaje",$datosModels["MENSAJE"],PDO::PARAM_STR);
        $stmt->bindParam(":gestion",$datosModels["GESTION"],PDO::PARAM_STR);
        $stmt->bindParam(":ruta",$datosModels["ruta"],PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;


    }
    public function deleteGobernantemodel($code){
        $query=mainModels::conectar()->prepare("DELETE FROM gobernante WHERE idgobernante=:Code");
        $query->bindParam(":Code",$code);
        $query->execute();
        return $query;
    }



    /*-----------------------------MISION Y VISION-----------------------------*/

    public function guardarMisionVision($datosModel){

        $stmt=mainModels::conectar()->prepare("INSERT INTO mision_vision (tipo_mv,contenido_mv)
                                            VALUES (:tipo_mv,:contenido_mv)");
        $stmt->bindParam(":tipo_mv",$datosModel["tipomv"],PDO::PARAM_STR);
        $stmt->bindParam(":contenido_mv",$datosModel["contenidomv"],PDO::PARAM_STR);

        $stmt->execute();
        //$stmt->close();
        return $stmt;

    }
    public function borrarMisionVisionModel($dat){

        $stmt = mainModels::conectar()->prepare("DELETE FROM mision_vision WHERE id_mv = :id");
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
    public function datos_MisionVision_modelo($codigo){

        $query=mainModels::conectar()->prepare("SELECT * FROM mision_vision WHERE id_mv=:Codigo");
        $query->bindParam(":Codigo",$codigo);
        $query->execute();
        return $query;
    }
    public function actualizarMVModel($datosModels){

        $stmt=mainModels::conectar()->prepare("UPDATE mision_vision SET tipo_mv=:tipo,contenido_mv=:contenido WHERE id_mv=:id");
        $stmt->bindParam(":id",$datosModels["ID"],PDO::PARAM_INT);
        $stmt->bindParam(":tipo",$datosModels["TIPO"],PDO::PARAM_STR);
        $stmt->bindParam(":contenido",$datosModels["CONTENIDO"],PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;


    }





    /*---------------------------------PLAN DE DESARROLLO---------------------------------------------*/
    public function guardarPlanDesarrollo($datosModel){

        $stmt=mainModels::conectar()->prepare("INSERT INTO plan_desarrollo (titulo_plan,contenido_plan,direc_plan)
                                            VALUES (:titulo_plan,:contenido_plan,:direc_plan)");
        $stmt->bindParam(":titulo_plan",$datosModel["pdtitulo"],PDO::PARAM_STR);
        $stmt->bindParam(":contenido_plan",$datosModel["pdcontenido"],PDO::PARAM_STR);
        $stmt->bindParam(":direc_plan",$datosModel["pdarchivo"],PDO::PARAM_STR);

        $stmt->execute();
        //$stmt->close();
        return $stmt;

    }
    public function borrarPlanDModel($dat){

        $stmt = mainModels::conectar()->prepare("DELETE FROM plan_desarrollo WHERE id_plan_desarrollo = :id");
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
    public function datos_PlanDmodelo($codigo){

        $query=mainModels::conectar()->prepare("SELECT * FROM plan_desarrollo WHERE id_plan_desarrollo=:Codigo");
        $query->bindParam(":Codigo",$codigo);
        $query->execute();
        return $query;
    }
    public function datos_PlanDVistamodelo($codigo){

        $query=mainModels::conectar()->prepare("SELECT * FROM plan_desarrollo WHERE id_plan_desarrollo=:Codigo");
        $query->bindParam(":Codigo",$codigo);
        $query->execute();
        return $query;
    }
    public function actualizarPlanDModel($datosModels){

        $stmt=mainModels::conectar()->prepare("UPDATE plan_desarrollo SET titulo_plan=:titupd,contenido_plan=:contpd,direc_plan=:direc WHERE id_plan_desarrollo=:id");
        $stmt->bindParam(":id",$datosModels["ID"],PDO::PARAM_INT);
        $stmt->bindParam(":titupd",$datosModels["TITULO"],PDO::PARAM_STR);
        $stmt->bindParam(":contpd",$datosModels["CONTENIDO"],PDO::PARAM_STR);
        $stmt->bindParam(":direc",$datosModels["RUTA"],PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;


    }

    /*...............................-----NOVEDADES-----------------------------------------------------------*/
    public function guardarNovedades($datosModel){

        $stmt=mainModels::conectar()->prepare("INSERT INTO novedades (titulo_nove,direc_nove)
                                            VALUES (:tituloNo,:direcNove)");
        $stmt->bindParam(":tituloNo",$datosModel["pdtitulo"],PDO::PARAM_STR);
        $stmt->bindParam(":direcNove",$datosModel["pdarchivo"],PDO::PARAM_STR);

        $stmt->execute();
        //$stmt->close();
        return $stmt;

    }
    public function datos_Novedadesmodelo($codigo){

        $query=mainModels::conectar()->prepare("SELECT * FROM novedades WHERE id_novedades=:Codigo");
        $query->bindParam(":Codigo",$codigo);
        $query->execute();
        return $query;
    }
    public function datos_NovedadesConteo_modelo($tipo){

        if($tipo=="Conteo") {
            $query = mainModels::conectar()->prepare("SELECT * FROM novedades WHERE id_novedades!='1'");
        }
        $query->execute();
        return $query;
    }
    public function actualizarNovedadesModel($datosModels){

        $stmt=mainModels::conectar()->prepare("UPDATE novedades SET titulo_nove=:titun,direc_nove=:direc WHERE id_novedades=:id");
        $stmt->bindParam(":id",$datosModels["ID"],PDO::PARAM_INT);
        $stmt->bindParam(":titun",$datosModels["TITULO"],PDO::PARAM_STR);
        $stmt->bindParam(":direc",$datosModels["RUTA"],PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;


    }

    public function deleteNovedadesmodel($code){
        $query=mainModels::conectar()->prepare("DELETE FROM novedades WHERE id_novedades=:Code");
        $query->bindParam(":Code",$code);
        $query->execute();
        return $query;
    }







    /*---------------------------------ORGANIGRAMA------------------------------*/

    public function guardarOrganigramaModels($datosModel){

        $stmt=$stmt=mainModels::conectar()->prepare("INSERT INTO organigrama (titulo_or,imagen_or)
                                            VALUES (:titulo_or,:imagen_or)");
        $stmt->bindParam(":titulo_or",$datosModel["otitulo"],PDO::PARAM_STR);
        $stmt->bindParam(":imagen_or",$datosModel["oimagen"],PDO::PARAM_STR);

        $stmt->execute();
        //$stmt->close();
        return $stmt;

    }

    public function datos_administrador_modelo($codigo){

        $query=mainModels::conectar()->prepare("SELECT * FROM organigrama WHERE id_organigrama=:Codigo");
        $query->bindParam(":Codigo",$codigo);
        $query->execute();
        return $query;
    }
    public function datos_OrganigramaConteo_modelo($tipo){

        if($tipo=="Conteo") {
            $query = mainModels::conectar()->prepare("SELECT * FROM organigrama WHERE id_organigrama!='1'");
        }
        $query->execute();
        return $query;

    }



    public function actualizarOrganigramaModel($datosModels){


        $stmt=mainModels::conectar()->prepare("UPDATE organigrama SET titulo_or=:tit,imagen_or=:ruta WHERE id_organigrama=:id");
        $stmt->bindParam(":id",$datosModels["ID"],PDO::PARAM_INT);
        $stmt->bindParam(":tit",$datosModels["TITULO"],PDO::PARAM_STR);
        $stmt->bindParam(":ruta",$datosModels["IMAGEN"],PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;


    }
    /* Modelo para eliminar administrador - Model to remove administrator */
    public function deleteOrganigramamodel($code){
        $query=mainModels::conectar()->prepare("DELETE FROM organigrama WHERE id_organigrama=:Code");
        $query->bindParam(":Code",$code);
        $query->execute();
        return $query;
    }






}