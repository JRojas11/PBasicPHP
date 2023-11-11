<?php
include("../../bd.php");

if(isset($_GET["txtID"])){
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:""; // almaceno el ID
    $sentencia=$conexion->prepare("SELECT *FROM empleados WHERE id=:id"); // Preparamos la sentencia SQL
    $sentencia->bindParam(":id", $txtID); // Paso el parametro de borrado
    $sentencia->execute(); // Ejecuto

    $registro=$sentencia->fetch(PDO::FETCH_LAZY);
  
    $nombre=$registro["nombre"];
    $apellido=$registro["apellido"];
    $foto=$registro["foto"];
    $cv=$registro["cv"];
    $idpuesto=$registro["idpuesto"];
    $fechadeingreso=$registro["fechadeingreso"];
    
    $sentencia->bindParam(":nombre",$nombre);
    $sentencia->bindParam(":apellido",$apellido);
    $sentencia->bindParam(":foto",$foto);
    $sentencia->bindParam(":cv",$cv);
    $sentencia->bindParam(":idpuesto",$idpuesto);
    $sentencia->bindParam(":fechadeingreso",$fechadeingreso);

    $sentencia=$conexion->prepare("SELECT *FROM puestos");
    $sentencia->execute();
    $lista_puestos=$sentencia->fetchAll(PDO::FETCH_ASSOC);

}


if($_POST){

    $txtID=(isset($_POST["txtID"]))?$_POST["txtID"]:""; // almaceno el ID
    $nombre=(isset($_POST["nombre"])?$_POST["nombre"]:"");
    $apellido=(isset($_POST["apellido"])?$_POST["apellido"]:"");  
    $idpuesto=(isset($_POST["idpuesto"])?$_POST["idpuesto"]:"");
    $fechadeingreso=(isset($_POST["fechadeingreso"])?$_POST["fechadeingreso"]:"");
  
    //preparo el INSERT
    $sentencia=$conexion->prepare("UPDATE empleados SET
        nombre=:nombre,
        apellido=:apellido,
        idpuesto=:idpuesto,
        fechadeingreso=:fechadeingreso
    WHERE id=:id "); 
    
  /*  empleados(nombre,apellido,foto,cv,idpuesto,fechadeingreso) VALUES (:nombre,:apellido,:foto,:cv,:idpuesto,:fechadeingreso)");
  
*/  
   $sentencia->bindParam(":nombre",$nombre);
   $sentencia->bindParam(":apellido",$apellido);

   $sentencia->bindParam(":idpuesto",$idpuesto);
   $sentencia->bindParam(":fechadeingreso",$fechadeingreso);
   $sentencia->bindParam(":id",$txtID);
   
   $sentencia->execute();

   //Tratamiento para fotos

$foto=(isset($_FILES["foto"]["name"])?$_FILES["foto"]["name"]:"");
   
$fecha_=new DateTime();

$nombreArchivo_foto=($foto!="")?$fecha_->getTimestamp()."_".$_FILES["foto"]["name"]:"";
$tmp_foto=$_FILES["foto"]["tmp_name"];
if($tmp_foto!=""){
     move_uploaded_file($tmp_foto,"./".$nombreArchivo_foto);
     $sentencia=$conexion->prepare("SELECT foto FROM empleados WHERE id=:id");
     $sentencia->bindParam(":id",$txtID);
     $sentencia->execute();
     $registro_recuperado=$sentencia->fetch(PDO::FETCH_LAZY);
 
     //Controlo si existe FOTO
     if(isset($registro_recuperado ["foto"]) && $registro_recuperado["foto"]!=""){
         if(file_exists("./".$registro_recuperado["foto"])){
                 unlink("./".$registro_recuperado["foto"]);
 
         }
     }
     $sentencia=$conexion->prepare("UPDATE empleados SET foto=:foto WHERE id=:id "); 
     $sentencia->bindParam(":foto",$nombreArchivo_foto);
     $sentencia->bindParam(":id",$txtID);
     $sentencia->execute();
  
}
   

$cv=(isset($_FILES["cv"]["name"])?$_FILES["cv"]["name"]:"");

$nombreArchivo_cv=($cv!="")?$fecha_->getTimestamp()."_".$_FILES["cv"]["name"]:"";
$tmp_cv=$_FILES["cv"]["tmp_name"];
if($tmp_cv!=""){
    move_uploaded_file($tmp_cv,"./".$nombreArchivo_cv);

    $sentencia=$conexion->prepare("SELECT cv FROM empleados WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $registro_recuperado=$sentencia->fetch(PDO::FETCH_LAZY);

     //Controlo si existe CV
    if(isset($registro_recuperado ["cv"]) && $registro_recuperado["cv"]!=""){
        if(file_exists("./".$registro_recuperado["cv"])){
                unlink("./".$registro_recuperado["cv"]);
        }
    }

    $sentencia=$conexion->prepare("UPDATE empleados SET cv=:cv WHERE id=:id "); 
    $sentencia->bindParam(":cv",$nombreArchivo_cv);
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
}
   
$mensaje="Empleado Actualizado";
header("location:index.php?mensaje=".$mensaje); 
}  


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
        <label for="txtID" class="form-label">ID: </label>
        <input type="text"
            value="<?php echo $txtID; ?>"
            class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId">
    </div>

     <div class="mb-3">
       <label for="nombre" class="form-label">Nombre</label>
       <input type="text"
           value="<?php echo $nombre; ?>"
           class="form-control" name="nombre" id="nombre" aria-describedby="helpId">
     </div>
     
     <div class="mb-3">
       <label for="apellido" class="form-label">Apellido</label>
       <input type="text"
           value="<?php echo $apellido; ?>"
           class="form-control" name="apellido" id="apellido" aria-describedby="helpId">
       
     </div>

    <div class="mb-3">
       <label for="foto" class="form-label">Foto</label>
       <br/>
       <img width="100"
            src="<?php echo $foto;?>" 
            class="rounded" alt="" />
            <br/><br/>
       <input type="file"
       class="form-control" name="foto" id="foto" aria-describedby="helpId">
       
    </div>

        <div class="mb-3">
          <label for="cv" class="form-label">CV(PDF)</label>
          <br/>
          <a href="<?php echo $cv; ?>"><?php echo $cv; ?> </a>
          <input type="file"
            class="form-control" name="cv" id="cv" aria-describedby="helpId">
          
        </div>

        <div class="mb-3">
            <label for="idpuesto" class="form-label">Puesto:</label>
           
            <select class="form-select form-select-lg" name="idpuesto" id="idpuesto">
                <?php foreach($lista_puestos as $registro) { ?>

                    <option <?php echo($idpuesto==$registro["idpuesto"])?"selected":"" ?> value="<?php echo $registro["idpuesto"];?>">
                    <?php echo $registro["nombredelpuesto"];?></option>
                <?php }?>
            </select>
        </div>

        <div class="mb-3">
         
          <label for="fechadeingreso" class="form-label">Fecha de Ingreso</label>
          <input value="<?php echo $fechadeingreso;?>" type="date" class="form-control" name="fechadeingreso" id="fechadeingreso"   aria-describedby="emailHelpId">
          
        </div>
        <button type="submit" class="btn btn-success">Actualizar Registro</button>
        
          <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
          </form>
     
    </div>

   
    </div>


<?php include("../../templates/footer.php");?>
 