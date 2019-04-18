<?php
    $mysql_host		= "localhost";
    $mysql_user		= "root";
    $mysql_password	= "";
    $mysql_database	= "pulc";
    
    $conexion = mysqli_connect( $mysql_host, $mysql_user, $mysql_password, $mysql_database ); 
    
    if (  mysqli_connect_errno($conexion)  )
    {
	echo "Fallo en conexion a MySQL";
    }
    
    mysqli_query($conexion,"SET NAMES 'utf8'");
    echo"Se conecto";
?>