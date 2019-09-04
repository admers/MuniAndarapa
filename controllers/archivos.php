<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php



include '../models/config.php';
include '../core/configGeneral.php';


$db=new Conect_MySql();
$sql = "SELECT * FROM plan_desarrollo WHERE id_plan_desarrollo=".$_GET['id'];
$query = $db->execute($sql);
if($datos=$db->fetch_row($query)){
    if($datos['direc_plan']==""){?>
        <p>NO tiene archivos</p>
    <?php }else{
       header('content-type: application/pdf');
       readfile(SERVERURL.$datos['direc_plan']);



    }
}



?>
</body>
</html>
