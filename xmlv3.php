<?php

$conexion = new mysqli("localhost", "root", "root", "sigi_huanta");
if ($conexion->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
}
$conexion->set_charset("utf8");
date_default_timezone_set("America/Lima");


$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

$et1 = $xml->createElement('Programas_estudio');
$xml->appendChild($et1);
$consulta = "SELECT * FROM sigi_programa_estudios";
$resultado = $conexion->query($consulta);

while ($pe = mysqli_fetch_assoc($resultado)) {
   echo $pe['nombre']. "<br>";
    $num_pe = $xml->createElement('pe_'. $pe['id']);
    $cod_pe = $xml->createElement('codigo', $pe['codigo']);
    $num_pe->appendChild($cod_pe);
    $tipo_pe = $xml->createElement('tipo', $pe['tipo']);
    $num_pe->appendChild($tipo_pe);
    $nom_pe = $xml->createElement('nombre', $pe['nombre']);
    $num_pe->appendChild($nom_pe);
    $et_plan = $xml->createElement('plan_estudio');
    $consulta_plan = "SELECT * FROM sigi_planes_estudio WHERE id_programa_estudios = ". $pe['id'];
    $resultado_plan = $conexion->query($consulta_plan);
    while ($plan = mysqli_fetch_assoc($resultado_plan)) {
        $et_plan_estudio = $xml->createElement('plan_estudio_'. $plan['id']);
        echo $plan['nombre']. "<br>";
        $num_plan = $xml->createElement('id_plan'. $plan['id']);
        $nom_plan = $xml->createElement('nombre', $plan['nombre']);
        $num_plan->appendChild($nom_plan);
        $resolucion_plan = $xml->createElement('resolucion', $plan['resolucion']);
        $num_plan->appendChild($resolucion_plan);
        $fecha_plan = $xml->createElement('fecha', $plan['fecha_registro']);
        $num_plan->appendChild($fecha_plan);
        $et_plan_estudio->appendChild($num_plan);
        $et_modulos = $xml->createElement('modulos');
        $consulta_modulos = "SELECT * FROM sigi_modulo_formativo WHERE id_plan_estudio = ". $plan['id'];
        $resultado_modulos = $conexion->query($consulta_modulos);
        while ($modulo = mysqli_fetch_assoc($resultado_modulos)) {
            $et_modulo = $xml->createElement('modulo_'. $modulo['id']);
            echo $modulo['descripcion']. "<br>";
            $num_modulo = $xml->createElement('id_modulo'. $modulo['id']);
            $desc_modulo = $xml->createElement('descripcion', $modulo['descripcion']);
            $num_modulo->appendChild($desc_modulo);
            $nro_modulo = $xml->createElement('numero_modulo', $modulo['nro_modulo']);
            $num_modulo->appendChild($nro_modulo);
            $et_modulo->appendChild($num_modulo);
            $et_periodos = $xml->createElement('periodos');
            $consulta_periodos = "SELECT * FROM sigi_semestre WHERE id_modulo_formativo = ". $modulo['id'];
            $resultado_periodos = $conexion->query($consulta_periodos);
            while ($periodo = mysqli_fetch_assoc($resultado_periodos)) {
                $et_periodo = $xml->createElement('periodo_'. $periodo['id']);
                echo $periodo['descripcion']. "<br>";
                $num_periodo = $xml->createElement('id_periodo_'. $periodo['id']);
                $desc_periodo = $xml->createElement('descripcion', $periodo['descripcion']);
                $num_periodo->appendChild($desc_periodo);
                $et_periodo->appendChild($num_periodo);
                $et_unidades = $xml->createElement('unidades_didacticas');;
                $consulta_unidades = "SELECT * FROM sigi_unidad_didactica WHERE id_semestre = ". $periodo['id'];
                $resultado_unidades = $conexion->query($consulta_unidades);
                while ($unidad = mysqli_fetch_assoc($resultado_unidades)) {
                    $et_unidad = $xml->createElement('unidad_'. $unidad['id']);
                    echo $unidad['nombre']. "<br>";
                    $num_unidad = $xml->createElement('id_unidad_'. $unidad['id']);
                    $nom_unidad = $xml->createElement('nombre', $unidad['nombre']);
                    $num_unidad->appendChild($nom_unidad);
                    $creditos_teorico = $xml->createElement('creditos_teorico', $unidad['creditos_teorico']);
                    $num_unidad->appendChild($creditos_teorico);
                    $creditos_practico = $xml->createElement('creditos_practico', $unidad['creditos_practico']);
                    $num_unidad->appendChild($creditos_practico);
                    $tipo_unidad = $xml->createElement('tipo', $unidad['tipo']);
                    $num_unidad->appendChild($tipo_unidad);
                    $horas_semanales = $xml->createElement('horas_semanal',$unidad['creditos_teorico'] * 1 + $unidad['creditos_practico'] * 2);
                    $hora_semestrales = $xml->createElement('horas_semestral',($unidad['creditos_teorico'] * 1 + $unidad['creditos_practico'] * 2) * 16);
                    $num_unidad->appendChild($horas_semanales);
                    $num_unidad->appendChild($hora_semestrales);
                    $et_unidad->appendChild($num_unidad);
                    $et_unidades->appendChild($et_unidad);
                }
                $et_periodo->appendChild($et_unidades);
                $et_periodos->appendChild($et_periodo);
            }
            $et_modulo->appendChild($et_periodos);
            $et_modulos->appendChild($et_modulo);
        }
        $et_plan_estudio->appendChild($et_modulos);
        $et_plan->appendChild($et_plan_estudio);
    }
    $num_pe->appendChild($et_plan);
    $et1->appendChild($num_pe);

}

$archivo = 'ies_db1.xml';
$xml->save($archivo);