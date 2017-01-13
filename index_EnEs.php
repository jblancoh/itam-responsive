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
    
    
      
    
    <!--  
      <img alt="" src="http://exalumnos.itam.mx/css/img/logo.gif" style="height: 45px;">
    -->
      
      </div>
		
    <div style="clear: both;"></div>
	
    <img src="pics/spacer.gif" height="5px" width="960px" />
    
  </div>
  <div id="contenido">
    <div id="bloqueizquierdo">      
      <h1 style="font-size: 16px;">FMI - Streaming</h1>
      <h3>
      LIVE: LATIN-AMERICAN ECONOMY AND FUTURE: THE YOUTH’S VOICE
      <br/>Lipton-Roubini University Roadshow — México
      </h3>
      <p style="font-size: 14px;">
        Para ver el streaming:<br/>
      </p>  
      <p style="font-size: 14px;">
        <a href="http://www.imf.org/external/np/seminars/eng/2014/imflima2015/index.htm">En inglés</a>
     </p>
     <p style="font-size: 14px;">
        <a href="http://www.imf.org/external/spanish/np/seminars/2014/fmilima2015/index.htm">En español</a>
      </p>
      
		</div>  
  </div>   
      <div id="separador" style="width:100%; height:10px; clear:both;"></div>
POV;

$html2 =<<<POV
		      
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
