<?php
include("../../bd.php");


if($_POST){

  $nombre=(isset($_POST["nombre"])?$_POST["nombre"]:"");
  $apellido=(isset($_POST["apellido"])?$_POST["apellido"]:"");
 
  $foto=(isset($_FILES["foto"]["name"])?$_FILES["foto"]["name"]:"");
  $cv=(isset($_FILES["cv"]["name"])?$_FILES["cv"]["name"]:"");
 
  $idpuesto=(isset($_POST["idpuesto"])?$_POST["idpuesto"]:"");
  $fechadeingreso=(isset($_POST["fechadeingreso"])?$_POST["fechadeingreso"]:"");

  //preparo el INSERT
  $sentencia=$conexion->prepare("INSERT INTO empleados(nombre,apellido,foto,cv,idpuesto,fechadeingreso) VALUES (:nombre,:apellido,:foto,:cv,:idpuesto,:fechadeingreso)");


 $sentencia->bindParam(":nombre",$nombre);
 $sentencia->bindParam(":apellido",$apellido);

$fecha_=new DateTime();

$nombreArchivo_foto=($foto!="")?$fecha_->getTimestamp()."_".$_FILES["foto"]["name"]:"";
$tmp_foto=$_FILES["foto"]["tmp_name"];
if($tmp_foto!=""){
  move_uploaded_file($tmp_foto,"./".$nombreArchivo_foto);
}
$sentencia->bindParam(":foto",$nombreArchivo_foto);

$nombreArchivo_cv=($cv!="")?$fecha_->getTimestamp()."_".$_FILES["cv"]["name"]:"";
$tmp_cv=$_FILES["cv"]["tmp_name"];
if($tmp_cv!=""){
  move_uploaded_file($tmp_cv,"./".$nombreArchivo_cv);
}
 $sentencia->bindParam(":cv",$nombreArchivo_cv);
 
 $sentencia->bindParam(":idpuesto",$idpuesto);
 $sentencia->bindParam(":fechadeingreso",$fechadeingreso);

$sentencia->execute();

$mensaje="Empleado Agregado";
header("location:index.php?mensaje=".$mensaje); 
}

$sentencia=$conexion->prepare("SELECT *FROM puestos");
$sentencia->execute();
$lista_puestos=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../templates/header.php");?>
  
<br/>   
<div class="card">
    <div class="card-header">
        Datos del Empleado
    </div>
    <div class="card-body">
     <form action="" method="post" enctype="multipart/form-data">

     <div class="mb-3">
       <label for="nombre" class="form-label">Nombre</label>
       <input type="text"
         class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Nombre">
       
     </div>
     
     <div class="mb-3">
       <label for="apellido" class="form-label">Apellido</label>
       <input type="text"
         class="form-control" name="apellido" id="apellido" aria-describedby="helpId" placeholder="Apellido">
       
     </div>

     <div class="mb-3">
       <label for="foto" class="form-label">Foto</label>
       <input type="file"

         class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="Foto">
       
        </div>

        <div class="mb-3">
          <label for="cv" class="form-label">CV(PDF)</label>
          <input type="file"
            class="form-control" name="cv" id="cv" aria-describedby="helpId" placeholder="cv">
          
        </div>

        <div class="mb-3">
            <label for="" class="form-label">Puesto:</label>
            <select class="form-select form-select-lg" name="idpuesto" id="idpuesto">
                <?php foreach($lista_puestos as $registro) { ?>
                    <option value="<?php echo $registro["idpuesto"];?>">
                    <?php echo $registro["nombredelpuesto"];?></option>
                <?php }?>
            </select>
        </div>

        <div class="mb-3">
         
          <label for="fechadeingreso" class="form-label">Fecha de Ingreso</label>
          <input type="date" class="form-control" name="fechadeingreso" id="fechadeingreso"   aria-describedby="emailHelpId" placeholder="Fecha de ingreso del empleado">
          
        </div>
        <button type="submit" class="btn btn-success">Agregar Registro</button>
        
          <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
          </form>
     
    </div>

   
    </div>


<?php include("../../templates/footer.php");?>
 