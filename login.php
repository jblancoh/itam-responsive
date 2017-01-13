<?php

  $sv = $_SERVER['SERVER_NAME'] . "/itam";
 // header("Location: http://" . $sv);


session_start();
include_once('log.php');

if(isLogin())
{
	header("Location: http://" . $sv);
}

$claves = array('chL9nocApC','VpCPJw5K7f','sTRUvmKFqv','BCl7_M-Qf7');

$clave = $_POST['clave'];
$login = false;
$msg = '';


if($clave)
{

   if(in_array($clave, $claves))
    {
      setIsLogin($clave);
      $login = true;

      setLog($clave);
    }

    $msg =<<<POV

    <div class="notification is-danger">
      <button class="delete" onclick="((this).parentNode.remove())"></button>
      Los datos proporcionados son incorrectos
    </div>

POV;

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
  <link href="css/jqueryUI.css" rel="stylesheet" type="text/css" />
  <!-- <link href="css/estilos.css" rel="stylesheet" type="text/css" /> -->
  <link href="css/bulma.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
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
						jQuery(this).addClass('is-danger').attr({placeholder:"El campo no puede ser vacio"});
						error=true;
					}
					else
					{
						jQuery(this).removeClass('is-success').attr({placeholder:""});
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
</head>

<body>
<div id="hidden" style="display:none;"></div>
  <nav class="nav">
    <div class="container">
      <div class="nav-left">
      <a class="nav-item">
      <img src="pics/itam-logo.jpg" height="65px" width="306px" alt="itam logo"/>
      </a>
      </div>
    </div>
  </nav>
  <br>
  <br>
  <section class="section content">
    <div class="columns is-mobile is-multiline">
      <div class="column is-10">
        <div class="column is-12">
          <h3>Seminario de Perspectivas Económicas 2017</h3>

        </div>
        <div class="column is-4">
          $msg
          <form action="login.php" id="logitam" method="post">
            <label class="label">Clave:</label>
            <p class="control">
              <input type="password" id="clave" name="clave" size="40" maxlength="40" class="required input"/>
            </p>
            <p class="control">
            <input class="button is-hovered" type="submit" value="Enviar" style="background-color: #00685d; color: #FFF ;"/>
            </p>
            <input type="hidden" name="s" value="$tm" />
          </form>
        </div>
      </div>
    </div>
  </section>
    <!-- Footer -->
    <footer class="footer" style="background-color: #00685d; border-top: 3px solid #a8d328;">
      <div class="container">
        <div class="content has-text-centered">
          <p style="color: #FFF"class="subtitle">CC, algunos derechos reservados</p>
        </div>
      </div>
    </footer>
</body>
</html>

POV;

echo $html;
}

?>
