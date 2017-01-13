<?php




session_start(); 

/*
include_once('log.php');


if(!isLogin())
{
  $sv = $_SERVER['SERVER_NAME'] . "/itam/login.php";
  header("Location: http://" . $sv);
}

else
{

$sv = "http://" . $_SERVER['SERVER_NAME'] . "/itam/";

}
$tm = time();
*/


$html1 =<<<POV
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link href="css/estilos.css" rel="stylesheet" type="text/css" />
  <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
  <link href="css/jqueryUI.css" rel="stylesheet" type="text/css" />
  
  <link href='http://fonts.googleapis.com/css?family=Duru+Sans' rel='stylesheet' type='text/css'>
  
  
  <meta http-equiv="PRAGMA" content="NO-CACHE" />
  <meta http-equiv="Expires" content="-1" />
  <meta name="Language" content="es" />
  
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
  <script type="text/javascript" src="js/core.js"></script>
  <script type="text/javascript" src="js/eventManager.js"></script>
  <script type="text/javascript" src="js/ajaxManager.js"></script>
  <script type="text/javascript" src="js/animManager.js"></script>
  
<script type="text/javascript" src="js/jquery.countdown.js"></script>
 <script type="text/javascript">

  jQuery.noConflict();

jQuery(document).ready(function() {
	
	var tipo = 1; //1 = aun esta la transmision 2=ya esta el contador	
	
	//loadAgenda();
	
	jQuery('#hidden').countdown({until:60*30,onExpiry: renew,onTick: searchForFlag, tickInterval: 10,compact: true,layout: 'Volveremos con la transmisión en aproximadamente <b>{mnn}{sep}{snn}</b> minutos'});
	
	 function renew()
     {
     	jQuery.ajax({
     		url: "countdownFlag.php?",
     		dataType: 'json',
     		cache:false,
     		success: function(data){
     			jQuery('#hidden').countdown('change',{until:data.plus});
     		}
     	});
     }
     
	 function searchForFlag()
     {
     	jQuery.ajax({
     		url: "countdownFlag.php?",
     		dataType: 'json',
     		cache:false,
     		success: function(data){
 				if(data.flag ==  true) 
 				{   
 					if(tipo == 1)
 					{
	 					tipo = 2;	
	 					if (jQuery("#hidden").is(":hidden")) {
							jQuery("#hidden").slideDown("slow");
						}
	 					jQuery('#hidden').countdown('change',{until:data.receso,onTick: searchForFlag, tickInterval: 20});
 					}
 					
 				}
 				if(tipo == 2 && data.flag == false)
 				{
 				tipo = 1;			
 				if (!jQuery("#hidden").is(":hidden")) {
							jQuery("#hidden").slideUp("slow");
						}
 				jQuery('#hidden').countdown('change',{until:60*30,onTick: searchForFlag, tickInterval: 10});
 				}
     		}
     	});
     }

   function loadAgenda()  
   {
     jQuery.getJSON('laagenda.php', function(data) 
     {
       if(typeof data == 'object')
       {
         if(typeof data.agenda  != 'undefined')
         {
           jQuery('#agenda').html(data.agenda);
           // PAU: Si no hay agenda o sólo se carga una vez comentar esta línea
           var st = setTimeout(function(){loadAgenda();}, 40000);
         }
       }
    });
  }
}); 
  </script>
  
  
  <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-17153173-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


</head>

<body>
<div id="hidden" style="display:none;"></div>
<center>
<div id="container">
  <div id="header">
    <div style="font-family: Georgia; font-size:35px; padding: 15px 5px 5px 5px; line-height:25px; height:70px; width:450px; float: left; text-align: left;">
      <img alt="ITAM" src="pics/itam-logo.jpg" /></div>
		
    <div style="font-family: Georgia; font-size:35px; padding: 20px 5px 5px 5px; line-height:25px; height:70px; width:450px; float: right; text-align: right;">
    
    
      <img alt="" src="img/logoReConexion.png" style="height: 45px;">
    
    <!--  
      <img alt="" src="http://exalumnos.itam.mx/css/img/logo.gif" style="height: 45px;">
    -->
      
      </div>
		
    <div style="clear: both;"></div>
	
    <img src="pics/spacer.gif" height="5px" width="960px" />
    
  </div>
  <div id="contenido">
   
    <div id="bloqueizquierdo">      
      <h1 style="font-size: 16px;">#ReconexiónITAM</h1>
		  <h2>Relaciones Internacionales</h2>
		
		
       <!-- Cybercast incrustation -->
