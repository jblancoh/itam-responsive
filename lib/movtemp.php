<script type="text/javascript">
  setTimeout(video, 10000)
  function video()
  {
    window.location = "<?php print $url ?>";
  }
  
  function screensize()
  {
    alert('Your resolution is '+screen.width+'x'+screen.height);
    alert(window.orientation)
  }
    
</script>
<style>
body 
{
	background: url(<?php print $background ?>) no-repeat;
	font: 14px/18px "Lucida Grande", Lucida, Verdana, sans-serif;
	color: #ffffff;
	background-color: #000000; 
}

#titulo.iPhone
{
  top: 0px;
	position: absolute; 
	right: 10%; 
	float: right; 
	text-align: right; 
	font-size: 22px;
	font-weight:900;
	line-height:125%;
}

#navegador.iPhone
{
	position: absolute; 
	float: right; 
	right: 10%; 
	top: 260px; 
	font-size:20px;
	text-align: right;
	margin-bottom: 2px;
	line-height:125%;
	
}

#redireccion.iPhone
{
	position: absolute; 
	float: right; 
	right: 10%; 
	top: 320px; 
	font-size:10px; 
	text-align: right;
}

#titulo.iPad
{
	position: absolute; 
	right: 45px; 
	float: right; 
	text-align: right; 
	font-size: 40px;
	line-height:125%;
}

#navegador.iPad
{
	position: absolute; 
	float: right; 
	right: 45px; 
	top: 420px; 
	font-size:36px; 
	text-align: right;
	line-height:200%;
}

#redireccion.iPad
{
	position: absolute; 
	float: right; 
	right: 45px; 
	top: 590px; 
	font-size:16px; 
	text-align: right;
}


</style>

<meta name="viewport" content="width=<?php print $viewportwidth ?>" />
<body class="<?php print $class ?>">
  <center>
    <div style="width: <?php print $viewportwidth ?>px; height: <?php print $viewportheight ?>px;" id="container">
          <br/>
          <div id="titulo" class="<?php print $class ?>"> <b>Informe del Senador Carlos Navarrete</b> </div>
          <div id="navegador" class="<?php print $class ?>"> Tu <b><?php print $navegador ?></b><br>será dirigido al video móvil</div>
       		<div id="redireccion" class="<?php print $class ?>"> <a style="color: #ffffff" href="<?php print $url ?>">Si no redirecciona automaticamente da click aquí</a> </div>
      <a onclick="screensize();">  </a>
    
        
    </div>
  </center>
</body>

   


