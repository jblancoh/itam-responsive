<?php

$filename = "laagenda.html";

$ret = array();

if (is_readable($filename))
{
	$content = '';
  $handler = fopen("laagenda.html", "r");

  $content = fread($handler, filesize($filename));
  fclose($handler);

  $ret = array('agenda' => $content);
}

print json_encode($ret);
die();
?>
