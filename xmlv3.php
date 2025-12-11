<?php
$xml = simplexml_load_file('ies_db1.xml') or die('Error: nose cargo el xml.Escribe correctamnete el nombre del archivo');
/*echo $xml->pe_1->nombre ."<br>"; 
echo $xml->pe_2->nombre;*/

foreach ($xml as $i_pe => $pe) {
    echo 'nombre:' . $pe->nombre . "<br>";
    echo 'codigo:' . $pe->codigo . "<br>";
    echo 'tipo:' . $pe->tipo . "<br>";
    foreach ($pe->planes_estudio[0] as $i_ple => $plan) {
        echo '--' . $plan->nombre . "<br>";
        echo '--' . $plan->resolucion . "<br>";
        echo '--' . $plan->fecha_registro . "<br>";
        foreach ($plan->modulos_formativos[0] as $id_mod => $modulo) {
             echo '----' . $modulo->descripcion . "<br>";
               echo '----' . $modulo->nro_modulo . "<br>";
             foreach ($modulo->periodos[0] as $i => $value) {
                
             }
        }
    }
}