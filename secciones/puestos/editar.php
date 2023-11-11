<?php
include("../../bd.php");

if(isset($_GET["txtID"])){
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:""; // almaceno el ID
    $sentencia=$conexion->prepare("SELECT *FROM puestos WHERE idpuesto=:id"); // Preparamos la sentencia SQL
    $sentencia->bindParam(":id", $txtID); // Paso el parametro de borrado
    $sentencia->execute(); // Ejecuto

    $registro=$sentencia->fetch(PDO::FETCH_LAZY);
    $nombredelpuesto=$registro["nombredelpuesto"];
}

if($_POST){
    print_r($_POST);
    
    $txtID=(isset($_POST["txtID"]))?$_POST["txtID"]:"";
    $nombredelpuesto=(isset($_POST["nombredelpuesto"])?$_POST["nombredelpuesto"]:"");
    
    $sentencia=$conexion->prepare("UPDATE puestos SET nombredelpuesto=:nombredelpuesto WHERE idpuesto=:id");
    
    $sentencia->bindParam(":nombredelpuesto",$nombredelpuesto);
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $mensaje="Puesto Actualizado";
    header("location:index.php?mensaje=".$mensaje); // Redirecciono
}

?>


<?php include("../../templates/header.php");?>
  
<br/>

<div class="card">
    <div class="card-header">
        Puestos
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
              <label for="txtID" class="form-label">ID: </label>
              <input type="text"
                value="<?php echo $txtID; ?>"
                class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID">
            </div>

            <div class="mb-3">
              <label for="nombredelpuesto" class="form-label">Nombre del puesto</label>
              <input type="text"
                value="<?php echo $nombredelpuesto; ?>"
                class="form-control" name="nombredelpuesto" id ="nombredelpuesto" aria-describedby="helpId" placeholder="Nombre del puesto">
            </div>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>

    </div>
    <div class="card-footer text-muted"> </div>
    
</div>



<?php include("../../templates/footer.php");?>
