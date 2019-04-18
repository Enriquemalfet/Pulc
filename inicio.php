<?php
    
    session_start();  
    
    if (  !isset($_SESSION['usuario'])  )
    {
	header("location:index.html");
    }
    else
	{
	    $usuario = $_SESSION['usuario'] ;
	}
        
    if (  isset($_SESSION['mensaje'])  )
    {
        $mensaje_usuario = $_SESSION['mensaje'] ;	
    }
    else
        {
	    $mensaje_usuario = "";
        }
        
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>Pulc</title>
    <meta name="description" content="Online"/>
    
</head>

<body >


<?php
    
		$usuario	= $_SESSION['usuario'];
		$almacen	= $_SESSION['almacen'];
		require("conexion.php");
		
        echo "<br>";
          
$q_dispositivo  = mysqli_query($conexion, "SELECT * FROM dispositivos WHERE status = '1' order by rand() LIMIT 15" );
                        $fila_disp  = mysqli_fetch_array($q_dispositivo) ;
                        $id_disp = $fila_disp['idDispositivos'];
                        $stats   = $fila_disp['Status'];

    echo "<br>";  
    echo "esto tiene id",$id_disp;
    echo "<br>";  
    echo "esto tiene el query $stats";
    echo "<br>";    

    // INFORMACION GENERAL
                            echo "<font size = '3' face = 'Arial'>";
                            echo "<b>Pulc :</b> Gestion de preguntas ";
                            echo "</font>";
                       
?>

</body>
</html> 