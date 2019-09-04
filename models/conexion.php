<?
class Conexion{
    
    public function conectar(){


        try {
            $enlace = new PDO("mysql:host=localhost;dbname=muniandarapa", "root", "cardenas");
            //var_dump($enlace);
            return $enlace;
        }catch (PDOException $e){
            echo 'Caught exception: ', $e->getMessage(), "\n";
            die();
        }

    }
}