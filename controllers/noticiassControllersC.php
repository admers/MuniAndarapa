<?php
//require './models/noticiaModelsM.php';
//if($AjaxRequest){
//    require_once '../models/noticiaModelsM.php';
//}else{
//    require_once './models/noticiaModelsM.php';
//}
//class noticiassControllersC extends noticiaModelsM {
//
//    public function mostrarImagenController($datos){
//
//        list($ancho,$alto)=getimagesize($datos);
//        if($ancho < 1920 || $alto < 1080){
//            echo 0;
//        }
//        else{
//            $aleatorio = mt_rand(100, 999);
//            $ruta = "../../views/images/noticia/temp/noticia".$aleatorio.".jpg";
//
//            $nuevo_ancho = 1280;
//            $nuevo_alto = 768;
//
//            $origen = imagecreatefromjpeg($datos);
//            #imagecreatetruecolor — Crear una nueva imagen de color verdadero
//            $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
//            #imagecopyresized() - copia una porción de una imagen a otra imagen.
//
//            #bool imagecopyresized( $destino, $origen, int $destino_x, int $destino_y, int $origen_x, int $origen_y, int $destino_w, int $destino_h, int $origen_w, int $origen_h)
//            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
//            imagejpeg($destino, $ruta);
//            echo $ruta;
//        }
//
//
//    }
//
//    #---------------guardar Noticia
//    public function guardarNoticiaController(){
//        if (isset($_POST["Notitulo"])){
//
//            $imagen=$_FILES["imagen"]["tmp_name"];
//            //echo $imagen;
//            /*---------------borrar----------------*/
//            $borrar=glob("views/images/noticia/temp/*");
//            foreach ($borrar as $file){
//                unlink($file) ;
//            }
//            /*-------------------borrar-------------------------*/
//            list($ancho,$alto)=getimagesize($imagen);
//            if($ancho < 1920 || $alto < 1080){
//                echo 0;
//            }
//            else {
//
//
//                $aleatorio = mt_rand(100, 999);
//
//                $ruta = "views/images/noticia/noticia".$aleatorio.".jpg";
//
//                $nuevo_ancho = 1920 ;
//                $nuevo_alto = 1080 ;
//                $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
//                $origen = imagecreatefromjpeg($imagen);
//
//                imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
//                imagejpeg($destino, $ruta);
//
//                $datosController = array("atitulo" => $_POST["Notitulo"],
//                    "aintroduccion" => $_POST["Nointroduccion"]."...",
//                    "aruta" => $ruta,
//                    "acontenido" => $_POST["Nocontenido"]);
//                $respuesta = noticiaModelsM::guardarNoticiaModel($datosController);
//
//
//                echo '<script>
//                    swal({
//                          title: "¡OK!",
//                          text: "¡Noticia ha sido creado correctamente!",
//                          type: "success",
//                          confirmButtonText: "Cerrar",
//                          closeOnConfirm: false
//                    },
//                    function(isConfirm){
//                             if (isConfirm) {
//                                window.location = "noticias";
//                              }
//                    });
//                    </script>';
//
//
//
//
//
//
//
//            }
//        }
//
//    }
//
//    public function mostrarNoticiaController(){
//
//        $respuesta=noticiaModelsM::mostrarNoticiaModel("noticias");
//
//        foreach ($respuesta as $row =>$item){
//            echo '<li>
//			<span>
//			<a href="index.php?action=articulos&idBorrar='.$item["id"].'&rutaImagen='.$item["ruta"].'">
//						<i class="fa fa-times btn btn-danger"></i>
//					</a>
//
//			<i class="fa fa-pencil btn btn-primary"></i>
//			</span>
//			<img src="'.$item['ruta'].'" class="img-thumbnail">
//			<h1>'.$item['titulo_noti'].'</h1>
//			<p>'.$item['introduccion_noti'].'</p>
//			<a href="#articulo'.$item['id_noticias'].'" data-toggle="modal">
//			<button class="btn btn-default">Leer Más</button>
//			</a>
//			<hr>
//			</li>
//
//
//
//
//
//
//            <div id="articulo'.$item['id_noticias']. '" class="modal fade">
//
//                  <div class="modal-dialog modal-content">
//
//                      <div class="modal-header" style="border:1px solid #ee0030">
//
//                      <button type="button" class="close" data-dismiss="modal">&times;</button>
//                          <h3 class="modal-title">' .$item['titulo_noti']. '</h3>
//
//                      </div>
//
//                      <div class="modal-body" style="border:1px solid #0cee00">
//
//                          <img src="' .$item['ruta'].'" width="100%" style="margin-bottom:20px">
//                          <p class="parrafoContenido">'.$item['contenido']. '</p>
//
//                      </div>
//
//                      <div class="modal-footer" style="border:1px solid #ee08c0">
//
//                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
//
//                      </div>
//
//                  </div>
//
//              </div>';
//
//        }
//
//    }
//
////    public function vistaNoticiaController($pagina,$registros){
////
////        $tabla="";
////
////        $pagina= (isset($pagina)&&$pagina>0) ?(int)$pagina:1;
////        //------------contador de datos en la base de datos---------------------
////        $inicio=($pagina>0) ?(($pagina*$registros)-$registros) :0;
////
////        $conexion=mainModels::conectar();
////
////        $datos=$conexion->query("
////        SELECT SQL_CALC_FOUND_ROWS * FROM noticias WHERE id_noticias!='1'
////         ORDER BY id_noticias DESC LIMIT $inicio,$registros
////        ");
////
////        $datos=$datos->fetchAll();
////
////        $total=$conexion->query("SELECT FOUND_ROWS()");
////        $total=(int)$total->fetchColumn();
////        //total de numeros de paginas
////        $Npaginas=ceil($total/$registros);
////
////        /*-------------------------paginando en una lista---------------------------*/
////        $tabla.='<div class="table-responsive">
////                <table class="table table-hover text-center">
////                    <thead>
////                    <tr>
////                        <th class="text-center">#</th>
////                        <th class="text-center">ID</th>
////                        <th class="text-center">TITULO</th>
////                        <th class="text-center">INTRODUCCION</th>
////
////                        <th class="text-center">ELIMINAR</th>
////                        <th class="text-center">VER</th>
////                    </tr>
////                    </thead>
////                    <tbody>
////                    ';
////        if ($total>=1 && $pagina<=$Npaginas){
////            $contador=$inicio+1;
////            foreach ($datos as $rows){
////                $tabla.='<tr>
////                            <td>'.$contador.'</td>
////                            <td>'.$rows['id_noticias'].'</td>
////                            <td>'.$rows['titulo_noti'].'</td>
////                            <td>'.$rows['introduccion_noti'].'</td>
////
////
////                            <td>
////                                <form action="'.SERVERURL.'ajax/gestorNoticia.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
////									<input type="hidden" name="idNoticia-del" value="'.$rows["id_noticias"].'">
////									<input type="hidden" name="imagNoticia-del" value="'.$rows["ruta"].'">
////									<button type="submit" class="btn btn-danger btn-raised btn-xs">
////										<i class="zmdi zmdi-delete"></i>
////									</button>
////									<span class="RespuestaAjax"></span>
////                            </td>
////                            <td>
////                                <a href="#noticia'.$rows["id_noticias"].'" data-toggle="modal" class="btn btn-success btn-raised btn-xs">
////                                    <i class="zmdi zmdi-eye"></i>
////                                </a>
////                                <div id="noticia'.$rows["id_noticias"].'" class="modal fade" style="width: 100%,
////                                    " >
////
////                                    <div class="modal-dialog modal-content">
////
////                                        <div class="modal-header" style="border:1px solid #eee; width: auto;">
////
////                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
////                                         <h2 class="modal-title" style="width: 100%;">'.$rows["titulo_noti"].'</h2>
////
////                                        </div>
////
////                                        <div class="modal-body" style="border:1px solid #eee;width: auto;">
////
////                                            <img src="../'.$rows['ruta'].'" width="100%" style="margin-bottom:20px">
////                                            <p class="parrafoContenido">'.$rows['contenido'].'</p>
////
////                                        </div>
////
////                                        <div class="modal-footer" style="border:1px solid #eee">
////
////                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
////
////                                        </div>
////
////                                    </div>
////
////                                </div>
////                            </td>
////
////                        </tr>
////                        ';
////
////                $contador++;
////            }
////        }else{
////            /*---------------------para eliminar el mensaje que muestra-----------------------*/
////            if($total>=1){
////                $tabla.='
////            <tr>
////                <td colspan="5"></td>
////
////            </tr>
////            ';
////            }else{
////                $tabla.='
////            <tr>
////                <td colspan="5">No hay registros en el sistema</td>
////
////            </tr>
////            ';
////            }
////
////        }
////        $tabla.='</tbody></table></div>';
////
////
////        /*.--------------PAGINADOR--------------------*/
////
////        if ($total>=1 && $pagina<=$Npaginas){
////            $tabla.='<nav class="text-center">
////                        <ul class="pagination pagination-sm">';
////
////            if ($pagina==1){
////                $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-left"></i></a></li>';
////            }else{
////                $tabla.='<li ><a href="'.SERVERURL.'noticias/'.($pagina-1).'"><i class="zmdi zmdi-arrow-left"></i></a></li>';
////            }
////
////            for ($i=1;$i<=$Npaginas;$i++){
////
////                if ($pagina==$i){
////                    $tabla.='<li class="active"><a href="'.SERVERURL.'noticias/'.$i.'">'.$i.'</a></li>';
////                }
////                else{
////                    $tabla.='<li ><a href="'.SERVERURL.'noticias/'.$i.'">'.$i.'</a></li>';
////
////
////                }
////            }
////
////            if ($pagina==$Npaginas){
////                $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
////            }else{
////                $tabla.='<li ><a href="'.SERVERURL.'noticias/'.($pagina+1).'"><i class="zmdi zmdi-arrow-right"></i></a></li>';
////
////            }
////
////            $tabla.='</ul></nav>';
////        }
////
////
////
////
////        return  $tabla;
////
////
////
////    }
//    public function vistaNoticiaController($pagina,$registros){
//
//        $tabla="";
//
//        $pagina= (isset($pagina)&&$pagina>0) ?(int)$pagina:1;
//        //------------contador de datos en la base de datos---------------------
//        $inicio=($pagina>0) ?(($pagina*$registros)-$registros) :0;
//
//        $conexion=mainModels::conectar();
//
//        $datos=$conexion->query("
//        SELECT SQL_CALC_FOUND_ROWS * FROM noticias WHERE id_noticias!='1'
//         ORDER BY id_noticias DESC LIMIT $inicio,$registros
//        ");
//
//        $datos=$datos->fetchAll();
//
//        $total=$conexion->query("SELECT FOUND_ROWS()");
//        $total=(int)$total->fetchColumn();
//        //total de numeros de paginas
//        $Npaginas=ceil($total/$registros);
//
//        /*-------------------------paginando en una lista---------------------------*/
//        $tabla.='<div class="table-responsive">
//                <table class="table table-hover text-center">
//                    <thead>
//                    <tr>
//                        <th class="text-center">#</th>
//                        <th class="text-center">ID</th>
//                        <th class="text-center">TITULO</th>
//                        <th class="text-center">CODIGO</th>
//
//
//                        <th class="text-center">ESTADO</th>
//                        <th class="text-center">ELIMINAR</th>
//                        <th class="text-center">VER</th>
//                        <th class="text-center">ACTUALIZAR</th>
//                    </tr>
//                    </thead>
//                    <tbody>
//                    ';
//        if ($total>=1 && $pagina<=$Npaginas){
//            $contador=$inicio+1;
//            foreach ($datos as $rows){
//                $tabla.='<tr>
//                            <td>'.$contador.'</td>
//                            <td>'.$rows['id_noticias'].'</td>
//                            <td>'.$rows['titulo_noti'].'</td>
//                            <td>'.$rows['introduccion_noti'].'</td>
//                            <td>'.$rows['ruta'].'</td>
//                            <td>'.$rows['contenido'].'</td>
//
//
//                            <td>'.$rows['fecha_publi'].'</td>
//
//                            <td>
//                                <form action="'.SERVERURL.'ajax/gestorNoticia.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
//									<input type="hidden" name="idNoticia-del" value="'.$rows['id_noticias'].'">
//									<input type="hidden" name="imagNoticia-del" value="'.$rows['ruta'].'">
//									<button type="submit" class="btn btn-danger btn-raised btn-xs">
//										<i class="zmdi zmdi-delete"></i>
//									</button>
//									<span class="RespuestaAjax"></span>
//                            </td>
//                            <td>
//                                <a href="#noticia'.$rows["id_noticias"].'" data-toggle="modal" class="btn btn-success btn-raised btn-xs">
//                                    <i class="zmdi zmdi-eye"></i>
//                                </a>
//                                <div id="noticia'.$rows["id_noticias"].'" class="modal fade" style="width: 100%,
//                                    " >
//
//                                    <div class="modal-dialog modal-content">
//
//                                        <div class="modal-header" style="border:1px solid #eee; width: auto;">
//
//                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
//                                         <h2 class="modal-title" style="width: 100%;">'.$rows["titulo_noti"].'</h2>
//
//                                        </div>
//
//                                        <div class="modal-body" style="border:1px solid #eee;width: auto;">
//
//                                            <img src="../'.$rows['ruta'].'" width="100%" style="margin-bottom:20px">
//                                            <p class="parrafoContenido">'.$rows['contenido'].'</p>
//
//                                        </div>
//
//                                        <div class="modal-footer" style="border:1px solid #eee">
//
//                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
//
//                                        </div>
//
//                                    </div>
//
//                                </div>
//                            </td>
//                            <td>
//							    <a href="'.SERVERURL.'upObrasP/'.$rows['id_op'].'" class="btn btn-primary btn-raised btn-xs">
//									<i class="zmdi zmdi-refresh"></i>
//								</a>
//                            </td>
//
//                        </tr>
//                        ';
//
//                $contador++;
//            }
//        }else{
//            /*---------------------para eliminar el mensaje que muestra-----------------------*/
//            if($total>=1){
//                $tabla.='
//            <tr>
//                <td colspan="5"></td>
//
//            </tr>
//            ';
//            }else{
//                $tabla.='
//            <tr>
//                <td colspan="5">No hay registros en el sistema</td>
//
//            </tr>
//            ';
//            }
//
//        }
//        $tabla.='</tbody></table></div>';
//
//
//        /*.--------------PAGINADOR--------------------*/
//
//        if ($total>=1 && $pagina<=$Npaginas){
//            $tabla.='<nav class="text-center">
//                        <ul class="pagination pagination-sm">';
//
//            if ($pagina==1){
//                $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-left"></i></a></li>';
//            }else{
//                $tabla.='<li ><a href="'.SERVERURL.'noticias/'.($pagina-1).'"><i class="zmdi zmdi-arrow-left"></i></a></li>';
//            }
//
//            for ($i=1;$i<=$Npaginas;$i++){
//
//                if ($pagina==$i){
//                    $tabla.='<li class="active"><a href="'.SERVERURL.'noticias/'.$i.'">'.$i.'</a></li>';
//                }
//                else{
//                    $tabla.='<li ><a href="'.SERVERURL.'noticias/'.$i.'">'.$i.'</a></li>';
//
//
//                }
//            }
//
//            if ($pagina==$Npaginas){
//                $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
//            }else{
//                $tabla.='<li ><a href="'.SERVERURL.'noticias/'.($pagina+1).'"><i class="zmdi zmdi-arrow-right"></i></a></li>';
//
//            }
//
//            $tabla.='</ul></nav>';
//        }
//
//
//
//
//
//
//
//
//        return  $tabla;
//
//
//
//    }
//
//
//    public function datos_NotConteo_ontrolador($conteo){
//        //return GestorsliderM::datos_Slider_modelo($codigo);
//        return noticiaModelsM::datos_NoticiaConteo_modelo($conteo);
//    }
//
//
//    public function deleteNoticiaController(){
//
//        if(isset($_POST['idNoticia-del'])) {
//            $code = $_POST['idNoticia-del'];
//          //  $ad = $_POST['imagObrasP-del'];
//
//           // unlink($_SERVER['DOCUMENT_ROOT'].'/MuniiFinall/'.$_GET["$ad"]);
//
//
//
//            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM noticias WHERE id_noticias='$code'");
//            $adminData = $query1->fetch();
//            if ($adminData['id_noticias']!=1) {
//
//                $DelAdmin = noticiaModelsM::deleteNoticiamodel($code);
//                $url = SERVERURL."noticias/";
//
//
//            }
//            return $urlLocation = '<script type="text/javascript"> window.location="'.$url.'"; </script>';
//        }
//
//
//    }
///*    public function deleteGobernantecontroller()
//    {
//
//        if (isset($_POST['idGober-del'])) {
//            $code = $_POST['idGober-del'];
//            $ad = $_POST['imagGober-del'];
//
//            //unlink($_SERVER['DOCUMENT_ROOT'].'/MuniiFinall/'.$_GET["$ad"]);
//
//
//            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM gobernante WHERE id_gobernante='$code'");
//            $adminData = $query1->fetch();
//            if ($adminData['id_gobernante'] != 1) {
//
//                $DelAdmin = adminListModelssM::deleteGobernantemodel($code);
//                $url = SERVERURL . "gobernante/";
//
//
//            }
//            return $urlLocation = '<script type="text/javascript"> window.location="' . $url . '"; </script>';
//        }
//
//
//    }*/
//
//
//
//}