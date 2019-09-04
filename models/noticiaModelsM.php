<?php
//if($AjaxRequest){
//    require_once "../core/mainModel.php";
//}else{
//    require_once "./core/mainModel.php";
//}
//class noticiaModelsM extends mainModels {
//
//
//    public function guardarNoticiaModel($datosModel){
//
//        $stmt=mainModels::conectar()->prepare("INSERT INTO noticias (titulo_noti,introduccion_noti,ruta,contenido)
//                                            VALUES (:titulo,:introduccion,:ruta,:contenido)");
//        $stmt->bindParam(":titulo",$datosModel["atitulo"],PDO::PARAM_STR);
//        $stmt->bindParam(":introduccion",$datosModel["aintroduccion"],PDO::PARAM_STR);
//        $stmt->bindParam(":ruta",$datosModel["aruta"],PDO::PARAM_STR);
//        $stmt->bindParam(":contenido",$datosModel["acontenido"],PDO::PARAM_STR);
//        $stmt->execute();
//        //$stmt->close();
//        return $stmt;
//
//    }
//
//    public function mostrarNoticiaModel($tabla){
//        $stmt=mainModels::conectar()->prepare("SELECT id_noticias,titulo_noti,introduccion_noti,ruta,contenido
//         FROM $tabla ORDER BY id_noticias DESC ");
//        $stmt->execute();
//        return $stmt->fetchAll();
//        $stmt->close();
//
//    }
//
//    public function datos_NoticiaConteo_modelo($tipo){
//        if($tipo=="Conteos") {
//            $query = mainModels::conectar()->prepare("SELECT * FROM noticias WHERE id_noticias!='1'");
//        }
//        $query->execute();
//        return $query;
//    }
//
//    public function deleteNoticiamodel($code){
//        $query=mainModels::conectar()->prepare("DELETE FROM noticias WHERE id_noticias=:id");
//        $query->bindParam(":id",$code);
//        $query->execute();
//        return $query;
//    }
//
//
//
//}