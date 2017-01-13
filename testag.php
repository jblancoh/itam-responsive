<?php 


$test =<<<POV

 <script src="http://www.google.com/jsapi"></script>
  <script type="text/javascript">
  google.load("jquery", "1.4");
  // Usamos este para el swfoject?
  google.load("swfobject", "2.2");
  
  google.setOnLoadCallback(
     function()
     {      
        loadIni();
     });
     
   function loadIni() 
   {
     jQuery.noConflict();
     loadAgenda();
   }
   
   function loadAgenda()  
   {
	   jQuery.getJSON('/laagenda.php', function(data) 
	   {
	     if(typeof data == 'object')
	     {
	       if(typeof data.agenda  != 'undefined')
	       {
	         jQuery('#agenda').html(data.agenda);
	         var st = setTimeout(function(){loadAgenda();}, 5000);
	       }
	     }
	  });
  }

  </script>
  
  
  
   <div id="agenda">
   
   </div>
  
   
   <input type="button" onclick="loadAgenda();" value="click" />
   
POV;


print $test;

?>