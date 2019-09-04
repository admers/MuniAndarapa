<?
if($AjaxRequest){
    require_once '../models/sliderModelsM.php';
}else{
    require_once './models/sliderModelsM.php';
}


class GestorSlidersC extends GestorsliderM{

    public function mostrarImagenController($datos){
        list($ancho,$alto)=getimagesize($datos);
        if($ancho < 1920 || $alto < 1080){
            echo 0;
        }
        else{
            $aleatorio = mt_rand(100, 999);
            $ruta = SERVERURL."views/images/slider/temp/slider".$aleatorio.".jpg";

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

        //return $ruta;
    }
    #---------------guardar Slider---------------------------------------------------
    public function guardarSlider(){

  
        if (isset($_POST["titleSlider"])){

            $imagen=$_FILES["imagen"]["tmp_name"];
            //echo $imagen;
            /*---------------borrar----------------*/
            $borrar=glob("views/images/slider/temp/*");
            foreach ($borrar as $file){
                unlink($file) ;
            }
            /*-------------------borrar-------------------------*/
            list($ancho,$alto)=getimagesize($imagen);
            if($ancho < 1920 || $alto < 1080){
                echo 0;
            }else {


                $aleatorio = mt_rand(100, 999);
                $ruta = "views/images/slider/slider" . $aleatorio . ".jpg";
                $nuevo_ancho = 1920;
                $nuevo_alto = 1080;
                $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
                $origen = imagecreatefromjpeg($imagen);

                imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
                imagejpeg($destino, $ruta);

                $datosController = array("stitulo" => $_POST["titleSlider"],
                    "sruta" => $ruta
                );
                $respuesta = GestorsliderM::guardarSliderModel($datosController);


                echo '<script>
                swal({
                        title: "¡OK!",
                        text: "¡El Slider ha sido creado correctamente!",
                        type: "success",
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                },
                function(isConfirm){
                            if (isConfirm) {	   
                            window.location = "slider";
                            } 
                });
                </script>';

            }
        }

    }
    public function vistaSliderController($pagina,$registros){

        $tabla="";

        $pagina= (isset($pagina)&&$pagina>0) ?(int)$pagina:1;
        //------------contador de datos en la base de datos---------------------
        $inicio=($pagina>0) ?(($pagina*$registros)-$registros) :0;

        $conexion=mainModels::conectar();

        $datos=$conexion->query("
        SELECT SQL_CALC_FOUND_ROWS * FROM slider WHERE idslider!='1'
         ORDER BY idslider DESC LIMIT $inicio,$registros
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
                        <th class="text-center">ELIMINAR</th>
                        <th class="text-center">VER</th>
                        <th class="text-center">EDITAR</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';
        if ($total>=1 && $pagina<=$Npaginas){
            $contador=$inicio+1;
        
            foreach ($datos as $rows){
                $tabla.='<tr>
                            <td>'.$contador.'</td>
                            <td>'.$rows['idslider'].'</td>
                            <td ><span maxlength="2">'.$rows['titulo_slider'].'</span></td>
                            
                            <td>
                                <form action="'.SERVERURL.'ajax/gestorSlider.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="idslider-del" value="'.$rows["idslider"].'">
									<input type="hidden" name="imagslider-del" value="'.$rows["ruta_slider"].'">
									<button type="submit" class="btn btn-danger btn-raised btn-xs">
										<i class="zmdi zmdi-delete"></i>
									</button>
									<span class="RespuestaAjax"></span>
								</form>
                            </td>
                            <td>
                                <a href="#noticia'.$rows["idslider"].'" data-toggle="modal" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-eye"></i>
                                </a>   
                                <div id="noticia'.$rows["idslider"].'" class="modal fade">
    
                                    <div class="modal-dialog modal-content">
        
                                        <div class="modal-header" style="border:1px solid #eee">
                                        
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                         <h3 class="modal-title" >'.$rows["titulo_slider"].'</h3>
                                    
                                        </div>
        
                                        <div class="modal-body" style="border:1px solid #eee">
                                    
                                            <img src="../'.$rows['ruta_slider'].'" width="100%" style="margin-bottom:20px">
                                    
                                        </div>
        
                                        <div class="modal-footer" style="border:1px solid #eee">
                                    
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    
                                        </div>
        
                                    </div>
        
                                </div>
                            </td>
                            <td>
							    <a href="'.SERVERURL.'upSlider/'.$rows['idslider'].'" class="btn btn-primary btn-raised btn-xs">
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

        /*.--------------PAGINADOR--------------------*/

        if ($total>=1 && $pagina<=$Npaginas){
            $tabla.='<nav class="text-center">
                        <ul class="pagination pagination-sm">';

            if ($pagina==1){
                $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-left"></i></a></li>';
            }else{
                $tabla.='<li ><a href="'.SERVERURL.'slider/'.($pagina-1).'"><i class="zmdi zmdi-arrow-left"></i></a></li>';
            }

            for ($i=1;$i<=$Npaginas;$i++){

                if ($pagina==$i){
                    $tabla.='<li class="active"><a href="'.SERVERURL.'slider/'.$i.'">'.$i.'</a></li>';
                }
                else{
                    $tabla.='<li ><a href="'.SERVERURL.'slider/'.$i.'">'.$i.'</a></li>';


                }
            }

            if ($pagina==$Npaginas){
                $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
            }else{
                $tabla.='<li ><a href="'.SERVERURL.'slider/'.($pagina+1).'"><i class="zmdi zmdi-arrow-right"></i></a></li>';

            }

            $tabla.='</ul></nav>';
        }





        return  $tabla;



    }
    public function datos_Slider_controlador($codigo){

        return GestorsliderM::datos_Slider_modelo($codigo);
    }
    public function datos_SliderConteo_controlador($codigo){

        return GestorsliderM::datos_SliderConteo_modelo($codigo);
    }
    public function actualizarSliderController(){

        $rutaF="";
        if (isset($_POST['titleSliderUp'])) {

                    if (isset($_FILES["imgSliderup"]["tmp_name"])) {

                        $tituloup = $_POST['titleSliderUp'];
                        //$imagup=$_POST['imgSliderup'];
                        $idup = $_POST['idSliderUp'];

                        $imagen = $_FILES["imgSliderup"]["tmp_name"];
                        /*-------------------borrar-------------------------*/
                        list($ancho, $alto) = getimagesize($imagen);
                        if ($ancho < 1920 || $alto < 1080) {
                            echo 0;
                        } else {


                            $aleatorio = mt_rand(100, 999);
                            $ruta = $_SERVER['DOCUMENT_ROOT'].'/backend/views/images/slider/slider' . $aleatorio . ".jpg";
                            $rutaF = 'views/images/slider/slider' . $aleatorio . ".jpg";

                            $nuevo_ancho = 1920;
                            $nuevo_alto = 1080;
                            $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
                            $origen = imagecreatefromjpeg($imagen);

                            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

                            imagejpeg($destino, $ruta);
                        }
                    }

                    if ($rutaF == "") {
                        $rutaF = $_POST["fotoAntiguoSlider"];
                    }
                    else{

                        unlink($_SERVER['DOCUMENT_ROOT'].'/backend/'.$_POST["fotoAntiguoSlider"]);
                    }





                $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM slider WHERE idslider='$idup'");
                $DatosAdmin = $query1->fetch();

                $dataAd = [
                    "ID" => $idup,
                    "TITULO" => $tituloup,
                    "IMAGEN" => $rutaF,

                ];

                if ($DatosAdmin['idslider'] != 1) {

                    $DelAdmin = GestorsliderM::actualizarSliderrModel($dataAd);
                    $url = SERVERURL . "slider/";


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

                return $urlLocation = '<script type="text/javascript"> window.location="' . $url . '"; </script>';





        }
    }
    public function deleteSliderController(){
        $code=$_POST['idslider-del'];
        $adminLevel=$_POST['imagslider-del'];




        $query1=mainModels::ejecutar_consulta_simple("SELECT * FROM slider WHERE islider='$code'");
        $adminData=$query1->fetch();
        if($adminData['idslider']!=1) {

            $DelAdmin = GestorsliderM::deleteSlidermodel($code);
            $url=SERVERURL."slider/";


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
        return $urlLocation='<script type="text/javascript"> window.location="'.$url.'"; </script>';



    }




}    