<?php
session_start();


include_once('log.php');

/*
if(!isLogin())
{
  $sv = $_SERVER['SERVER_NAME'] . "/itam/login.php";
  header("Location: http://" . $sv);
}

*/
$html1 =<<<POV
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link href="css/estilos.css" rel="stylesheet" type="text/css" />
  <link href="css/bulma.css" rel="stylesheet" type="text/css" />
  <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
  <link href="css/jqueryUI.css" rel="stylesheet" type="text/css" />

  <link href='http://fonts.googleapis.com/css?family=Duru+Sans' rel='stylesheet' type='text/css'>


  <meta http-equiv="PRAGMA" content="NO-CACHE" />
  <meta http-equiv="Expires" content="-1" />
  <meta name="Language" content="es" />

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>


  <script type="text/javascript" src="js/eventManager.js"></script>
  <script type="text/javascript" src="js/ajaxManager.js"></script>
  <script type="text/javascript" src="js/animManager.js"></script>

  <script type="text/javascript" src="js/jquery.countdown.js"></script>
  <script type="text/javascript">

  jQuery.noConflict();

jQuery(document).ready(function() {

  var tipo = 1; //1 = aun esta la transmision 2=ya esta el contador

  loadAgenda();

  jQuery('#hidden').countdown({until:60*30,onExpiry: renew,onTick: searchForFlag, tickInterval: 10,compact: true,layout: 'Volveremos con la transmisión en aproximadamente <b>{mnn}{sep}{snn}</b> minutos'});



   function renew()
     {
      jQuery.ajax({
        url: "countdownFlag2.php?",
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
        url: "countdownFlag2.php?",
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
</head>
<body style="line-height:20px;">
  <div id="hidden" style="display:none;"></div>
  <center>
  <div id="container">
  <div id="header">
    <div style="font-family: Georgia; font-size:35px; text-align:left; padding: 5px 5px 5px 5px; line-height:25px; color:#00574A; height:70px; width:950px; text-align:left;">
    <img src="pics/itam-logo.jpg" height="65px" width="306px" />

      <!--<img src="pics/logo_seminario_2016.png" height="65px" width="306px" style="float:right;"/>-->
      <img width="150px" height="45px" style="float: right; margin-top: 11px;" src="pics/exitam.png">

    </div>

    <img src="pics/spacer.gif" height="5px" width="960px" />

  </div>
  <div id="contenido">

    <div id="bloqueizquierdo">
    <div style="text-align:center">
    <img src="pics/logo_seminario_2016.png" height="65px" width="306px"/>
    </div>
      <!--<h1>Seminario de Perspectivas Empresariales 2016</h1>-->
      <!--<h2>Reformas en Acción</h2>-->

         <!-- Cybercast incrustation -->
<div id="cybercast"></div>
<script type="text/javascript">
var cybercast = {
  // El canal de esta pagina
  channel: 'itam',
  // Algun redirect especial (basicamente para blackberry: problemas de JS si no se hace asi)
  redirect: {
    'blackberry': 'rtsp://stream0.cybercast.mx:1935/adlr/itam.sdp'
  },
  // Algunos parametros para reemplazar en la plantilla antes de ensenarla {param1} {param2}
  // Util para imagenes de fondo, tamanos, colores, etc.
  params:
  {
    bgiphone: 'http://www.cybercast.mx/tools/pics/iphone-background.jpg',
    bgipad: 'http://www.cybercast.mx/tools/pics/ipad-background.jpg',
    bgrtsp: 'http://www.cybercast.mx/tools/pics/rtsp-background.jpg',
    evento: 'ITAM, Seminario México, TIC´s y Sociedad del Conocimiento',
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
    <div id="bloquederecho" style="margin-top:68px;" >

    <a class="twitter-timeline" href="https://twitter.com/hashtag/EmpresarialesITAM" data-widget-id="530400779811577856">Tweets sobre #EmpresarialesITAM</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>

POV;

$html2 =<<<POV



             <div id="separador" style="width:100%; height:10px; clear:both;"></div>

<div style="text-align: center;">
  <h2>PROGRAMA</h2>
</div>


<div>

<table style="text-align:center;">
        <tbody>
        <tr>
          <th>HORARIO</th>
          <th>TEMA</th>
          <th>EXPOSITOR</th>
        </tr>
        <tr">
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>8:00 - 9:00 h</b></div></td>
          <td width="40%" bgcolor="" coslpan="2"><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">Registro</span></div></td>
          <td></td>
        </tr>

        <!--
        <tr>
          <td colspan="3">
            <b>“PERSPECTIVAS MACROECONÓMICAS 2015”</b>
          </td>
        </tr>
        -->

        <tr>
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>9:00 - 9:10 h</b></div></td>
          <td width="40%" bgcolor=""><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">Bienvenida</span></div></td>
          <td width="30%" bgcolor=""><div style="text-align:center;"><span style="padding-left:10px;">Lorenzo Meade (Presidente de la Asociación de Ex Alumnos) ExITAM
          </span></div></td>
        </tr>

        <tr>
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>9:10 - 9:15 h</b></div></td>
          <td width="40%" bgcolor=""><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">Inauguración</span></div></td>
          <td width="30%" bgcolor=""><div style="text-align:center;"><span style="padding-left:10px;">Arturo Fernández (Rector del ITAM) ExITAM
          </span></div></td>
        </tr>

        <tr>
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>9:15 - 10:00 h</b></div></td>
          <td width="40%" bgcolor=""><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">El Futuro del Sector Automotriz Mexicano</span></div></td>
          <td width="30%" bgcolor=""><div style="text-align:center;">
          <span style="padding-left:10px;">Bruno Cattori (CEO Chrysler México) ExITAM</span><br>
          <span style="padding-left:10px;">Ernesto Hernández (CEO General Motors México) ExITAM</span><br>
          <span style="padding-left:10px;"><b>Moderador:</b> Alejandro Hernández (Vicerrector del ITAM) ExITAM</span>
          </div></td>
        </tr>

        <tr>
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>10:00 - 11:00 h</b></div></td>
          <td width="40%" bgcolor=""><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">Tendencias de Negocios en un Mundo Digital</span></div></td>
          <td width="30%" bgcolor=""><div style="text-align:center;">
          <span style="padding-left:10px;">Santiago Kuribreña (Twitter México)</span><br>
          <span style="padding-left:10px;">Max Linares (CEO Toys for Boys, Mexico)</span><br>
          <span style="padding-left:10px;">Francisco Sordo (Director General UBER México)</span><br>
          <span style="padding-left:10px;"><b>Moderador:</b> Max Linares (CEO Toys for Boys, Mexico)</span>
          </div></td>
        </tr>

        <tr>
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>11:00 - 11:45 h</b></div></td>
          <td width="40%" bgcolor=""><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">Emprendimiento</span></div></td>
          <td width="30%" bgcolor=""><div style="text-align:center;">
          <span style="padding-left:10px;">David Arana (CEO y Fundador Konfio)</span><br>
          <span style="padding-left:10px;">Adolfo Babatz (CEO y Fundador Clip) ExITAM</span><br>
          <span style="padding-left:10px;">Adalberto Flores (CEO y Fundador Kueski)</span><br>
          <span style="padding-left:10px;">Diego Solórzano (CEO y Fundador Carrot) ExITAM</span><br>
          <span style="padding-left:10px;"><b>Moderador:</b> Fernando Lelo de Larrea (CEO y Co-fundador ALL VP)

ExITAM</span>
          </div></td>
        </tr>

        <tr>
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>11:45 - 12:00 h</b></div></td>
          <td width="40%" bgcolor="" coslpan="2"><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">Receso</span></div></td>
          </span></div></td>
          <td></td>
        </tr>

        <tr>
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>12:00 - 13:00 h</b></div></td>
          <td width="40%" bgcolor=""><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">Transformando Organizaciones</span></div></td>
          <td width="30%" bgcolor=""><div style="text-align:center;">
          <span style="padding-left:10px;">Rodrigo Guzmán (CFO Mexichem) ExITAM</span><br>
          <span style="padding-left:10px;">Roberto Palacios (CFO City Express) ExITAM</span><br>
          <span style="padding-left:10px;">America Taracido (CFO Smurfit Carton) ExITAM</span><br>
          <span style="padding-left:10px;"><b>Moderador:</b> Francisco Pérez (Director DAAC ITAM)</span>
          </div></td>
        </tr>

        <tr>
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>13:00 - 13:45 h</b></div></td>
          <td width="40%" bgcolor=""><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">La Industria del Entretenimiento</span></div></td>
          <td width="30%" bgcolor=""><div style="text-align:center;"><span style="padding-left:10px;">Alejandro Soberón (CEO CIE)
          </span></div></td>
        </tr>

        <tr>
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>13:45 - 14:30 h</b></div></td>
          <td width="40%" bgcolor=""><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">Perspectivas del Sector Financiero</span></div></td>
          <td width="30%" bgcolor=""><div style="text-align:center;"><span style="padding-left:10px;">Martin Werner (Dir. Gral. Goldman Sachs México) ExITAM
          </span></div></td>
        </tr>

        <tr>
          <td width="20%" bgcolor=""><div align="left" style="color:; padding:10px;"><b>14:30 h</b></div></td>
          <td width="40%" bgcolor=""><div style="text-align:center;padding:10px;"><span style="padding-left:10px;">Clausura</span></div></td>
          <td width="30%" bgcolor=""><div style="text-align:center;"><span style="padding-left:10px;">Alejandro Hernández (Vicerrector del ITAM) ExITAM
          </span></div></td>
        </tr>

        </tbody>
</table>
<br /><br /><br  />
      </div>


        </div>


      </div>

     <div id="separador" style="width:100%; height:10px; clear:both;"></div>

  <div id="footer" style="background-color: #00685d; border-top: 3px solid #a8d328; color:; line-height:40px;">

  <p>
CC, algunos derechos reservados</p>
</div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-17153173-1', 'cybercast.mx');
  ga('send', 'pageview');

</script>
</center>
</body>

</html>
POV;

print $html1;

print $html2;

?>
