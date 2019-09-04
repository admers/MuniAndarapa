<?
if($AjaxRequest){
    require_once '../models/adminListModelsM.php';
}else{
    require_once './models/adminListModelsM.php';
}


class obrasPControllerssC extends adminListModelssM{

    public function mostrarImagenController($datos){

        list($ancho,$alto)=getimagesize($datos);
        if($ancho < 1920 || $alto < 1080){
            echo 0;
        }
        else{
            $aleatorio = mt_rand(100, 999);
            $ruta = "../../views/images/obrasProyectos/temp/obrasP".$aleatorio.".jpg";

            $nuevo_ancho = 1280;
            $nuevo_alto = 768;

            $origen = imagecreatefromjpeg($datos);
            #imagecreatetruecolor — Crear una nueva imagen de color verdadero
            $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
            #imagecopyresized() - copia una porción de una imagen a otra imagen.

            #bool imagecopyresized( $destino, $origen, int $destino_x, int $destino_y, int $origen_x, int $origen_y, int $destino_w, int $destino_h, int $origen_w, int $origen_h)
            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
            imagejpeg($destino, $ruta);
            echo $ruta;
        }


    }
    public function guardarObrasPControllers(){

        if (isset($_POST["titleObrasP"])){

            $imagen=$_FILES["imagen"]["tmp_name"];
            //echo $imagen;
            /*---------------borrar----------------*/
            $borrar=glob("views/images/obrasProyectos/temp/*");
            foreach ($borrar as $file){
                unlink($file) ;
            }
            /*-------------------borrar-------------------------*/
            list($ancho,$alto)=getimagesize($imagen);


            $aleatorio = mt_rand(100, 999);
            $ruta = "views/images/obrasProyectos/obrasP".$aleatorio.".jpg";
            $nuevo_ancho = 1920 ;
            $nuevo_alto = 1080 ;
            $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
            $origen = imagecreatefromjpeg($imagen);

            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
            imagejpeg($destino, $ruta);


            $datosController = array(
                "optitulo" => $_POST["titleObrasP"],
                "opcodigo" => $_POST["codigoObrasP"],
                "opruta" => $ruta,
                "opcontenido"=>$_POST["contenidoObrasP"],
                "opestado"=>$_POST["estadoObrasP"],
            );

            $respuesta = adminListModelssM::guardarOPModel($datosController);


            echo '<script>
                swal({
                      title: "¡OK!",
                      text: "¡Obras y Proyectos ha sido creado correctamente!",
                      type: "success",
                      confirmButtonText: "Cerrar",
                      closeOnConfirm: false
                },
                function(isConfirm){
                         if (isConfirm) {	   
                            window.location = "'.SERVERURL.'obrasProyectos/";
                          } 
                });
                </script>';







        }


    }
    public function vistaObrasPController($pagina,$registros){

        $tabla="";

        $pagina= (isset($pagina)&&$pagina>0) ?(int)$pagina:1;
        //------------contador de datos en la base de datos---------------------
        $inicio=($pagina>0) ?(($pagina*$registros)-$registros) :0;

        $conexion=mainModels::conectar();

        $datos=$conexion->query("
        SELECT SQL_CALC_FOUND_ROWS * FROM obras_proy WHERE id_op!='1'
         ORDER BY id_op DESC LIMIT $inicio,$registros
        ");

        $datos=$datos->fetchAll();

        $total=$conexion->query("SELECT FOUND_ROWS()");
        $total=(int)$total->fetchColumn();
        //total de numeros de paginas
        $Npaginas=ceil($total/$registros);

        /*-------------------------paginando en una lista---------------------------*/
        $tabla.='<div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">ID</th>
                        <th class="text-center">TITULO</th>
                        <th class="text-center">CODIGO</th>
                        
                        <th class="text-center">CONTENIDO</th>
                        <th class="text-center">ESTADO</th>
                        <th class="text-center">ELIMINAR</th>
                        <th class="text-center">VER</th>
                        <th class="text-center">ACTUALIZAR</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';
        if ($total>=1 && $pagina<=$Npaginas){
            $contador=$inicio+1;
            foreach ($datos as $rows){
                $tabla.='<tr>
                            <td>'.$contador.'</td>
                            <td>'.$rows['id_op'].'</td>
                            <td>'.$rows['titulo_op'].'</td>
                            <td>'.$rows['codigo_op'].'</td>
                            
                            <td>'.$rows['contenido_op'].'</td>
                            <td>'.$rows['estado'].'</td>
                            
                            <td>
                                <form action="'.SERVERURL.'ajax/gestorObrasP.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="idObrasP-del" value="'.$rows["id_op"].'">
									<input type="hidden" name="imagObrasP-del" value="'.$rows["ruta_op"].'">
									<button type="submit" class="btn btn-danger btn-raised btn-xs">
										<i class="zmdi zmdi-delete"></i>
									</button>
									<span class="RespuestaAjax"></span>
								</form>
                            </td>
                            <td>
                                <a href="#obrasp'.$rows["id_op"].'" data-toggle="modal" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-eye"></i>
                                </a>   
                                <div id="obrasp'.$rows["id_op"].'" class="modal fade">
    
                                    <div class="modal-dialog modal-content">
        
                                        <div class="modal-header" style="border:1px solid #eee">
                                        
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                         <h3 class="modal-title">'.$rows["titulo_op"].'</h3>
                                         <h4 class="modal-title">Codigo: '.$rows["codigo_op"].'  Estado: '.$rows["estado"].'</h4>
                                        
                                    
                                        </div>
        
                                        <div class="modal-body" style="border:1px solid #eee">
                                    
                                            <img src="../'.$rows['ruta_op'].'" width="100%" style="margin-bottom:20px">
                                            <p class="parrafoContenido">'.$rows['contenido_op'].'</p>
                                    
                                        </div>
        
                                        <div class="modal-footer" style="border:1px solid #eee">
                                    
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    
                                        </div>
        
                                    </div>
        
                                </div>
                            </td>
                            <td>
							    <a href="'.SERVERURL.'upObrasP/'.$rows['id_op'].'" class="btn btn-primary btn-raised btn-xs">
									<i class="zmdi zmdi-refresh"></i>
								</a>
                            </td>
                           
                            
                        </tr>
                        ';

                $contador++;
            }
        }else{
            /*---------------------para eliminar el mensaje que muestra-----------------------*/
            if($total>=1){
                $tabla.='
            <tr>
                <td colspan="5"></td>
            
            </tr>
            ';
            }else{
                $tabla.='
            <tr>
                <td colspan="5">No hay registros en el sistema</td>
            
            </tr>
            ';
            }

        }
        $tabla.='</tbody></table></div>';


        /*.--------------PAGINADOR--------------------*/

        if ($total>=1 && $pagina<=$Npaginas){
            $tabla.='<nav class="text-center">
                        <ul class="pagination pagination-sm">';

            if ($pagina==1){
                $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-left"></i></a></li>';
            }else{
                $tabla.='<li ><a href="'.SERVERURL.'obrasProyectos/'.($pagina-1).'"><i class="zmdi zmdi-arrow-left"></i></a></li>';
            }

            for ($i=1;$i<=$Npaginas;$i++){

                if ($pagina==$i){
                    $tabla.='<li class="active"><a href="'.SERVERURL.'obrasProyectos/'.$i.'">'.$i.'</a></li>';
                }
                else{
                    $tabla.='<li ><a href="'.SERVERURL.'obrasProyectos/'.$i.'">'.$i.'</a></li>';


                }
            }

            if ($pagina==$Npaginas){
                $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
            }else{
                $tabla.='<li ><a href="'.SERVERURL.'obrasProyectos/'.($pagina+1).'"><i class="zmdi zmdi-arrow-right"></i></a></li>';

            }

            $tabla.='</ul></nav>';
        }








        return  $tabla;



    }
    public function datos_obrasP_ontrolador($codigo){

        //return GestorsliderM::datos_Slider_modelo($codigo);


        return adminListModelssM::datos_ObrasP_modelo($codigo);
    }
    public function actualizar_ObrasPController(){

        $rutaF="";
        if (isset($_POST['titleObrasPUP'])) {

            if (isset($_FILES["imgOPUP"]["tmp_name"])) {

                $idup = $_POST['idObrasPUP'];
                $tituloup = $_POST['titleObrasPUP'];
                $codigoup = $_POST['codigoObrasPUP'];
                $contenidoup = $_POST['contenidoObrasPUP'];
                $estadoup = $_POST['estadoObrasPUP'];


                $imagen = $_FILES["imgOPUP"]["tmp_name"];
                /*-------------------borrar-------------------------*/
                list($ancho, $alto) = getimagesize($imagen);
                if ($ancho < 1920 || $alto < 1080) {
                    echo 0;
                } else {


                    $aleatorio = mt_rand(100, 999);
                    $ruta = $_SERVER['DOCUMENT_ROOT'].'/MuniiFinall/views/images/obrasProyectos/obrasP'.$aleatorio.".jpg";
                    $rutaF = 'views/images/obrasProyectos/obrasP'.$aleatorio.".jpg";

                    $nuevo_ancho = 1920;
                    $nuevo_alto = 1080;
                    $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
                    $origen = imagecreatefromjpeg($imagen);

                    imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

                    imagejpeg($destino, $ruta);
                }
            }

            if ($rutaF == "") {
                $rutaF = $_POST["fotoAntiguoOP"];
            }
            else{

                unlink($_SERVER['DOCUMENT_ROOT'].'/MuniiFinall/'.$_POST["fotoAntiguoOP"]);
            }


            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM obras_proy WHERE id_op='$idup'");
            $DatosAdmin = $query1->fetch();

            $dataAd = [
                "ID" => $idup,
                "TITULO" => $tituloup,
                "CODIGO" => $codigoup,
                "IMAGEN" => $rutaF,
                "CONTENIDO" => $contenidoup,
                "ESTADO" => $estadoup,

            ];

            if ($DatosAdmin['id_op'] != 1) {

                $DelAdmin = adminListModelssM::actualizar_ObrasPModel($dataAd);
                $url = SERVERURL."obrasProyectos";


                if ($DelAdmin->rowCount() >= 1) {

                    $dataAlert = [
                        "Title" => "Administrador eliminado",
                        "Text" => "El administrador fue eliminado del sistema con éxito",
                        "Type" => "success",
                        "Alert" => "reload"
                    ];


                } else {
                    $dataAlert = [
                        "Title" => "Ocurrió un error inesperado",
                        "Text" => "No podemos eliminar la cuenta en este momento",
                        "Type" => "error",
                        "Alert" => "single"
                    ];

                }

            }

            return $urlLocation = '<script type="text/javascript"> window.location="'.$url.'";</script>';

        }

    }
    public function deleteObrasPcontroller(){

        if(isset($_POST['idObrasP-del'])) {
            $code = $_POST['idObrasP-del'];
            $ad = $_POST['imagObrasP-del'];

            unlink($_SERVER['DOCUMENT_ROOT'].'/MuniiFinall/'.$_GET["$ad"]);



            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM obras_proy WHERE id_op='$code'");
            $adminData = $query1->fetch();
            if ($adminData['id_op'] != 1) {

                $DelAdmin = adminListModelssM::deleteObrasPmodel($code);
                $url = SERVERURL."obrasProyectos/";


            }
            return $urlLocation = '<script type="text/javascript"> window.location="'.$url.'"; </script>';
        }


    }

}