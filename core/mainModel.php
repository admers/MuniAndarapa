<?php

if($AjaxRequest){
    require_once "../core/configAPP.php";
}else{
    require_once "./core/configAPP.php";
}

class mainModels{

    public function conectar(){
        $enlace = new PDO(SGBD,USER,PASS);
        //$enlace = new PDO("mysql:host=localhost;dbname=muniandarapa","root","cardenas");
        //var_dump($enlace);
        return $enlace;
    }
    public function ejecutar_consulta_simple($consulta){
        $respuesta = self::conectar()->prepare($consulta);
        $respuesta->execute();
        return $respuesta;
    }
    /*----------  Funcion para encriptar claves  -------------------------------------*/
    public function encryption($string){
        $output=FALSE;
        $key=hash('sha256', SECRET_KEY);
        $iv=substr(hash('sha256', SECRET_IV), 0, 16);
        $output=openssl_encrypt($string, METHOD, $key, 0, $iv);
        $output=base64_encode($output);
        return $output;
    }
    /*-----------------------------GENERAR CODIGO ALEATORIO-------------------------------------------*/
    protected function generar_codigo_aleatorio($letra,$longitud,$num){
        for ($i=1;$i<=$longitud;$i++) {
            $numero = rand(0,9);
            $letra.=$numero;
        }
        return $letra.$num;
    }
    /*------------------------------------LIMPIAR CADENA-------------------------------------------*/
    protected function limpiar_cadena($cadena){
        $cadena=trim($cadena);
        $cadena=stripslashes($cadena);
        $cadena=str_ireplace("<script>","",$cadena);
        return $cadena;
    }
    /*----------  Funcion para desencriptar claves ----------------------------------*/
    public function decryption($string){
        $key=hash('sha256', SECRET_KEY);
        $iv=substr(hash('sha256', SECRET_IV), 0, 16);
        $output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
        return $output;
    }


    protected function sweet_alert($datos){
        if($datos['Alerta']=="simple"){
            $alerta="
					<script>
						swal(
						  '".$datos['Titulo']."',
						  '".$datos['Texto']."',
						  '".$datos['Tipo']."'
						);
					</script>
				";
        }elseif($datos['Alerta']=="recargar"){
            $alerta="
					<script>
						swal({
						  title: '".$datos['Titulo']."',
						  text: '".$datos['Texto']."',
						  type: '".$datos['Tipo']."',
						  confirmButtonText: 'Aceptar'
						}).then(function () {
							location.reload();
						});
					</script>
				";
        }elseif($datos['Alerta']=="limpiar"){
            $alerta="
					<script>
						swal({
						  title: '".$datos['Titulo']."',
						  text: '".$datos['Texto']."',
						  type: '".$datos['Tipo']."',
						  confirmButtonText: 'Aceptar'
						}).then(function () {
							$('.FormularioAjax')[0].reset();
						});
					</script>
				";
        }
        return $alerta;
    }


}
$a=new mainModels();
$a->conectar();