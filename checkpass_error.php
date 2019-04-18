<?php      
    if (  isset($_POST['usuario'])  and  isset($_POST['clave'])  and strlen($_POST['usuario'])>1 and strlen($_POST['clave'])>1  )   
    {  
        $usuario    = $_POST['usuario'];    // Recibe usuario
        $clave      = $_POST['clave'];      // Recibe clave
        
        require("conexion.php");
        
        $usuario    = mysqli_real_escape_string($conexion, $_POST['usuario']);  // Prevenir inyección SQL mi estimado AS & AL
        $clave      = mysqli_real_escape_string($conexion, $_POST['clave']);    
        
        $usuario    = trim($usuario);
        $clave      = trim($clave);   
         
         
        $q_usuario  = "
                        SELECT * FROM usuarios
                        WHERE usuario = '$usuario'
                     ";
        $rq_usuario     = mysqli_query($conexion,$q_usuario);
        $nrq_usuario    = mysqli_num_rows($rq_usuario); 
        if (  $nrq_usuario !==  1  )   // Usuario NO EXISTE (0 filas encontradas en tabla)
        {
            header("location:password_fail_verificar_usuario.php");  // Mostrar esta pantalla si el usuario NO existe en la BD
        }
        else    // EL USUARIO EXISTE
            {
                // Obtener los datos del usuario
                $registro_usuario           = mysqli_fetch_array($rq_usuario);
                $clave_usuario_bd           = $registro_usuario['clave'];
                $status_usuario_bd          = $registro_usuario['status'];
                $n_intentos_acceso_bd       = $registro_usuario['n_intentos_acceso'];
                $fecha_intento_acceso       = $registro_usuario['fecha_intento_acceso'];
                $fecha_actualizacion_clave  = $registro_usuario['fecha_actualizacion_clave'];
                $almacen                    = $registro_usuario['almacen'];                
                
                if (  $status_usuario_bd == 2  )  //  Si Status de la cuenta = 2, entonces la cuenta está suspendida
                {
                    header("location:password_fail_cuenta_suspendida.php");  // Mostrar esta pantalla si se ingreso USUARIO y CLAVE menor a 1 caracter de longitud                    
                }
                else if (  $status_usuario_bd == 0  ||  $status_usuario_bd == 1  )   // 0 = Cuenta recien creada,  1 = Cuenta activa
                        {
                            // ------------- GET CURRENT TIME ----------------------------------------
                            $q_fecha_db  =  "
                                                SELECT NOW() as fecha_hora_db
                                            ";
                            $rq_fecha_db        = mysqli_query($conexion,$q_fecha_db) ;
                            $registro_fecha_db  = mysqli_fetch_assoc($rq_fecha_db);
                            $fecha_hora_db      = $registro_fecha_db['fecha_hora_db'];                                 
                            // -------------- END GET CURRENT TIME ----------------------------------------
                            
                            $segundos_transcurridos = strtotime($fecha_hora_db) - strtotime($fecha_intento_acceso);  
                            $minutos_transcurridos  = $segundos_transcurridos / 60;             
                            //echo "<br> Minutros transcurridos  : $minutos_transcurridos";
                            $q_fecha_intento_acceso = "
                                                            UPDATE usuarios
                                                            SET fecha_intento_acceso  =  '$fecha_hora_db'  
                                                            WHERE usuario = '$usuario'
                                                       ";
                            if (!mysqli_query($conexion,$q_fecha_intento_acceso)) { die('Error: ' . mysqli_error($conexion)); }
                            
                            if (  $minutos_transcurridos  > 5 )
                            {
                                $q_inicializar_n_intentos_acceso = "
                                                                        UPDATE usuarios
                                                                        SET n_intentos_acceso  =  0 
                                                                        WHERE usuario = '$usuario'
                                                                    ";
                                if (!mysqli_query($conexion,$q_inicializar_n_intentos_acceso)) { die('Error: ' . mysqli_error($conexion)); }
                                
                                if (  $clave == $clave_usuario_bd  )  // SI clave OK ==> Verificar status de la cuenta antes del acceso
                                {
                                    session_start();

                                    $_SESSION['usuario']    = $usuario;
                                    $_SESSION['clave']      = $clave;
                                    $_SESSION['status']     = $usuario;
                                    $_SESSION['almacen']     = $almacen;                                    
                                    $_SESSION['mensaje']    = "";                                    
                                    switch( $status_usuario_bd  )
                                    {
                                        case 0:
                                                $_SESSION['n_intentos_nueva_clave']     = 0;                                                                        
                                                header("location:usuario_definir_clave.php");
                                                break;
                                        case 1:
                                                // ------------- GET CURRENT TIME ----------------------------------------
                                                    $q_fecha_db  =  "
                                                                        SELECT NOW() as fecha_hora_db
                                                                    ";
                                                    $rq_fecha_db        = mysqli_query($conexion,$q_fecha_db) ;
                                                    $registro_fecha_db  = mysqli_fetch_assoc($rq_fecha_db);
                                                    $fecha_hora_db      = $registro_fecha_db['fecha_hora_db'];                                 
                                                // -------------- END GET CURRENT TIME ----------------------------------------
                                                
                                                $segundos_transcurridos = strtotime($fecha_hora_db) - strtotime($fecha_actualizacion_clave);  
                                                $minutos_transcurridos  = $segundos_transcurridos / 60;
                                                $horas_transcurridas    = $minutos_transcurridos / 60;
                                                $dias_clave = $horas_transcurridas / 24;
                                                
                                                if (  $dias_clave < 80  )
                                                {
                                                    $_SESSION['mensaje'] = "";
                                                    header("location:inicio.php");                                                                
                                                }
                                                else if ( $dias_clave > 80 and  $dias_clave < 90  )
                                                    {
                                                        $dias_usuario = 90 - ceil($dias_clave);
                                                        $_SESSION['mensaje'] = "Cambie su clave de acceso. [$dias_usuario] " ;
                                                        header("location:inicio.php");
                                                    }
                                                else if ( $dias_clave > 90  )
                                                    {
                                                        $_SESSION['n_intentos_nueva_clave'] = 0;
                                                        header("location:usuario_cambiar_clave.php");
                                                    }
                                                
                                                break;
                                        default://echo "<br> Unknown condition" ;
                                    }                                    
                                    
                                }
                                else if (  $clave !== $clave_usuario_bd  )
                                        {
                                            $q_incrementar_n_intentos_acceso = "
                                                            UPDATE usuarios
                                                            SET n_intentos_acceso = n_intentos_acceso + 1 
                                                            WHERE usuario = '$usuario'
                                                       ";
                                                if (!mysqli_query($conexion,$q_incrementar_n_intentos_acceso)) { die('Error: ' . mysqli_error($conexion)); }
                                            header("location:password_fail_verificar_usuario.php");
                                        }
                            }
                            else if (  $minutos_transcurridos  < 5 )  
                                {
                                    if (  $n_intentos_acceso_bd   > 3 )
                                    {
                                        header("location:password_fail_cuenta_bloqueda.php");                                       
                                    }
                                    else
                                        {
                                            if (  $clave == $clave_usuario_bd  )
                                            {
                                                $q_inicializar_n_intentos_acceso = "
                                                                                        UPDATE usuarios
                                                                                        SET n_intentos_acceso  =  0 
                                                                                        WHERE usuario = '$usuario'
                                                                                    ";
                                                if (!mysqli_query($conexion,$q_inicializar_n_intentos_acceso)) { die('Error: ' . mysqli_error($conexion)); }

                                                session_start();
                                                
                                                $_SESSION['usuario']    = $usuario;
                                                $_SESSION['clave']      = $clave;
                                                $_SESSION['status']     = $usuario;
                                                $_SESSION['almacen']    = $almacen;                                                                                                
                                                
                                                switch( $status_usuario_bd  )
                                                {
                                                    case 0:
                                                            header("location:usuario_definir_clave.php");
                                                            break;
        
                                                    case 1:
                                                            // ------------- GET CURRENT TIME ----------------------------------------
                                                                $q_fecha_db  =  "
                                                                                    SELECT NOW() as fecha_hora_db
                                                                                ";
                                                                $rq_fecha_db        = mysqli_query($conexion,$q_fecha_db) ;
                                                                $registro_fecha_db  = mysqli_fetch_assoc($rq_fecha_db);
                                                                $fecha_hora_db      = $registro_fecha_db['fecha_hora_db'];                                 
                                                            // -------------- END GET CURRENT TIME ----------------------------------------
                                                            
                                                            $segundos_transcurridos = strtotime($fecha_hora_db) - strtotime($fecha_actualizacion_clave);  
                                                            $minutos_transcurridos  = $segundos_transcurridos / 60;
                                                            $horas_transcurridas    = $minutos_transcurridos / 60;
                                                            $dias_clave = $horas_transcurridas / 24;
                                                            
                                                            if (  $dias_clave < 80  )
                                                            {
                                                                $_SESSION['mensaje'] = "";
                                                                header("location:inicio.php");                                                                
                                                            }
                                                            else if ( $dias_clave > 80 and  $dias_clave < 90  )
                                                                {
                                                                    $dias_usuario = 90 - ceil($dias_clave);
                                                                    $_SESSION['mensaje'] = "Cambie su clave de acceso. [$dias_usuario] " ;
                                                                    header("location:inicio.php");
                                                                }
                                                            else if ( $dias_clave > 90  )
                                                                {
                                                                    $_SESSION['n_intentos_nueva_clave'] = 0;
                                                                    header("location:usuario_cambiar_clave.php");
                                                                }
                                                            break;
                                                    default:
                                                    echo "<br> Unknown condition" ;
                                                }                                    
                                                    
                                            }
                                            else
                                                {
                                                    $q_incrementar_n_intentos_acceso = "
                                                                    UPDATE usuarios
                                                                    SET n_intentos_acceso = n_intentos_acceso + 1 
                                                                    WHERE usuario = '$usuario'
                                                               ";
                                                        if (!mysqli_query($conexion,$q_incrementar_n_intentos_acceso)) { die('Error: ' . mysqli_error($conexion)); }
                                                    header("location:password_fail_verificar_usuario.php");
                                                }
                                        }

                                }
                        }
            }
    }
    else
        {
            header("location:index.html");  // Mostrar esta pantalla si se ingreso USUARIO y CLAVE menor a 1 caracter de longitud
        }
?>