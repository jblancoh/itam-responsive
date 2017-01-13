<?php
/**
 * paulino@adigital.com.mx
 * 
 * @file
 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */


// ESTE LO PASO AL INDEX PRINCIPAL
//session_start();

// Es petición ajax? OJO, es por HTTP GET!!!!
$ajx = $_GET['ajx'];
if($ajx == 1)
  session_start();

$sentwt = $_GET['sentwt'];
$urlajx = "/itam/postt/twitteroauth/index.php";

$hasht = "#EmprendedorITAM #InnovacionITAM ";
/* Load required lib files, oauth del twitter. */
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
$sv = $_SERVER['SERVER_NAME'] . "/itam";

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) 
{
  session_start();
  session_destroy();

  // wind = window.open(\"/?P=buscarprov\",\"wind\",\"width=640,height=480,scrollbars=yes\");
  
  $content =<<<POV
    <a href="javascript:;" onclick="window.open('http://$sv/postt/twitteroauth/redirect.php','wind','width=800,height=400,scrollbars=yes')">
                 <img border="0" src="http://$sv/pics/entrar.jpg" alt="Accede con tu cuenta de twitter"/></a>  
POV;
  
  /*
  $content = '<a href="http://' . $sv . '/ciudadano20/postt/twitteroauth/redirect.php">
                 <img border="0" src="http://' . $sv  . '/ciudadano20/postt/twitteroauth/images/lighter.png" alt="Accede con tu cuenta de twitter"/></a>';  
  */
  if($ajx == 1)
  {
    print json_encode(array('success' => false, 'response' => 'Algo estuvo mal :('));
    die();
  }
}
else
{
  //var_dump($_SESSION);
  /* Get user access tokens out of the session. */
  $access_token = $_SESSION['access_token'];  
  /* Create a TwitterOauth object with consumer/user tokens. */
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
  if($ajx == 1 && $sentwt)
  {
    $response = $connection->post('statuses/update', array('status' => stripslashes($sentwt)));    
    //var_dump(stripslashes($sentwt));    
    print json_encode(array('success' => true, 'response' => $response));
    die();    
  }
  
  $jspre =<<<POV
  <script type="text/javascript">
    function clrT()
    {
      jQuery('#sentwtstatus').text('');       
      jQuery('#sentwt').val('$hasht');
      jQuery("#sentwtcount").text('$hasht'.length)
    }
    
    function sndTwt()
    {
      var valuechar = jQuery('#sentwt').val(); 
      if(valuechar.length > 140)
      {
        alert('Recuerda que twitter sólo te permite 140 caracteres :), favor de  recortar tu comentario');        
      }
      else
      {
        if(valuechar.length == 0)
          alert('Debes escribir algo');
        else
        {
          jQuery('#sentwtstatus').text('Enviando...');
          //alert(jQuery('#sentwt').val());
          jQuery.ajax(
          {
            type: 'GET',
            async: true,
            dataType: 'json',
            url: '$urlajx' + '?ajx=1&sentwt=' + Url.encode(valuechar),
            success: function(data)
                     {   
                       jQuery('#sentwtstatus').text('Tuit enviado!');
                       jQuery('#sentwt').val('$hasht'); 
                       //self.dataserie = data;                           
                       //self.counters = data.data.counters;
                     },
            error: function(data)
               {    
                 
                 jQuery('#sentwtstatus').text('Error en el envío, puedes intentar nuevamente' + data);
               }
            }
         );  
        }
      }
    } 
    

/**
*
*  URL encode / decode
*  http://www.webtoolkit.info/
*
**/
 
 var Url = {
 
	// public method for url encoding
	encode : function (string) {
		return escape(this._utf8_encode(string));
	},
 
	// public method for url decoding
	decode : function (string) {
		return this._utf8_decode(unescape(string));
	},
 
	// private method for UTF-8 encoding
	_utf8_encode : function (string) 
	  {
		string = string.replace(/\\r\\n/g,'\\n');
		
		var utftext = "";
 
		for (var n = 0; n < string.length; n++) 
		{
 
			var c = string.charCodeAt(n);
 
			if (c < 128) 
			{
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) 
			{
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else 
			{
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
		} 
		return utftext;
	},
 
	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
 
		while ( i < utftext.length ) 
		{
			c = utftext.charCodeAt(i);
 
			if (c < 128) 
			{
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) 
			{
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else 
			{
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
		}
 
		return string;
	}
}
  </script>
POV;
  // TODO Este pasarlo al CSS
  $postarea =<<<POV
  
  <div style="width:290px; border: 0px"> 
    <div style="width:290px;display:block; ">
      <div style="width:45%px; float:left;"><h3>¿Y tu qué piensas?</h3></div>
      <div style="width:45%px; float:right;" id="sentwtcount" class="tcounterg">0</div>      
    </div>
    <div style="display:block; width:290px;">
      <textarea id="sentwt" style="width:260px;" rows="4">$hasht</textarea>
    </div>    
    <div style="width:290px; padding:3px 0px 0px 0px;">
      &nbsp;<input type="button" class="bt" onclick="sndTwt();" />&nbsp;&nbsp;      
      <div class="tcounterstatus"><a href="javascript:;" onclick="clrT();">Limpiar</a></div>&nbsp;&nbsp;
      <div id="sentwtstatus" class="tcounterstatus">&nbsp;</div>
    </div>
  </div>
POV;
  
  $jspost =<<<POV
  <script type="text/javascript">    
    jQuery("#sentwt").keyup(
                        function(event) 
                        {
                          var valuechar = jQuery(this).val();
                          vlength = valuechar.length;
                          if(vlength < 141)
                          {
                            jQuery("#sentwtcount").removeClass('tcounterr').addClass('tcounterg');
                            jQuery("#sentwtcount").text(vlength);
                          }
                          else
                          {
                            jQuery("#sentwtcount").removeClass('tcounterg').addClass('tcounterr');
                            jQuery("#sentwtcount").text('-' + (vlength - 140));
                          }  
                        }
                      );
                      
       clrT();
  </script>
POV;
  
  /* If method is set change API call made. Test is called by default. */
  //$content = $connection->get('account/verify_credentials');  
  $content = "$jspre $postarea $jspost";
}

$content =<<<POV
  <p style="padding:5px;">
    Si experimentas problemas para participar, intenta <a href="http://$sv/postt/twitteroauth/clearsessions.php">reiniciar tu acceso a twitter</a>.
  </p>
  $content
POV;

echo $content;
?>
