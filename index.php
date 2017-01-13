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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link href="css/estilos.css" rel="stylesheet" type="text/css" /> -->
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
  <nav class="nav">
    <div class="container">
      <div class="nav-left nav-left2">
        <a class="nav-item">
          <img src="pics/itam-logo.jpg" height="65px" width="306px" alt="itam logo"/>
        </a>
      </div>
    </div>
  </nav>
  <!-- <div id="hidden" style="display:none;"></div> -->
  <br>
  <br>
  <section class="section">
    <div class="columns is-desktop">
      <div class="column is-two-thirds-desktop is-full-tablet is-offset-1-desktop">
        <div class="tile is-ancestor">
          <div class="tile is-parent">
            <div class="tile is-child">
              <div class="column is-offset-1-desktop is-12-desktop">
                <img src="pics/logo_seminario_2016.png" height="65px" width="306px"/>
              </div>
              <div id="cybercast" class="column is-offset-1-desktop is-12-desktop">
                <figure class="image is-16by9">
                  <img src="http://bulma.io/images/placeholders/640x360.png">
                </figure>
              </div>
              <!--<h1>Seminario de Perspectivas Empresariales 2016</h1>-->
              <!--<h2>Reformas en Acción</h2>-->
              <!-- Cybercast incrustation -->

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
              <div id="agenda" class="column is-offset-1-desktop is-12-desktop"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-one-third-desktop is-12-tablet is-offset-1-desktop " style="margin-top:95px !important;">
        <div class="column is-half-table is-offset-one-quarter-tablet is-offset-1-desktop is-12-desktop">
          <a class="twitter-timeline" href="https://twitter.com/hashtag/EmpresarialesITAM" data-widget-id="530400779811577856">Tweets sobre #EmpresarialesITAM</a>
          <script>
          !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
          </script>
        </div>
      </div>
    </div>
POV;

$html2 =<<<POV

  <div class="columns is-desktop">
    <div class="column is-mobile content" style="overflow: auto">
      <div class="has-text-centered">
        <h2 class="subtitle">PROGRAMA</h2>
      </div>
      <table class="table is-narrow" >
        <thead>
          <tr>
            <th class="">HORARIO</th>
            <th>TEMA</th>
            <th >EXPOSITOR</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>8:00 - 9:00 h</th>
            <td>Registro</td>
            <td></td>
          </tr>
          <tr>
            <th class="is-narrow">9:00 - 9:10 h</th>
            <td class="is-narrow">Bienvenida</td>
            <td class="is-narrow"><span>Lorenzo Meade (Presidente de la Asociación de Ex Alumnos) ExITAM</span></td>
          </tr>
          <tr>
            <th>9:10 - 9:15 h</th>
            <td>Inauguración</td>
            <td>Arturo Fernández (Rector del ITAM) ExITAM</td>
          </tr>
          <tr>
            <th>9:15 - 10:00 h</th>
            <td>El Futuro del Sector Automotriz Mexicano</td>
            <td>
              <span>Bruno Cattori (CEO Chrysler México) ExITAM</span>
              <span>Ernesto Hernández (CEO General Motors México) ExITAM</span>
              <span><b>Moderador:</b> Alejandro Hernández (Vicerrector del ITAM) ExITAM</span>
            </td>
          </tr>
          <tr>
            <th>10:00 - 11:00 h</th>
            <td class="is-narrow">Tendencias de Negocios en un Mundo Digital</td>
            <td>
              <span>Santiago Kuribreña (Twitter México)</span>
              <span>Max Linares (CEO Toys for Boys, Mexico)</span>
              <span>Francisco Sordo (Director General UBER México)</span>
              <span><b>Moderador:</b> Max Linares (CEO Toys for Boys, Mexico)</span>
            </td>
          </tr>
          <tr>
            <th>11:00 - 11:45 h</th>
            <td><span style="padding-left:10px;">Emprendimiento</td>
              <td>
                <span>David Arana (CEO y Fundador Konfio)</span>
                <span>Adolfo Babatz (CEO y Fundador Clip) ExITAM</span>
                <span>Adalberto Flores (CEO y Fundador Kueski)</span>
                <span>Diego Solórzano (CEO y Fundador Carrot) ExITAM</span>
                <span><b>Moderador:</b> Fernando Lelo de Larrea (CEO y Co-fundador ALL VP)ExITAM</span>
              </td>
            </tr>
            <tr>
              <th>11:45 - 12:00 h</th>
              <td>Receso</td>
              <td></td>
            </tr>
            <tr>
              <th>12:00 - 13:00 h</th>
              <td>Transformando Organizaciones</td>
              <td>
                <span>Rodrigo Guzmán (CFO Mexichem) ExITAM</span>
                <span>Roberto Palacios (CFO City Express) ExITAM</span>
                <span>America Taracido (CFO Smurfit Carton) ExITAM</span>
                <span><b>Moderador:</b> Francisco Pérez (Director DAAC ITAM)</span>
              </td>
            </tr>
            <tr>
              <th>13:00 - 13:45 h</th>
              <td>La Industria del Entretenimiento</td>
              <td>Alejandro Soberón (CEO CIE)</td>
            </tr>

            <tr>
              <th class="is-narrow">13:45 - 14:30 h</th>
              <td>Perspectivas del Sector Financiero</td>
              <td>Martin Werner (Dir. Gral. Goldman Sachs México) ExITAM</td>
            </tr>

            <tr>
              <th>14:30 h</th>
              <td>Clausura</td>
              <td>Alejandro Hernández (Vicerrector del ITAM) ExITAM</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
  <footer class="footer" style="background-color: #00685d; border-top: 3px solid #a8d328;">
    <div class="container">
      <div class="content has-text-centered">
        <p style="color: #FFF"class="subtitle">CC, algunos derechos reservados</p>
      </div>
    </div>
  </footer>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-17153173-1', 'cybercast.mx');
ga('send', 'pageview');

</script>
</body>
</html>
POV;

print $html1;

print $html2;

?>
