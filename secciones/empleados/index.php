<?php
include("../../bd.php");

if(isset($_GET["txtID"])){
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:""; // almaceno el ID
    
    //Archivo relacionado con el empleado

    //Busco en la BD los archivos que coincida con el ID a borrar
    $sentencia=$conexion->prepare("SELECT foto,cv FROM empleados WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $registro_recuperado=$sentencia->fetch(PDO::FETCH_LAZY);

    //Controlo si existe FOTO
    if(isset($registro_recuperado ["foto"]) && $registro_recuperado["foto"]!=""){
        if(file_exists("./".$registro_recuperado["foto"])){
                unlink("./".$registro_recuperado["foto"]);

        }
    }
    //Controlo si existe CV
    if(isset($registro_recuperado ["cv"]) && $registro_recuperado["cv"]!=""){
        if(file_exists("./".$registro_recuperado["cv"])){
                unlink("./".$registro_recuperado["cv"]);

        }
    }

    // Preparamos la sentencia SQL
    $sentencia=$conexion->prepare("DELETE FROM empleados WHERE id=:id"); 
    // Paso el parametro de borrado
    $sentencia->bindParam(":id", $txtID); 
    // Ejecuto
    $sentencia->execute(); 
    // Redirecciono
    //header("location:index.php"); 
    $mensaje="Empleado Eliminado";
    header("location:index.php?mensaje=".$mensaje); 
    
}

$sentencia=$conexion->prepare("SELECT *,
(SELECT nombredelpuesto FROM puestos WHERE puestos.idpuesto=empleados.idpuesto limit 1) as puesto
FROM empleados");
$sentencia->execute();
$lista_empleados=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../templates/header.php");?>

<br/>
<h1>Empleados </h1>
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Empleado</a>
    </div>

    
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Foto</th>
                        <th scope="col">CV</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Fecha Ingreso</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach($lista_empleados as $registro) {?>

                        <tr class="">
                         
                            <td><?php echo $registro["id"]; ?> </td>
                            <td><?php echo $registro["nombre"]; ?>
                                <?php echo $registro["apellido"]; ?> </td>

                            <td> <img width="50"
                                src="<?php echo $registro["foto"];?>" 
                                class="img-fluid rounded" alt="" />
                            </td>

                            <td>
                                <a href="<?php echo $registro["cv"]; ?>">
                                <?php echo $registro["cv"]; ?>
                                </a>
                            </td>
                            <td><?php echo $registro["puesto"]; ?> </td>
                            <td><?php echo $registro["fechadeingreso"]; ?> </td>

                            <td>  <a class="btn btn-primary" href="carta_recomendacion.php?txtID=<?php echo $registro["id"]; ?>" role="button">Carta</a> | 
                            <a class="btn btn-success" href="editar.php?txtID=<?php echo $registro["id"]; ?>" role="button">Editar</a> |

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
 