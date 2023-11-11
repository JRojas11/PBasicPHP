<?php
include("../../bd.php");

if(isset($_GET["txtID"])){
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:""; // almaceno el ID
    $sentencia=$conexion->prepare("DELETE FROM puestos WHERE idpuesto=:id"); // Preparamos la sentencia SQL
    $sentencia->bindParam(":id", $txtID); // Paso el parametro de borrado
    $sentencia->execute(); // Ejecuto
    $mensaje="Registro Eliminado";
    header("location:index.php?mensaje=".$mensaje); // Redirecciono
}

$sentencia=$conexion->prepare("SELECT *FROM puestos");
$sentencia->execute();
$lista_puestos=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../templates/header.php");?>
  
<br/>

<div class="card">
    <div class="card-header">
    <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Puesto</a>
    
    </div>
    <div class="card-body">
     
    <div class="table-responsive-sm">
    <table class="table" id="tabla_id">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Puesto</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach($lista_puestos as $registro) {   ?>
            
            <tr class="">
                <td scope="row"> <?php echo $registro["idpuesto"]; ?> </td>
                <td> <?php echo $registro["nombredelpuesto"]; ?> </td>
               
                <td>
                
                <a class="btn btn-success" href="editar.php?txtID=<?php echo $registro["idpuesto"]; ?>" role="button">Editar</a>

                <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro["idpuesto"]; ?>);" role="button">Eliminar</a>

                </td>
            </tr>
            
            <?php }?>
            
        </tbody>
    </table>
</div>
    

     </div>

</div>

<?php include("../../templates/footer.php");?>
 