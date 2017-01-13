<?php
  
function getUseragents()
{
  $dm_usergent = array(

  'Android' => array('Android','genericRTSP'),
  'SymbianOS' => array('Symbian O.S.','genericRTSP'),
  'SymbOS' => array('Symbian O.S.','genericRTSP'),
  'BlackBerry' => array('BlackBerry','genericRTSP'),
  'webOS' => array('webOS','genericRTSP'),

  'LG' => array('LG','genericRTSP'),
  'SAMSUNG' => array('Samsung','genericRTSP'),  
  'SonyEricsson' => array('SonyEricsson','genericRTSP'),
  'Walkman' => array('Sony Walkman','genericRTSP'),
  'Nokia' => array('Nokia','genericRTSP'),
  'MOT-RASPB' => array('Raspberry','genericRTSP'),
  'VLC' => array('VLC PLAYER','genericRTSP'),

  'iPhone' => array('iPhone','AppleMovile'),
  'iPod' => array('iPod','AppleMovile'),
  'iPad' => array('iPad','AppleMovile'),

  'PSP' => array('Sony PSP','sinsoporteaun'),
  'Nintendo Wii' => array('Nintendo Wii','sinsoporteaun'),
  'Nintendo DSi' => array('Nintendo DSi','sinsoporteaun'),

  'Linux' => array('Linux','Desktop'),
  'Windows' => array('Windows','Desktop'),
  'Macintosh' => array('Macintosh','Desktop'),
  'Apple' => array('Mac','Desktop')
  );
   
  return $dm_usergent;
}
  

function getNavigator($useragents, $useragent)
{
   foreach($useragents as $nav=>$ua)
   {
     $exist = stripos($_SERVER['HTTP_USER_AGENT'], $nav);
      if ($exist === false) 
      {
        "La cadena no fue encontrada";
      }
      else
      {
        return $ua;
      }
   }
   return 'otro';
}

function initDetection($pages, $flashparams, $player)
{
  $url = $player['url'];
  $app = $player['app'];
  $file = $player['file'];
  
  $puertowowza = ':1935';
  $urlapplemovil = 'http://'.$url.$puertowowza.'/'.$app.'/'.$file.'/playlist.m3u8';
  $urlgenericRTSP = 'rtsp://'.$url.'/'.$app.'/'.$file;
  $urlflashplayer = 'rtmp://'.$url.'/'.$app;

  if($player['ondemand'] == true)
  {
    $app = 'vod';
    $file = 'http://google.adigital.info/moviles/ondemand/'.$player['ondemandfile'];
    $urlflashplayer = 'rtmp://'.$url.'/'.$app;
  }

  $useragent = $_SERVER['HTTP_USER_AGENT'];
  $dm_usergent = getUseragents();
  $navi = getNavigator($dm_usergent,$useragent);

  if(isset($navi[0]) && $navi!='otro')
  {
    $navegador=$navi[0];
    $navegadorrama=$navi[1];
  }
  else
  {
    $navegadorrama = $navi;
  }

  switch ($navegadorrama)
  {
    case 'AppleMovile':
      if($navegador == 'iPad' && $pages['ipad'])
      {
        //header('location: subsites/ipad.php');
      }
      
      if(($navegador == 'iPhone' || $navegador == 'iPod') && $pages['iphone'])
      {
        //header('location: subsites/iphone.php');
      }
      if($navegador == 'iPad')
      {
        $viewportwidth = 1024;
        $viewportheight = 768;
        $background = "/navarrete/pics/ipad-background.jpg";
        $class = $navegador;
      }
      else 
      {  
      	$viewportwidth = 320;
      	$viewportheight = 480;
        $background = "/navarrete/pics/iphone-background.jpg";
        $class = "iPhone";
      }
      $url = $urlapplemovil;
      include_once "lib/movtemp.php";
      
    break;

    case 'genericRTSP':
      if($navegador == 'BlackBerry' && $pages['bb'])
      {
       // header('location: subsites/bb.php');
      }
      
      if($navegador == 'Android' && $pages['android'])
      {
        //header('location: subsites/android.php');
      }
      
      if($navegador == 'webOS' && $pages['webos'])
      {
        //header('location: subsites/android.php');
      }
      
      $viewportwidth = 320;
      $viewportheight = 480;
      $class = 'iPhone';
      $background = "/navarrete/pics/iphone-background.jpg";
      $url = $urlgenericRTSP;
      include_once "lib/movtemp.php";
      break;

    case "Desktop":

      $url = $urlflashplayer;
      include_once "lib/flashplayer.php";
      include_once "lib/desktemp.php";
      break;

    case "otro":
      print 'Lo sentimos pero tu dispositivo no es soportado aun.';
    $archivo = 'logsdesc/'.date("m-Y").' logsbrowsersdesc.txt';
      $persona = date("d/m/Y G:i:s") . ' ' .$_SERVER['HTTP_USER_AGENT'] .' IP: '.$_SERVER['REMOTE_ADDR'] . "\n\n";
      file_put_contents($archivo, $persona, FILE_APPEND | LOCK_EX);
    break;
  }
  
  $archivo = 'logs/'.date("m-Y").' logsbrowsers.txt';
  $persona = date("d/m/Y G:i:s") . ' ' .$_SERVER['HTTP_USER_AGENT'] .' IP: '.$_SERVER['REMOTE_ADDR'] . "\n\n";
  file_put_contents($archivo, $persona, FILE_APPEND | LOCK_EX);
}

initDetection($pages, $flashparams, $player);
?>