<div id="cybercast"></div>
<script type="text/javascript">
var cybercast = {
  // El canal de esta pagina
  channel: 'itam',
  redirect: {
    'blackberry': 'rtsp://stream0.cybercast.mx:1935/adlr/itam.sdp'
  },
  params: 
  {
    bgiphone: 'http://www.cybercast.mx/tools/pics/iphone-background.jpg',
    bgipad: 'http://www.cybercast.mx/tools/pics/ipad-background.jpg',
    bgrtsp: 'http://www.cybercast.mx/tools/pics/rtsp-background.jpg',
    evento: '#ReconexiónITAM',
    widthrtmp: '640px',
    heightrtmp: '360px'
  }
}
</script>
<script src="http://www.cybercast.mx/tools/cybercast.js" type="text/javascript"></script>
<!-- End cybercast incrustation -->
		
		
        
        <div id="agenda"></div>
        
      <div style="width:100%; height:10px; clear:both;"></div>
      
    </div>
    <div id="bloquederecho" style="padding-top: 63px;">
		
		<p></p>
		
		<a class="twitter-timeline"  href="https://twitter.com/search?q=%23Reconexi%C3%B3nITAM"  data-widget-id="380904386680987649">Tweets about "#ReconexiónITAM"</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

		

		
		<!--
        <div id="bloquederecho2">
		  <h3>#EmprendedorITAM / #InnovaciónITAM en Twitter</h3>
          <div id="fillin">
            Mensajes de Twitter
          </div>
          <script type="text/javascript" src="js/twitterapi.js"></script>
          <div id="separador" style="height: 10px;"><img src="pics/dot.gif" alt="" /></div>
          <div id="cajatwitter">
            <h3>Participa en la conversaci&oacute;n</h3>
          -->
POV;

$html2 =<<<POV
<!--
          </div>
        </div> 
		-->  
        <!-- <img src="http://cybercast.mx/comunicacionsocial/pics/marquesina.jpg" style="margin-top:30px;" />-->
      </div> 
  
      <div id="separador" style="width:100%; height:10px; clear:both;"></div>
		
		
		
<!--		
      <div id="programa">
		
<div style="padding-bottom: 20px; text-align: left;">
  <b>Fecha: 19 y 20 de Agosto de 2013</b><br />
  Lugar: Auditorio Raúl Bailleres, ITAM<br />
		Río Hondo #1. Col. Progreso Tizapán. Del. Álvaro Obregón
</div>

-->

<div style="text-align: center;">
  <h2>PROGRAMA</h2>
</div>


  

<table>		

 	
 <tr>
    <td style="padding: 10px; text-align: bold;">9.00 h</td>
    <td style="padding: 10px;">
	    <strong>México y la Alianza del Pacífico</strong><br/> 
	    <i> Natalia Saltalamacchiaz</i>
    </td>
  </tr>
  
   <tr>
    <td style="padding: 10px; text-align: bold;">10.15 h</td>
    <td style="padding: 10px;">
	    <strong>Tensiones entre China y Japón: ¿una nueva Guerra Fría?</strong><br/> 
	    <i>Ulises Granados</i>
    </td>
  </tr>
  
    <tr>
    <td style="padding: 10px; text-align: bold;">11.30 h</td>
    <td style="padding: 10px;">
	    <strong>La seguridad nacional y la democracia, ¿contradictorias o complementarias?</strong><br/> 
	    <i>Athanasios Hristoulas</i>
    </td>
  </tr>
  
  <tr>
    <td style="padding: 10px; text-align: bold;">12.45 h</td>
    <td style="padding: 10px; text-align: bold;">
       <strong>Crisis del Euro y el populismo en Europa</strong><br/>
       <i>Gabriel Goodliffe</i><br/>
    </td>
  </tr>
  
 
    
</table>

<br /><br /><br  />
		
		
	
      </div>
		
      
      <div id="separador" style="width:100%; height:10px; clear:both;"></div>
      
  <div id="footer" style="background-color: #00685d; border-top: 3px solid #a8d328; color:#FFF; line-height:40px;">

  <p>
CC, algunos derechos reservados</p></div>
</div>  
</center>
</body>

</html> 
POV;

print $html1;
// include("postt/twitteroauth/index.php");
print $html2;

?>
