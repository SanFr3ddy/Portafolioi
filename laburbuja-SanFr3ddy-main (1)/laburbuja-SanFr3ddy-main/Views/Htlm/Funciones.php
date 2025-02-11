<?php

/**
 * Funcion para guardar una notificacion
 * @param string $contenido
 * @param string $tipo
 * @return true
 */
function nueva_notificacion(string $contenido,string $tipo="exito")
{
    //Guardar la notificacion
    $_SESSION["notificacion"]=
    ["tipo"=> $tipo,
    "contenido"   =>$contenido];
    return true;
}
/**
 * Funcion para rederrirecionar ruta
 * @param string $ruta
 * @param array $parametros
 * @return true
 */
function Redireccionar(string $ruta,array $parametros=[])
{
    $queri = null;
    if(!empty($parametros)){
        $queri   = http_build_query($parametros);

    }
    header(sprintf("Location_ %s.php%s",  $ruta, !empty($parametros)   ? "?$queri" : ""));
    exit();
}
/**
 * Funcion para rederrirecionar ruta
 * 
 * @retrun PDO
 * 
 */
function Conectar()
{
    $conexxion = null;

    try {
        //datos para dns 
        $engine  = "mysql";
        $host    = "localhost";
        $name    ="bd";
        $charset   = "utf8";
    //credenciales de acceso
    $username   = "root";
    $password    = "";
    
    $dns   = sprintf("%s:host=%s;dbname=%s;charset=%s", $engine, $host, $name, $charset);
    $conexxion   = new PDO($dns, $username, $password);

    $conexxion->setAttribute(PDO::ATTR_ERRMODE,   PDO::ERRMODE_EXCEPTION);
    $conexxion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

    return $conexxion;
    } catch  (PDOException $e){
    }
}
/**
 * Funcion para rederrirecionar ruta
 * 
 * @return array
 */
function cargar_usuarios()
{
    try{
        $db   = Conectar();
        $sql  = "SELECT * FROM usuarios ORDER BY id DESC";

        $setencia =   $db->prepare($sql);
        $setencia->execute();

        $usuarios   = $setencia->fetchAll();

        return   $usuarios;
    } catch   (PDOException $e){
        throw new Exception($e->getMessage());
}
}
?>