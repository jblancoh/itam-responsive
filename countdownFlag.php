<?php 
/**** 
 * 
 Lo que hay que editar
 */

// EDITAR FLAG TRUE=YA ES HORA DEL RECESO , FALSE=HAY TRANSMISION
$flag = false;

// LA HORA EN LA QUE TERMINARIA EL RECESO CON EL FORMATO DE 24 HRS
$hora = 11;
$minuto = 45;

$breaktime = date("d-m-Y h:i", mktime($hora, $minuto, 0));

//SI AL TERMINAR EL TIEMPO DE RECESO AUN NO HAY TRANSMISION PONEMOS UN TIEMPO DE MAS EN SEGUNDOS
$plustime = 60;


/**
 * 
 * Lo que ya no hay que editar
 */

$actualtime = date('d-m-Y h:i',time());
$fecha1 = strtotime($actualtime);
$fecha2 = strtotime($breaktime);
$diff = round(($fecha2 - $fecha1));

$breaktoend = $diff;


die(json_encode(array('flag' =>$flag,
'receso' => $breaktoend,
'plus' => $plustime)));


?>