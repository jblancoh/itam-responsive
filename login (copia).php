<?php

  $sv = $_SERVER['SERVER_NAME'] . "/itam";
 // header("Location: http://" . $sv);
  
  
session_start(); 
include_once('log.php');

if(isLogin())
{	
	header("Location: http://" . $sv);
}

$nuser = $_POST['nuser'];
$npw = $_POST['npw'];
$login = false;
$msg = '';


if($nuser && $npw)
{
  $filename = "users_transmision.csv";
  $ret = array();

  if (is_readable($filename)) 
  {
    $row = 1;
	$content = '';
    $handler = fopen($filename, "r");	

    while (($data = fgetcsv($handler, 1000, ",")) !== FALSE) 
    {    	
      if(strval($data[1]) == strval($nuser) && strval($data[2]) == strval($npw))
      {      
        setIsLogin($nuser);
        $login = true;
        
        setLog(strval($data[1]));
        
        
        break;
      }
        
    }
    
    $msg =<<<POV
  <div class="alert">Los datos proporcionados son incorrectos</div>
POV;

    fclose($handler);  
  }
}





if($login)
{
  $sv = $_SERVER['SERVER_NAME'] . "/itam";
  header("Location: http://" . $sv);
  }
else
{
$tm = time();
$html =<<<POV
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link href="css/estilos.css" rel="stylesheet" type="text/css" />
  <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
  <link href="css/jqueryUI.css" rel="stylesheet" type="text/css" />
  <meta http-equiv="PRAGMA" content="NO-CACHE" />
  <meta http-equiv="Expires" content="-1" />
  <meta name="Language" content="es" />
  
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script type="text/javascript">
	jQuery.noConflict();
	
jQuery(document).ready(function(){

		jQuery('#logitam').submit(function(){
		
			var error=false;
				jQuery('.required').each(function(k,v){
				
					if(!jQuery(this).val())
					{
						jQuery(this).addClass('notnull').attr({placeholder:"El campo no puede ser vacio"});
						error=true;					
					}
					else
					{
						jQuery(this).removeClass('notnull').attr({placeholder:""});
					}
				});
		
			if(!error)
			jQuery('#logitam').submit();
			else	
			return false;
		});	

});

</script>
  
  <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-17153173-1']);
  _gaq.push(['_setDomainName', '.com.mx']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


<style type="text/css">
.notnull{
		border-color:red;
		background:#FFD8D8;
		color:red;
}

.alert{
	background:#FFAEAD;
    border: 2px solid red;
    color: red;
    font-size: 13px;
    padding: 15px;
}
</style>

</head>

<body>
<div id="hidden" style="display:none;"></div>
<center>
<div id="container">
  <div id="header">
    <div style="font-family: Georgia; font-size:35px; text-align:left; padding: 15px 5px 5px 5px; line-height:25px; color:#00574A; height:70px; width:950px; text-align:left;">
       <img src="pics/itam-logo.jpg" height="65px" width="306px" />
    </div>
    
    <img src="pics/spacer.gif" height="5px" width="960px" />
    
  </div>
  <div id="contenido">
   
    <div id="bloqueizquierdo">
      <h3>Seminario de Perspectivas Económicas 2012</h3>
        
      <br/>
      <br/>
      
      $msg     
      
      <br/>
      <br/>
      
      <form action="login.php" id="logitam" method="post">
        <table>
          <tr>
            <td>Usuario:</td><td><input name="nuser" id="nuser" type="text" size="40" maxlength="40" class="required" /></td>
          </tr>
            <td>Contraseña:</td><td><input type="password" id="npw" name="npw" size="40" maxlength="40" class="required"/></td> 
          </tr>
          <tr>
            <td colspan="2"><input  type="submit" value="Enviar" /></td>
          </tr>
          </tr>
        </table>
        <input type="hidden" name="s" value="$tm" />
      </form>      
    </div>
    <div id="bloquederecho"> 
    
       
    </div> 
  
      <div id="separador" style="width:100%; height:10px; clear:both;"></div>
      
  <div id="footer">

  <p>
CC, algunos derechos reservados</p></div>
</div>  
</center>
</body>           
</html> 

POV;

echo $html;
}

?>