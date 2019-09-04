<?php
//require './models/adminListModelsM.php';

if($AjaxRequest){
    require_once '../models/adminListModelsM.php';
}else{
    require_once './models/adminListModelsM.php';
}


class adminListControllerssC extends adminListModelssM
{
    /*---------------------Noticia--------------------------*/
    public function mostrarImagenNoticiaController($datos){

        list($ancho,$alto)=getimagesize($datos);
        if($ancho < 1920 || $alto < 1080){
            echo 0;
        }
        else{
            $aleatorio = mt_rand(100, 999);
            $ruta = "../../views/images/noticia/temp/noticia".$aleatorio.".jpg";

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
    public function guardarNoticiaController(){
        if (isset($_POST["Notitulo"])){

            $imagen=$_FILES["imagen"]["tmp_name"];
            //echo $imagen;
            /*---------------borrar----------------*/
            $borrar=glob("views/images/noticia/temp/*");
            foreach ($borrar as $file){
                unlink($file) ;
            }
            /*-------------------borrar-------------------------*/
            list($ancho,$alto)=getimagesize($imagen);
            if($ancho < 1920 || $alto < 1080){
                echo 0;
            }
            else {


                $aleatorio = mt_rand(100, 999);

                $ruta = "views/images/noticia/noticia".$aleatorio.".jpg";

                $nuevo_ancho = 1920 ;
                $nuevo_alto = 1080 ;
                $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
                $origen = imagecreatefromjpeg($imagen);

                imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
                imagejpeg($destino, $ruta);



                $hoy = date("Y-m-d" );

                $datosController = array("atitulo" => $_POST["Notitulo"],
                    "aintroduccion" => $_POST["Nointroduccion"]."...",
                    "aruta" => $ruta,
                    "acontenido" => $_POST["Nocontenido"],
                    "dato" => $hoy
                );
                $respuesta = adminListModelssM::guardarNoticiaModel($datosController);


                echo '<script>
                    swal({
                          title: "¡OK!",
                          text: "¡Noticia ha sido creado correctamente!",
                          type: "success",
                          confirmButtonText: "Cerrar",
                          closeOnConfirm: false
                    },
                    function(isConfirm){
                             if (isConfirm) {	   
                                window.location = "noticias";
                              } 
                    });
                    </script>';







            }
        }

    }
    public function vistaNoticiaController($pagina,$registros){

        $tabla="";

        $pagina= (isset($pagina)&&$pagina>0) ?(int)$pagina:1;
        //------------contador de datos en la base de datos---------------------
        $inicio=($pagina>0) ?(($pagina*$registros)-$registros) :0;

        $conexion=mainModels::conectar();

        $datos=$conexion->query("
        SELECT SQL_CALC_FOUND_ROWS * FROM noticias WHERE id_noticias!='1'
         ORDER BY id_noticias DESC LIMIT $inicio,$registros
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
                        <th class="text-center">INTRODUCCION</th>


                        <th class="text-center">FECHA</th>
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
                            <td>'.$rows['id_noticias'].'</td>
                            <td>'.$rows['titulo_noti'].'</td>
                            <td>'.$rows['introduccion_noti'].'</td>
                            
                            
                            
                            <td>'.$rows['fecha_publi'].'</td>

                            <td>
                                <form action="'.SERVERURL.'ajax/adminAjax.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="idNotDel" value="' . $rows["id_noticias"] . '">
									<input type="hidden" name="imagnNotiDel" value="' . $rows["ruta"] . '">
									<button type="submit" class="btn btn-danger btn-raised btn-xs">
										<i class="zmdi zmdi-delete"></i>
									</button>
									<span class="RespuestaAjax"></span>
								</form>
                            </td>
                            <td>
                                <a href="#noticia'.$rows["id_noticias"].'" data-toggle="modal" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-eye"></i>
                                </a>
                                <div id="noticia'.$rows["id_noticias"].'" class="modal fade" style="width: 100%,
                                    " >

                                    <div class="modal-dialog modal-content">

                                        <div class="modal-header" style="border:1px solid #eee; width: auto;">

                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                         <h2 class="modal-title" style="width: 100%;">'.$rows["titulo_noti"].'</h2>

                                        </div>

                                        <div class="modal-body" style="border:1px solid #eee;width: auto;">

                                            <img src="../'.$rows['ruta'].'" width="100%" style="margin-bottom:20px">
                                            <p class="parrafoContenido">'.$rows['contenido'].'</p>

                                        </div>

                                        <div class="modal-footer" style="border:1px solid #eee">

                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                        </div>

                                    </div>

                                </div>
                            </td>
                            <td>
							    <a href="'.SERVERURL.'upNoticias/'.$rows['id_noticias'].'" class="btn btn-primary btn-raised btn-xs">
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
                $tabla.='<li ><a href="'.SERVERURL.'noticias/'.($pagina-1).'"><i class="zmdi zmdi-arrow-left"></i></a></li>';
            }

            for ($i=1;$i<=$Npaginas;$i++){

                if ($pagina==$i){
                    $tabla.='<li class="active"><a href="'.SERVERURL.'noticias/'.$i.'">'.$i.'</a></li>';
                }
                else{
                    $tabla.='<li ><a href="'.SERVERURL.'noticias/'.$i.'">'.$i.'</a></li>';


                }
            }

            if ($pagina==$Npaginas){
                $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
            }else{
                $tabla.='<li ><a href="'.SERVERURL.'noticias/'.($pagina+1).'"><i class="zmdi zmdi-arrow-right"></i></a></li>';

            }

            $tabla.='</ul></nav>';
        }








        return  $tabla;



    }
    public function datos_Noticia_controlador($codigo){
        //return GestorsliderM::datos_Slider_modelo($codigo);
        return adminListModelssM::datos_Noticia_modelo($codigo);
    }
    public function datos_NotConteo_ontrolador($conteo){
        //return GestorsliderM::datos_Slider_modelo($codigo);
        return adminListModelssM::datos_NoticiaConteo_modelo($conteo);
    }
    public function actualizar_NoticiaController(){

        $rutaF="";
        if (isset($_POST['NotituloUP'])) {

            if (isset($_FILES["imgNoticiaup"]["tmp_name"])) {

                $idup = $_POST['idNoticiaUP'];
                $tituloup = $_POST['NotituloUP'];
                $introcuccionup = $_POST['NointroduccionUP'];
                $contenidoup = $_POST['NocontenidoUP'];
                $fechaNoti = $_POST['fechaNoticiaUP'];


                $imagen = $_FILES["imgNoticiaup"]["tmp_name"];
                /*-------------------borrar-------------------------*/
                list($ancho, $alto) = getimagesize($imagen);
                if ($ancho < 1920 || $alto < 1080) {
                    echo 0;
                } else {


                    $aleatorio = mt_rand(100, 999);
                    $ruta = $_SERVER['DOCUMENT_ROOT'].'/backend/views/images/noticia/noticia'.$aleatorio.".jpg";
                    $rutaF = 'views/images/noticia/noticia'.$aleatorio.".jpg";

                    $nuevo_ancho = 1920;
                    $nuevo_alto = 1080;
                    $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
                    $origen = imagecreatefromjpeg($imagen);

                    imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

                    imagejpeg($destino, $ruta);
                }
            }

            if ($rutaF == "") {
                $rutaF = $_POST["fotoAntiguoNoticia"];
            }
            else{

                unlink($_SERVER['DOCUMENT_ROOT'].'/backend/'.$_POST["fotoAntiguoNoticia"]);
            }


            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM noticias WHERE id_noticias='$idup'");
            $DatosAdmin = $query1->fetch();

            $dataAd = [
                "ID" => $idup,
                "TITULO" => $tituloup,
                "INTRODUCCION" => $introcuccionup,
                "IMAGEN" => $rutaF,
                "FECHA" => $fechaNoti,
                "CONTENIDO" => $contenidoup

            ];

            if ($DatosAdmin['id_noticias'] != 1) {

                $DelAdmin = adminListModelssM::actualizar_NoticiaModel($dataAd);
                $url = SERVERURL."noticias/";


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
    public function deleteNoticiacontroller(){

        if(isset($_POST['idNotDel'])) {
            $code = $_POST['idNotDel'];
            $ad = $_POST['imagnNotiDel'];

            unlink($_SERVER['DOCUMENT_ROOT'].'/backend/'.$ad);



            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM noticias WHERE id_noticias='$code'");
            $adminData = $query1->fetch();
            if ($adminData['id_noticias']!= 1) {

                $DelAdmin = adminListModelssM::deleteNoticiamodel($code);
                $url = SERVERURL."noticias/";


            }
            return $urlLocation = '<script type="text/javascript"> window.location="'.$url.'"; </script>';
        }


    }



    /*----------------------OBRAS Y PROYECTOS---------------------------------*/
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
                            
                            
                            <td>'.$rows['estado'].'</td>

                            <td>
                                <form action="'.SERVERURL.'ajax/adminAjax.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
									<input  type="hidden" name="idObrasP-del" value="'.$rows["id_op"].'">
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
    public function datos_obrasPConteo_ontrolador($conteo){
        //return GestorsliderM::datos_Slider_modelo($codigo);
        return adminListModelssM::datos_ObrasPConteo_modelo($conteo);
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
                    $ruta = $_SERVER['DOCUMENT_ROOT'].'/backend/views/images/obrasProyectos/obrasP'.$aleatorio.".jpg";
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

                unlink($_SERVER['DOCUMENT_ROOT'].'/backend/'.$_POST["fotoAntiguoOP"]);
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

            if ($DatosAdmin['idslider'] != 1) {

                $DelAdmin = adminListModelssM::actualizar_ObrasPModel($dataAd);
                $url = SERVERURL."obrasProyectos/";


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

            unlink($_SERVER['DOCUMENT_ROOT'].'/backend/'.$_GET["$ad"]);



            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM obras_proy WHERE id_op='$code'");
            $adminData = $query1->fetch();
            if ($adminData['id_op']!= 1) {

                $DelAdmin = adminListModelssM::deleteObrasPmodel($code);
                $url = SERVERURL."obrasProyectos/";


            }
            return $urlLocation = '<script type="text/javascript"> window.location="'.$url.'"; </script>';
        }


    }


    /*-------------------------GOBERNANTE-----------------------------*/
    public function guardarGobernanteControllers()
    {

        if (isset($_POST["nombre"])) {

            $imagen = $_FILES["img"]["tmp_name"];
            //echo $imagen;
            /*---------------borrar----------------*/
            /*$borrar=glob("views/images/obrasProyectos/temp/*");
            foreach ($borrar as $file){
                unlink($file) ;
            }*/
            /*-------------------borrar-------------------------*/
            list($ancho, $alto) = getimagesize($imagen);


            $aleatorio = mt_rand(100, 999);
            $ruta = "views/images/gobernante/gobernante" . $aleatorio . ".jpg";
            $nuevo_ancho = 1920;
            $nuevo_alto = 1080;
            $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
            $origen = imagecreatefromjpeg($imagen);

            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
            imagejpeg($destino, $ruta);

            /*echo $_POST["nombre"];
            echo $_POST["apellido"];
            echo $_POST["dni"];
            echo $ruta;*/
            $datosController = array(
                "dnig" => $_POST["dni"],
                "nombreg" => $_POST["nombre"],
                "apellidog" => $_POST["apellido"],
                "cargog" => $_POST["cargo"],
                "mensajeg" => $_POST["mensaje"],
                "fechag" => $_POST["fecha"],
                "gestiong" => $_POST["fecha"],
                "rutag" => $ruta


            );

            $respuesta = adminListModelssM::guardarGobernanteModel($datosController);


            echo '<script>
                swal({
                      title: "¡OK!",
                      text: "¡Gobernante ha sido creado correctamente!",
                      type: "success",
                      confirmButtonText: "Cerrar",
                      closeOnConfirm: false
                },
                function(isConfirm){
                         if (isConfirm) {	   
                            window.location = "' . SERVERURL . 'obrasProyectos/";
                          } 
                });
                </script>';


        }


    }

    public function vistaGobernanteController($pagina, $registros)
    {

        $tabla = "";

        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        //------------contador de datos en la base de datos---------------------
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        $conexion = mainModels::conectar();

        $datos = $conexion->query("
        SELECT SQL_CALC_FOUND_ROWS * FROM gobernante WHERE idgobernante!='1'
         ORDER BY idgobernante DESC LIMIT $inicio,$registros
        ");

        $datos = $datos->fetchAll();

        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int)$total->fetchColumn();
        //total de numeros de paginas
        $Npaginas = ceil($total / $registros);

        /*-------------------------paginando en una lista---------------------------*/
        $tabla .= '<div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">ID</th>
                        <th class="text-center">Nombre completo</th>
                        <th class="text-center">DNI</th>
                        <th class="text-center">CARGO</th>
                        <th class="text-center">MENSAJE</th>
                        <th class="text-center">GESTION</th>
                        <th class="text-center">ELIMINAR</th>
                        <th class="text-center">VER</th>
                        <th class="text-center">ACTUALIZAR</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '<tr>
                            <td>' . $contador . '</td>
                            <td>' . $rows['idgobernante'] . '</td>
                            <td>' . $rows['nombre_gober'] ." ".$rows['apellido_gober']. '</td>
                            <td>' . $rows['dni_gobernante'] . '</td>
                            <td>' . $rows['cargo_gober'] . '</td>
                            <td>' . $rows['mensage'] . '</td>
                            <td>' . $rows['gestion'] . '</td>
                            <td>
                                <form action="' . SERVERURL . 'ajax/adminAjax.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="idGober-del" value="' . $rows["idgobernante"] . '">
									<input type="hidden" name="imagGober-del" value="' . $rows["rutaGobernante"] . '">
									<button type="submit" class="btn btn-danger btn-raised btn-xs">
										<i class="zmdi zmdi-delete"></i>
									</button>
									<span class="RespuestaAjax"></span>
								</form>
                            </td>
                            <td>
                                <a href="#obrasp' . $rows["idgobernante"] . '" data-toggle="modal" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-eye"></i>
                                </a>   
                                <div id="obrasp' . $rows["idgobernante"] . '" class="modal fade">
    
                                    <div class="modal-dialog modal-content">
        
                                        <div class="modal-header" style="border:1px solid #eee">
                                        
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                         <h3 class="modal-title">Nombre Apellido : ' . $rows["nombre_gober"] . " " . $rows["apellido_gober"] . '</h3>
                                         <h4 class="modal-title">Dni: ' . $rows["dni_gobernante"] . '  Cargo: ' . $rows["cargo_gober"] . '</h4>
                                        
                                    
                                        </div>
        
                                        <div class="modal-body" style="border:1px solid #eee">
                                    
                                            <img src="../' . $rows['rutaGobernante'] . '" width="100%" style="margin-bottom:20px">
                                            <p class="parrafoContenido">' . $rows['mensage'] . '</p>
                                    
                                        </div>
        
                                        <div class="modal-footer" style="border:1px solid #eee">
                                    
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    
                                        </div>
        
                                    </div>
        
                                </div>
                            </td>
                            <td>
                                <a href="' . SERVERURL . 'upGobernante/' . $rows['idgobernante'] . '" class="btn btn-primary btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>
                            
                        </tr>
                        ';

                $contador++;
            }
        } else {
            /*---------------------para eliminar el mensaje que muestra-----------------------*/
            if ($total >= 1) {
                $tabla .= '
            <tr>
                <td colspan="5"></td>
            
            </tr>
            ';
            } else {
                $tabla .= '
            <tr>
                <td colspan="5">No hay registros en el sistema</td>
            
            </tr>
            ';
            }

        }
        $tabla .= '</tbody></table></div>';


        /*.--------------PAGINADOR--------------------*/
        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= '<nav class="text-center">
                        <ul class="pagination pagination-sm">';

            if ($pagina == 1) {
                $tabla .= '<li class="disabled"><a ><i class="zmdi zmdi-arrow-left"></i></a></li>';
            } else {
                $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . ($pagina - 1) . '"><i class="zmdi zmdi-arrow-left"></i></a></li>';
            }

            for ($i = 1; $i <= $Npaginas; $i++) {

                if ($pagina == $i) {
                    $tabla .= '<li class="active"><a href="' . SERVERURL . 'gobernante/' . $i . '">' . $i . '</a></li>';
                } else {
                    $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . $i . '">' . $i . '</a></li>';


                }
            }

            if ($pagina == $Npaginas) {
                $tabla .= '<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
            } else {
                $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . ($pagina + 1) . '"><i class="zmdi zmdi-arrow-right"></i></a></li>';

            }

            $tabla .= '</ul></nav>';
        }


        return $tabla;


    }

    public function datos_Gobernante_controlador($codigo)
    {

        return adminListModelssM::datos_Gobernante_modelo($codigo);
    }
    public function datos_GobernanteConteo_controlador($conteo)
    {

        return adminListModelssM::datos_GobernanteConteo_modelo($conteo);
    }

    public function actualizarGobernanteController()
    {

        $rutaF = "";
        if (isset($_POST['nombreGoberUp'])) {

            if (isset($_FILES["imgGoberUp"]["tmp_name"])) {

                $nombre = $_POST['nombreGoberUp'];
                $apellido = $_POST['apellidoGoberUp'];
                $dni = $_POST['dniGoberUp'];
                $mensaje = $_POST['mensajeGoberUp'];
                $fechaGestion = $_POST['fechaGoberUp'];
                $cargo = $_POST['cargoGoberUp'];
                $idup = $_POST['idGoberUp'];

                $imagen = $_FILES["imgGoberUp"]["tmp_name"];
                /*-------------------borrar-------------------------*/
                list($ancho, $alto) = getimagesize($imagen);
                if ($ancho < 1920 || $alto < 1080) {
                    echo 0;
                } else {


                    $aleatorio = mt_rand(100, 999);
                    $ruta = $_SERVER['DOCUMENT_ROOT'].'/backend/views/images/gobernante/gobernante' . $aleatorio . ".jpg";
                    $rutaF = 'views/images/gobernante/gobernante'.$aleatorio.".jpg";

                    $nuevo_ancho = 1920;
                    $nuevo_alto = 1080;
                    $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
                    $origen = imagecreatefromjpeg($imagen);

                    imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

                    imagejpeg($destino, $ruta);
                }
            }

            if ($rutaF == "") {
                $rutaF = $_POST["fotoAntiguoGobernante"];
            } else {

                unlink($_SERVER['DOCUMENT_ROOT'] . '/backend/' . $_POST["fotoAntiguoGobernante"]);
            }


            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM gobernante WHERE idgobernante='$idup'");
            $DatosAdmin = $query1->fetch();


            $dataAd = [
                "ID" => $idup,
                "DNI" => $dni,
                "NOMBRE" => $nombre,
                "APELLIDO" => $apellido,
                "CARGO" => $cargo,
                "MENSAJE" => $mensaje,
                "GESTION" => $fechaGestion,
                "ruta" => $rutaF

            ];

            if ($DatosAdmin['idgobernante'] != 1) {

                $DelAdmin = adminListModelssM::actualizarGobernanteModel($dataAd);
                $url = SERVERURL . "gobernante/";


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

    public function deleteGobernantecontroller()
    {

        if (isset($_POST['idGober-del'])) {
            $code = $_POST['idGober-del'];
            $ad = $_POST['imagGober-del'];

            //unlink($_SERVER['DOCUMENT_ROOT'].'/MuniiFinall/'.$_GET["$ad"]);


            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM gobernante WHERE id_gobernante='$code'");
            $adminData = $query1->fetch();
            if ($adminData['id_gobernante'] != 1) {

                $DelAdmin = adminListModelssM::deleteGobernantemodel($code);
                $url = SERVERURL . "gobernante/";


            }
            return $urlLocation = '<script type="text/javascript"> window.location="' . $url . '"; </script>';
        }


    }


    /*.........................MISION Y VISION.....................*/
    public function guardarMisionVisionController()
    {
        if (isset($_POST["tmision"])) {


            $datosController = array(
                "tipomv" => $_POST["tmision"],
                "contenidomv" => $_POST["cmision"],

            );

            $respuesta = adminListModelssM::guardarMisionVision($datosController);


            echo '<script>
                swal({
                      title: "¡OK!",
                      text: "¡Mision y Vision ha sido creado correctamente!",
                      type: "success",
                      confirmButtonText: "Cerrar",
                      closeOnConfirm: false
                },
                function(isConfirm){
                         if (isConfirm) {	   
                            window.location = "' . SERVERURL . 'misionVision/";
                          } 
                });
                </script>';

        }

    }

    public function vistaMisionVisionController($pagina, $registros)
    {

        $tabla = "";

        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        //------------contador de datos en la base de datos---------------------
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        $conexion = mainModels::conectar();

        $datos = $conexion->query("
        SELECT SQL_CALC_FOUND_ROWS * FROM mision_vision WHERE id_mv!='1'
         ORDER BY id_mv DESC LIMIT $inicio,$registros
        ");

        $datos = $datos->fetchAll();

        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int)$total->fetchColumn();
        //total de numeros de paginas
        $Npaginas = ceil($total / $registros);

        /*-------------------------paginando en una lista---------------------------*/
        $tabla .= '<div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">ID</th>
                        <th class="text-center">TITULO</th>
                        <th class="text-center">CONTENIDO</th>
                        <th class="text-center">VER</th>
                        <th class="text-center">EDITAR</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '<tr>
                            <td>' . $contador . '</td>
                            <td>' . $rows['id_mv'] . '</td>
                            <td>' . $rows['tipo_mv'] . '</td>
                            <td>' . $rows['contenido_mv'] . '</td>
                            <td>
                                <a href="#misionVsion' . $rows["id_mv"] . '" data-toggle="modal" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-eye"></i>
                                </a>   
                                <div id="misionVsion' . $rows["id_mv"] . '" class="modal fade">
    
                                    <div class="modal-dialog modal-content">
        
                                        <div class="modal-header" style="border:1px solid #eee">
                                        
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                         <h3 class="modal-title">' . $rows["tipo_mv"] . '</h3>
                                        
                                    
                                        </div>
        
                                        <div class="modal-body" style="border:1px solid #eee">
                                    
                                            <p class="parrafoContenido">' . $rows['contenido_mv'] . '</p>
                                    
                                        </div>
        
                                        <div class="modal-footer" style="border:1px solid #eee">
                                    
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    
                                        </div>
        
                                    </div>
        
                                </div>
                            </td>
                            <td>
                                <a href="' . SERVERURL . 'upMision/' . $rows['id_mv'] . '" class="btn btn-primary btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>

                           
                            
                            
                        </tr>
                        ';

                $contador++;
            }
        } else {
            /*---------------------para eliminar el mensaje que muestra-----------------------*/
            if ($total >= 0) {
                $tabla .= '
            <tr>
                <td colspan="5"></td>
            
            </tr>
            ';
            } else {
                $tabla .= '
            <tr>
                <td colspan="5">No hay registros en el sistema</td>
            
            </tr>
            ';
            }

        }
        $tabla .= '</tbody></table></div>';


        /*.--------------PAGINADOR--------------------*/
        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= '<nav class="text-center">
                        <ul class="pagination pagination-sm">';

            if ($pagina == 1) {
                $tabla .= '<li class="disabled"><a ><i class="zmdi zmdi-arrow-left"></i></a></li>';
            } else {
                $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . ($pagina - 1) . '"><i class="zmdi zmdi-arrow-left"></i></a></li>';
            }

            for ($i = 1; $i <= $Npaginas; $i++) {

                if ($pagina == $i) {
                    $tabla .= '<li class="active"><a href="' . SERVERURL . 'gobernante/' . $i . '">' . $i . '</a></li>';
                } else {
                    $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . $i . '">' . $i . '</a></li>';


                }
            }

            if ($pagina == $Npaginas) {
                $tabla .= '<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
            } else {
                $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . ($pagina + 1) . '"><i class="zmdi zmdi-arrow-right"></i></a></li>';

            }

            $tabla .= '</ul></nav>';
        }


        return $tabla;


    }

    public function actualizarMVController()
    {


        if (isset($_POST['idMVUP'])) {


            $idmvup = $_POST['idMVUP'];
            $tipo = $_POST['tipoMVUP'];
            $contenido = $_POST['contMVUP'];


            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM mision_vision WHERE id_mv='$idmvup'");
            $DatosAdmin = $query1->fetch();


            $dataAd = [
                "ID" => $idmvup,
                "TIPO" => $tipo,
                "CONTENIDO" => $contenido
            ];

            if ($DatosAdmin['id_mv'] != 1) {

                $DelAdmin = adminListModelssM::actualizarMVModel($dataAd);
                $url = SERVERURL . "misionVision/";


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

    public function datos_MisionVision_controlador($codigo)
    {

        return adminListModelssM::datos_MisionVision_modelo($codigo);
    }

    /*------------------------PLAN DE DESARROLLO-----------------------------------------*/

    public function guardarPlanDController()
    {
        if (isset($_POST["titulopd"])) {


            $aleatorio = mt_rand(100, 999);
            //$rutaE = $_SERVER['DOCUMENT_ROOT'].'/backend/';
            $rutaA = 'adjuntos/PlanD'.$aleatorio.".PDF";

            $nombre_tem = $_FILES['archivo']['tmp_name'];
            move_uploaded_file($nombre_tem,$rutaA);


            $datosController = array(
                "pdtitulo" => $_POST["titulopd"],
                "pdcontenido" => $_POST["contenidopd"],
                "pdarchivo" => $rutaA

            );

            $respuesta = adminListModelssM::guardarPlanDesarrollo($datosController);


            echo '<script>
                swal({
                      title: "¡OK!",
                      text: "¡Mision y Vision ha sido creado correctamente!",
                      type: "success",
                      confirmButtonText: "Cerrar",
                      closeOnConfirm: false
                },
                function(isConfirm){
                         if (isConfirm) {	   
                            window.location = "' . SERVERURL . 'inicio/";
                          } 
                });
                </script>';

        }

    }

    public function vistaPlanDcontroller($pagina, $registros)
    {

        $tabla = "";

        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        //------------contador de datos en la base de datos---------------------
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        $conexion = mainModels::conectar();

        $datos = $conexion->query("
        SELECT SQL_CALC_FOUND_ROWS * FROM plan_desarrollo WHERE id_plan_desarrollo!='0'
         ORDER BY id_plan_desarrollo DESC LIMIT $inicio,$registros
        ");

        $datos = $datos->fetchAll();

        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int)$total->fetchColumn();
        //total de numeros de paginas
        $Npaginas = ceil($total / $registros);

        /*-------------------------paginando en una lista---------------------------*/
        $tabla .= '<div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">ID</th>
                        <th class="text-center">TITULO</th>
                        <th class="text-center">CONTENIDO</th>
                        <th class="text-center">DOCUMENTO</th>
                        <th class="text-center">ELIMINAR</th>
                        <th class="text-center">VER</th>
                        <th class="text-center">ACTUALIZAR</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '<tr>
                            <td>' . $contador . '</td>
                            <td>' . $rows['id_plan_desarrollo'] . '</td>
                            <td>' . $rows['titulo_plan'] . '</td>
                            <td>' . $rows['contenido_plan'] . '</td>
                            <td>
                            <a type="application/pdf"  target="_blank" href="'.SERVERURL.'controllers/archivos.php?id='.$rows['id_plan_desarrollo'].'">'.$rows['direc_plan'].'</a>
                            </td>
                            
                            
                            <td>
                                <a href="' . SERVERURL . 'index.php?action=planDesarrollo&idBorrar=' . $rows["id_plan_desarrollo"] . '">
                                    <button type="submit" class="btn btn-danger btn-raised btn-xs">
                                       <i class="zmdi zmdi-delete"></i>
                                    </button>
                                </a>
                            </td>
                            <td>
                                <a href="#planD' . $rows["id_plan_desarrollo"] . '" data-toggle="modal" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-eye"></i>
                                </a>   
                                <div id="planD' . $rows["id_plan_desarrollo"] . '" class="modal fade">
    
                                    <div class="modal-dialog modal-content">
        
                                        <div class="modal-header" style="border:1px solid #eee">
                                        
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                         <h3 class="modal-title">' . $rows["titulo_plan"] . '</h3>
                                        
                                    
                                        </div>
        
                                        <div class="modal-body" style="border:1px solid #eee">
                                    
                                            <p class="parrafoContenido">' . $rows['contenido_plan'] . '</p>
                                            <p class="parrafoContenido">' . $rows['direc_plan'] . '</p>
                                    
                                        </div>
        
                                        <div class="modal-footer" style="border:1px solid #eee">
                                    
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    
                                        </div>
        
                                    </div>
        
                                </div>
                            </td>
                            <td>
                                <a href="'.SERVERURL.'upplanDesarrollo/'.$rows['id_plan_desarrollo'].'" class="btn btn-primary btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>
                          
                            
                        </tr>
                        ';

                $contador++;
            }
        } else {
            /*---------------------para eliminar el mensaje que muestra-----------------------*/
            if ($total >= 1) {
                $tabla .= '
            <tr>
                <td colspan="5"></td>
            
            </tr>
            ';
            } else {
                $tabla .= '
            <tr>
                <td colspan="5">No hay registros en el sistema</td>
            
            </tr>
            ';
            }

        }
        $tabla .= '</tbody></table></div>';


        /*.--------------PAGINADOR--------------------*/
        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= '<nav class="text-center">
                        <ul class="pagination pagination-sm">';

            if ($pagina == 1) {
                $tabla .= '<li class="disabled"><a ><i class="zmdi zmdi-arrow-left"></i></a></li>';
            } else {
                $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . ($pagina - 1) . '"><i class="zmdi zmdi-arrow-left"></i></a></li>';
            }

            for ($i = 1; $i <= $Npaginas; $i++) {

                if ($pagina == $i) {
                    $tabla .= '<li class="active"><a href="' . SERVERURL . 'gobernante/' . $i . '">' . $i . '</a></li>';
                } else {
                    $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . $i . '">' . $i . '</a></li>';


                }
            }

            if ($pagina == $Npaginas) {
                $tabla .= '<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
            } else {
                $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . ($pagina + 1) . '"><i class="zmdi zmdi-arrow-right"></i></a></li>';

            }

            $tabla .= '</ul></nav>';
        }


        return $tabla;


    }

    public function borrarPlanDController()
    {

        $ids = $_POST["id-slider"];
        $rutas = $_POST["ruta-slider"];


        if (isset($_GET["idBorrar"])) {


            $datosController = $_GET["idBorrar"];

            $respuesta = adminListModelssM::borrarPlanDModel($datosController);

            if ($respuesta == "ok") {

                echo '<script>
                swal({
                        title: "¡OK!",
                        text: "¡El Plan de desarollo  ha sido Borrado correctamente!",
                        type: "success",
                        confirmButtonText: "Cerrar",
                        closeOnConfirm: false
                },
                function(isConfirm){
                            if (isConfirm) {	   
                            window.location = "' . SERVERURL . 'misionVision/";
                            } 
                });
                </script>';

            }
        }

    }

    public function datos_PlanDcontrolador($codigo)
    {
        return adminListModelssM::datos_PlanDmodelo($codigo);
    }

    public function actualizarPlanDController(){

        $rutaAc = "";
        if (isset($_POST['titulopdUP'])) {

            $idup = $_POST['idpdUP'];
            $titulo = $_POST['titulopdUP'];
            $contenido = $_POST['contenidopdUP'];

            if (isset($_FILES["archivoUP"]["tmp_name"])) {

                $nombre_tem = $_FILES['archivoUP']['tmp_name'];
                $aleatorio = mt_rand(100, 999);
                $rutaC = $_SERVER['DOCUMENT_ROOT'].'/backend/adjuntos/PlanD'.$aleatorio.".PDF";
                $rutaA = 'adjuntos/PlanD'.$aleatorio.".PDF";
                move_uploaded_file($nombre_tem,$rutaC);

            }


            if ($rutaAc == "") {
                $rutaAc = $_POST["archivoAntiguoPD"];
            } else {

                unlink($_SERVER['DOCUMENT_ROOT'].'/backend/'.$_POST["archivoAntiguoPD"]);
            }



            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM plan_desarrollo WHERE id_plan_desarrollo='$idup'");
            $DatosAdmin = $query1->fetch();


            $dataAd = [
                "ID" => $idup,
                "TITULO" => $titulo,
                "CONTENIDO" => $contenido,
                "RUTA" => $rutaA
            ];

            if ($DatosAdmin['id_plan_desarrollo'] != 1) {

                $DelAdmin = adminListModelssM::actualizarPlanDModel($dataAd);
                $url = SERVERURL."planDesarrollo/";


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

            return $urlLocation = '<script type="text/javascript"> window.location="'.$url.'"; </script>';
        }


    }


    /*---------------------------------------NOVEDADES-------------------------------------------------*/
    public function guardarNovedadesController()
    {
        if (isset($_POST["tituloNov"])) {



            $aleatorio = mt_rand(100, 999);
            //$rutaE = $_SERVER['DOCUMENT_ROOT'].'/backend/';
            $rutaA = 'adjuntos/novedades/Novedades'.$aleatorio.".PDF";

            $nombre_tem = $_FILES['archivoNov']['tmp_name'];
            move_uploaded_file($nombre_tem,$rutaA);


            $datosController = array(
                "pdtitulo" => $_POST["tituloNov"],
                "pdarchivo" => $rutaA

            );

            $respuesta = adminListModelssM::guardarNovedades($datosController);


            echo '<script>
                swal({
                      title: "¡OK!",
                      text: "¡Novedades ha sido creado correctamente!",
                      type: "success",
                      confirmButtonText: "Cerrar",
                      closeOnConfirm: false
                },
                function(isConfirm){
                         if (isConfirm) {	   
                            window.location = "'.SERVERURL.'novedades/";
                          } 
                });
                </script>';

        }

    }
    public function vistaNovedadescontroller($pagina, $registros)
    {

        $tabla = "";

        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        //------------contador de datos en la base de datos---------------------
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        $conexion = mainModels::conectar();

        $datos = $conexion->query("
        SELECT SQL_CALC_FOUND_ROWS * FROM novedades WHERE id_novedades!='0'
         ORDER BY id_novedades DESC LIMIT $inicio,$registros
        ");

        $datos = $datos->fetchAll();

        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int)$total->fetchColumn();
        //total de numeros de paginas
        $Npaginas = ceil($total / $registros);

        /*-------------------------paginando en una lista---------------------------*/
        $tabla .= '<div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">ID</th>
                        <th class="text-center">TITULO</th>
                        <th class="text-center">DOCUMENTO</th>
                        <th class="text-center">ELIMINAR</th>
                        <th class="text-center">VER</th>
                        <th class="text-center">ACTUALIZAR</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '<tr>
                            <td>' . $contador . '</td>
                            <td>' . $rows['id_novedades'] . '</td>
                            <td>' . $rows['titulo_nove'] . '</td>
                            <td>
                            <a type="application/pdf"  target="_blank" href="'.SERVERURL.'controllers/noveArchivo.php?id='.$rows['id_novedades'].'">'.$rows['direc_nove'].'</a>
                            </td>
                            <td>
                                <form action="'.SERVERURL.'ajax/adminAjax.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="idO-del" value="'.$rows["id_novedades"].'">
									<input type="hidden" name="imagO-del" value="'.$rows["direc_nove"].'">
									<button type="submit" class="btn btn-danger btn-raised btn-xs">
										<i class="zmdi zmdi-delete"></i>
									</button>
									<span class="RespuestaAjax"></span>
								</form>
                            </td>
                            <td>
                                <a href="#planD'.$rows["id_novedades"].'" data-toggle="modal" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-eye"></i>
                                </a>   
                                <div id="planD'.$rows["id_novedades"].'" class="modal fade">
    
                                    <div class="modal-dialog modal-content">
        
                                        <div class="modal-header" style="border:1px solid #eee">
                                        
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                         <h3 class="modal-title">' . $rows["titulo_nove"] . '</h3>
                                        
                                    
                                        </div>
        
                                        <div class="modal-body" style="border:1px solid #eee">
                                    
                                            <p class="parrafoContenido">' . $rows['direc_nove'] . '</p>
                                    
                                        </div>
        
                                        <div class="modal-footer" style="border:1px solid #eee">
                                    
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    
                                        </div>
        
                                    </div>
        
                                </div>
                            </td>
                            <td>
                                <a href="'.SERVERURL.'upNovedades/'.$rows['id_novedades'].'" class="btn btn-primary btn-raised btn-xs">
                                    <i class="zmdi zmdi-refresh"></i>
                                </a>
                            </td>
                          
                            
                        </tr>
                        ';

                $contador++;
            }
        } else {
            /*---------------------para eliminar el mensaje que muestra-----------------------*/
            if ($total >= 1) {
                $tabla .= '
            <tr>
                <td colspan="5"></td>
            
            </tr>
            ';
            } else {
                $tabla .= '
            <tr>
                <td colspan="5">No hay registros en el sistema</td>
            
            </tr>
            ';
            }

        }
        $tabla .= '</tbody></table></div>';


        /*.--------------PAGINADOR--------------------*/
        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= '<nav class="text-center">
                        <ul class="pagination pagination-sm">';

            if ($pagina == 1) {
                $tabla .= '<li class="disabled"><a ><i class="zmdi zmdi-arrow-left"></i></a></li>';
            } else {
                $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . ($pagina - 1) . '"><i class="zmdi zmdi-arrow-left"></i></a></li>';
            }

            for ($i = 1; $i <= $Npaginas; $i++) {

                if ($pagina == $i) {
                    $tabla .= '<li class="active"><a href="' . SERVERURL . 'gobernante/' . $i . '">' . $i . '</a></li>';
                } else {
                    $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . $i . '">' . $i . '</a></li>';


                }
            }

            if ($pagina == $Npaginas) {
                $tabla .= '<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
            } else {
                $tabla .= '<li ><a href="' . SERVERURL . 'gobernante/' . ($pagina + 1) . '"><i class="zmdi zmdi-arrow-right"></i></a></li>';

            }

            $tabla .= '</ul></nav>';
        }


