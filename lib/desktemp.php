<body>
<center>
<div id="container">
  <div id="header">
  	<div id="menu" style="float:right; width:940px; margin-right:30px;">
    	<ul id="mainlevel-nav">
        	<li><a href="http://www.carlosnavarrete.org/" target="_blank" >VISITA EL SITIO DEL SENADOR CARLOS NAVARRETE</a></li>
		</ul>	
    </div>
  <img src="pics/spacer.gif" height="130px" width="855px" />
  
  </div>
  <div id="contenido"> 
    <div id="bloqueizquierdo">
    	<h3>Informe Navarrete</h3>
	      <?php print $playerfl; ?>
      <div style="width:100%; height:10px; clear:both;"></div>
      
    </div>
    <div id="bloquederecho"> 
    
        <div id="bloquederecho">    
          <h3>#VamosJuntos en Twitter</h3>
          <div id="fillin">
            Mensajes de Twitter
          </div>
          <script type="text/javascript" src="/js/twitterapi.js"></script>
          <div id="separador" style="height: 10px;"><img src="pics/dot.gif" alt="" /></div>
          <div id="cajatwitter">
            <h2 class="tituloprincipalh2">Participa en la conversaci&oacute;n</h2>
            <?php include("postt/twitteroauth/index.php"); ?>
          </div>
        </div>   
      	<!-- <img src="http://cybercast.mx/comunicacionsocial/pics/marquesina.jpg" style="margin-top:30px;" />-->
      </div> 
  
      <div id="separador" style="width:100%; height:60px; clear:both;"></div>
  <div id="footer"><p>Este sitio se realiza con recursos personales <br />
Todos los Derechos Reservados 2011</p></div>
</div>  
</center>
</body>
