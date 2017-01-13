<?php

$is_mobile = false;


if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad') || strstr($_SERVER['HTTP_USER_AGENT'],'Android'))
{
  $is_mobile = true;
}

function setLog($user)
{
	$file = 'claveslogs.csv';
	$date = date('c');
	$ip = $_SERVER['REMOTE_ADDR'];
	$contenido = "$user,$ip,$date\n";

	// Primero vamos a asegurarnos de que el archivo existe y es escribible.
	if (is_writable($file)) {

		// En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adición.
		// El puntero al archivo está al final del archivo
		// donde irá $contenido cuando usemos fwrite() sobre él.
		if (!$gestor = fopen($file, 'a')) {
			exit;
		}

		// Escribir $contenido a nuestro archivo abierto.
		if (fwrite($gestor, $contenido) === FALSE) {
			exit;
		}
		fclose($gestor);

	}

}

function isLogin()
{

  if(isset($_COOKIE['itmlog']) && $_COOKIE['itmlog'])
  {

    return true;
  }
  else
  {

    return false;
    }
}


function setIsLogin($user)
{
  //if(!$is_mobile)
    //$res = setcookie('itmlog', $user, time() + (3600 * 24), 'itam', 'cybercast.mx');
    $res = setcookie('itmlog', $user, time() + (3600 * 24), '', '', 0);
  //else
    //$res = setcookie('itmlog', $user, time() + (3600 * 24));

  return true;
}

function unsetLogin()
{
  setcookie('itmlog', $user, time() - (3600 * 24), '', '', 0);
/*
  if(!$is_mobile)
    setcookie("itmlog", "", time()-(3600 * 24),  'itam', 'cybercast.mx');
  else
    setcookie("itmlog", "", time()-(3600 * 24));
    */
  unset($_COOKIE['itmlog']);
  $_COOKIE['itmlog'] = '';
}


?>
