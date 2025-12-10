<?php

$conexion = new mysqli("localhost","root","root","sigi_huanta");
if($conexion->connect_errno){
    echo "fallo al conectar a MySQL:(".$$conexion->connect_errno.")".
    $conexion->connect_error;

}
$xml = new DOMDocument('1.0','UTF-8');
$xml->formatOutput = true;
$et1 = $xml->createElement("programas_estudio");
$xml->appendChild($et1);

$consulta = "SELECT*FROM sigi_programa_estudios";
$resultado = $conexion->query($consulta);

while ($pe = mysqli_fetch_assoc($resultado)) {

echo $pe['nombre']."<br>";
$num_pe = $xml->createElement('pe_'.$pe['id']);
$codigo_pe = $xml->createElement('codigo',$pe['codigo']);
$num_pe->appendChild($codigo_pe);

$tipo_pe = $xml->createElement('tipo',$pe['tipo']);
$num_pe->appendChild($tipo_pe);

$nombre_pe = $xml->createElement('nombre',$pe['nombre']);
$num_pe->appendChild($nombre_pe);


$et_plan=$xml->createElement('planes_estudio');
$consulta_plan = "SELECT * FROM sigi_planes_estudio WHERE id_programa_estudios = ".$pe['id'];
$resultado_plan = $conexion->query($consulta_plan);

while ($plan = mysqli_fetch_assoc($resultado_plan)) {

    $num_plan = $xml->createElement('plan_estudio');
    $id_plan = $xml->createElement('id', $plan['id']);
    $num_plan->appendChild($id_plan);

    $nombre_plan = $xml->createElement('nombre', $plan['nombre']);
    $num_plan->appendChild($nombre_plan);

    $resolucion_plan = $xml->createElement('resolucion', $plan['resolucion']);
    $num_plan->appendChild($resolucion_plan);

    $et_plan->appendChild($num_plan);
}


$et_plan->appendChild($num_plan);
$num_pe->appendChild($et_plan);
$et1->appendChild($num_pe);

}

$archivo = "ies_db.xml";
$xml->save($archivo);
?>