        return $tabla;


    }
    public function datos_Novedadescontrolador($codigo)
    {
        return adminListModelssM::datos_Novedadesmodelo($codigo);
    }
    public function datos_NovedadesConteocontrolador($codigo)
    {
        return adminListModelssM::datos_NovedadesConteo_modelo($codigo);
    }
    public function actualizarNovedadesController(){

       // $rutaA = "";
        if (isset($_POST['titulopdUN'])) {

            $idup = $_POST['idpdUN'];
            $titulo = $_POST['titulopdUN'];

            if (isset($_FILES["archivoUN"]["tmp_name"])) {

                $nombre_tem = $_FILES['archivoUN']['tmp_name'];
                //$nombew=$_FILES['archivoUN']['name'];
                $aleatorio = mt_rand(100, 999);


                $rutaC = $_SERVER['DOCUMENT_ROOT'].'/backend/adjuntos/novedades/Novedades'.$aleatorio.".PDF";
                $rutaAC = 'adjuntos/novedades/Novedades'.$aleatorio.".PDF";

                move_uploaded_file($nombre_tem,$rutaC);

            }


           /* if ($rutaA == "") {
                $rutaA = $_POST["archivoAntiguoN"];
            } else {

                unlink($_SERVER['DOCUMENT_ROOT'].'/backend/'.$_POST["archivoAntiguoN"]);
            }*/



            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM novedades WHERE id_novedades='$idup'");
            $DatosAdmin = $query1->fetch();


            $dataAd = [
                "ID" => $idup,
                "TITULO" => $titulo,
                "RUTA" => $rutaAC,

            ];

            if ($DatosAdmin['id_novedades']!= 1) {

                $DelAdmin = adminListModelssM::actualizarNovedadesModel($dataAd);
                $url = SERVERURL."novedades/";


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

            return $urlLocation = '<script type="text/javascript"> window.location="'.$url.'"; </script>';
        }


    }

    public function deletenNovedadescontroller(){
        $code=$_POST['idO-del'];
        $adminLevel=$_POST['imagO-del'];

        $query1=mainModels::ejecutar_consulta_simple("SELECT * FROM novedades WHERE id_novedades='$code'");
        $adminData=$query1->fetch();
        if($adminData['id_novedades']!=1) {

            $DelAdmin = adminListModelssM::deleteNovedadesmodel($code);
            $url=SERVERURL."novedades/";


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







    /*--------------------------ORGANIGRAMA-----------------------------*/
    public function guardarOrganigramaController(){
        if (isset($_POST["tituloo"])){

            $imagen=$_FILES["img"]["tmp_name"];
            list($ancho,$alto)=getimagesize($imagen);


            $aleatorio = mt_rand(100, 999);
            $ruta = "views/images/organigrama/organigrama".$aleatorio.".jpg";
            $nuevo_ancho = 1920 ;
            $nuevo_alto = 1080 ;
            $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
            $origen = imagecreatefromjpeg($imagen);

            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
            imagejpeg($destino, $ruta);






            $datosController = array(
                "otitulo"=>$_POST["tituloo"],

                "oimagen" => $ruta

            );

            $respuesta = adminListModelssM::guardarOrganigramaModels($datosController);


            echo '<script>
                swal({
                      title: "¡OK!",
                      text: "¡Organigrama ha sido creado correctamente!",
                      type: "success",
                      confirmButtonText: "Cerrar",
                      closeOnConfirm: false
                },
                function(isConfirm){
                         if (isConfirm) {	   
                            window.location = "'.SERVERURL.'inicio/";
                          } 
                });
                </script>';

        }

    }
    public function vistaOrganigramacontroller($pagina,$registros){

        $tabla="";

        $pagina= (isset($pagina)&&$pagina>0) ?(int)$pagina:1;
        //------------contador de datos en la base de datos---------------------
        $inicio=($pagina>0) ?(($pagina*$registros)-$registros) :0;

        $conexion=mainModels::conectar();

        $datos=$conexion->query("
        SELECT SQL_CALC_FOUND_ROWS * FROM organigrama WHERE id_organigrama!='0'
         ORDER BY id_organigrama DESC LIMIT $inicio,$registros
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
                        <th class="text-center">IMAGEN</th>
                        <th class="text-center">VER</th>
                        <th class="text-center">ELIMINAR</th>
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
                            <td>'.$rows['id_organigrama'].'</td>
                            <td ><span maxlength="10">'.$rows['titulo_or'].'</span></td>
                            <td>'.$rows['imagen_or'].'</td>                         
                            <td>
                                <a href="#organi'.$rows["id_organigrama"].'" data-toggle="modal" class="btn btn-success btn-raised btn-xs">
                                    <i class="zmdi zmdi-eye"></i>
                                </a>   
                                <div id="organi'.$rows["id_organigrama"].'" class="modal fade">
    
                                    <div class="modal-dialog modal-content">
        
                                        <div class="modal-header" style="border:1px solid #eee">
                                        
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                         <h3 class="modal-title">'.$rows["titulo_or"].'</h3>
                                        
                                    
                                        </div>
        
                                        <div class="modal-body" style="border:1px solid #eee">
                                    
                                           <img src="../'.$rows['imagen_or'].'" width="100%" style="margin-bottom:20px">
                                         
                                    
                                        </div>
        
                                        <div class="modal-footer" style="border:1px solid #eee">
                                    
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    
                                        </div>
        
                                    </div>
        
                                </div>
                            </td>
                            ';

                $tabla.='<td>
							    <form action="'.SERVERURL.'ajax/adminAjax.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="id-del" value="'.$rows["id_organigrama"].'">
									<input type="hidden" name="imag-del" value="'.$rows["imagen_or"].'">
									<button type="submit" class="btn btn-danger btn-raised btn-xs">
										<i class="zmdi zmdi-delete"></i>
									</button>
									<span class="RespuestaAjax"></span>
								</form>
							</td>
							<td>
							    <a href="'.SERVERURL.'upOrganigrama/'.$rows['id_organigrama'].'" class="btn btn-success btn-raised btn-xs">
									<i class="zmdi zmdi-refresh"></i>
								</a>
                            </td>
                        </tr>';

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
                $tabla.='<li ><a href="'.SERVERURL.'gobernante/'.($pagina-1).'"><i class="zmdi zmdi-arrow-left"></i></a></li>';
            }

            for ($i=1;$i<=$Npaginas;$i++){

                if ($pagina==$i){
                    $tabla.='<li class="active"><a href="'.SERVERURL.'gobernante/'.$i.'">'.$i.'</a></li>';
                }
                else{
                    $tabla.='<li ><a href="'.SERVERURL.'gobernante/'.$i.'">'.$i.'</a></li>';


                }
            }

            if ($pagina==$Npaginas){
                $tabla.='<li class="disabled"><a ><i class="zmdi zmdi-arrow-right"></i></a></li>';
            }else{
                $tabla.='<li ><a href="'.SERVERURL.'gobernante/'.($pagina+1).'"><i class="zmdi zmdi-arrow-right"></i></a></li>';

            }

            $tabla.='</ul></nav>';
        }



        return  $tabla;



    }

    public function deleteOrganigramacontroller(){
        $code=$_POST['id-del'];
        $adminLevel=$_POST['rImag-del'];




            $query1=mainModels::ejecutar_consulta_simple("SELECT * FROM organigrama WHERE id_organigrama='$code'");
            $adminData=$query1->fetch();
        if($adminData['id_organigrama']!=1) {

            $DelAdmin = adminListModelssM::deleteOrganigramamodel($code);
            $url=SERVERURL."organigrama/";


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
    public function datos_OrganigramaConteo_controlador($conteo)
    {

        return adminListModelssM::datos_OrganigramaConteo_modelo($conteo);
    }


    public function datos_administrador_controlador($codigo){

        return adminListModelssM::datos_administrador_modelo($codigo);
    }

    public function actualizarOrganigramaController(){

        $rutaF="";
        if (isset($_POST['titulooup'])) {

            if (isset($_FILES["imgOUP"]["tmp_name"])) {

                $tituloup = $_POST['titulooup'];
                $idup = $_POST['idoup'];


                $imagen = $_FILES["imgOUP"]["tmp_name"];
                /*-------------------borrar-------------------------*/
                list($ancho, $alto) = getimagesize($imagen);
                if ($ancho < 1920 || $alto < 1080) {
                    echo 0;
                } else {


                    $aleatorio = mt_rand(100, 999);
                    $ruta = $_SERVER['DOCUMENT_ROOT'].'/backend/views/images/organigrama/organigrama' . $aleatorio . ".jpg";
                    $rutaF = 'views/images/organigrama/organigrama' . $aleatorio . ".jpg";

                    $nuevo_ancho = 1920;
                    $nuevo_alto = 1080;
                    $destino = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
                    $origen = imagecreatefromjpeg($imagen);

                    imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

                    imagejpeg($destino, $ruta);
                }
            }

            if ($rutaF == "") {
                $rutaF = $_POST["fotoAntiguo"];
            } else {

                unlink($_SERVER['DOCUMENT_ROOT'] . '/backend/' . $_POST["fotoAntiguo"]);
            }

            $query1 = mainModels::ejecutar_consulta_simple("SELECT * FROM organigrama WHERE id_organigrama='$idup'");
            $DatosAdmin = $query1->fetch();

            $dataAd = [
                "ID" => $idup,
                "TITULO" => $tituloup,
                "IMAGEN" => $rutaF

            ];

            if ($DatosAdmin['id_organigrama'] != 1) {

                $DelAdmin = adminListModelssM::actualizarOrganigramaModel($dataAd);
                $url = SERVERURL . "organigrama/";


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






}