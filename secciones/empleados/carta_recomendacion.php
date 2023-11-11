<?php
include("../../bd.php");

if(isset($_GET["txtID"])){
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:""; // almaceno el ID

    $sentencia=$conexion->prepare("SELECT *,(SELECT nombredelpuesto FROM puestos WHERE puestos.idpuesto=empleados.idpuesto limit 1) as puesto FROM empleados WHERE id=:id"); // Preparamos la sentencia SQL
    
    $sentencia->bindParam(":id", $txtID); // Paso el parametro de borrado
    $sentencia->execute(); // Ejecuto

    $registro=$sentencia->fetch(PDO::FETCH_LAZY);

    $nombre=$registro["nombre"];
    $apellido=$registro["apellido"];
    $foto=$registro["foto"];
    $cv=$registro["cv"];
    $idpuesto=$registro["idpuesto"];
    $puesto=$registro["puesto"];
    
    $fechadeingreso=$registro["fechadeingreso"];
    
    $nombreCompleto=$nombre." ".$apellido;

    $fechaInicio=new DateTime($fechadeingreso);
    $fechaFin= new DateTime(date("Y-m-d"));
    $diferencia=date_diff($fechaInicio,$fechaFin);

}
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta de Recomendacion</title>
</head>
<body>
    <h1>Carta de Recomendacion Laboral</h1>
    <br/><br/>
    San Miguel de Tucumán a <strong><?php echo date("d M Y"); ?></strong>
    <br/><br/>
    A quien pueda interesar:
    <br/><br/>
    Reciba un cordial y respetuoso saludo.
    <br/><br/>
    humo humo humo ..... que Sr(a) <strong> <?php echo $nombreCompleto; ?></strong> 
    <br/><br/>
    humo humo humo ..... trabajo durante <strong> <?php echo $diferencia->y;?> año(s) </strong>
    <br/><br/>
    humo humo humo ..... en el puesto de <strong> <?php echo $puesto;?></strong>
    <br/><br/><br/><br/><br/><br/><br/><br/>
    Atentamente:
    <br/><br/>
    Ing. Homero Simpson 
    
</body>
</html>

<?php
$HTML=ob_get_clean();

require_once("../../libs/autoload.inc.php");
use Dompdf\Dompdf;
$dompdf=new Dompdf();
$opciones=$dompdf->getOptions();
$opciones->set(array("isRemoteEnabled" =>true));

$dompdf->setOptions($opciones);
$dompdf->loadHTML($HTML);
$dompdf->setPaper("letter");
$dompdf->render();
$dompdf->stream("archivo.pdf", array("Attachment"=>false));

?>