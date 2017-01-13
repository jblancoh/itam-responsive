<?php

// ejemplos para los queries:
// #InternetNecesario
//$query = '#Eleccionesmx';
// algunas palabras
//$query = 'genial+OR+geniales+OR+excelente+OR+super';
// from:nombre    lo mismo que @nombre
//$query = 'from:nombre';
//$query = '#PremiosITAM';
$query = '#EmprendedorITAM #InnovacionITAM';
// cada cuandos segundos recalcular el cache. Se recomienda superior a 1 minuto
$timeout = 60;
// cuantos ir a buscar cada hit. Se crecomienda que la cantidad no excede de much de 60/rotacion (en el js)
$rpp = 25;

// NO TOCAR DESPUES DE ESTA LINEA

$data = null;
$fname = 'twit.ser';
$f = @fopen($fname, 'rb');
if ($f)
{
  $data = unserialize(fread($f, filesize($fname)));
  fclose($f);
}

$lastid = 0;
if ($data)
{
  if (time() < $data['time'])
  {
    echo $data['data'];
    return;
  }
  $data = null;
}

$b = file_get_contents('http://search.twitter.com/search.json?q='.rawurlencode($query).'&rpp='.$rpp);

$data = array(
  'time' => time() + $timeout,
  'data' => $b
);

$f = fopen($fname, 'wb');
if ($f)
{
  fwrite($f, serialize($data));
  fclose($f);
}

echo $data['data'];

?>
