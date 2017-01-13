<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');



/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) 
{
  $_SESSION['oauth_status'] = 'oldtoken';
  //header('Location: ./clearsessions.php');
  $sv = $_SERVER['SERVER_NAME'] . "/itam";
  header('Location: http://' . $sv . '/postt/twitteroauth/clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;


/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code)
{
  /* The user has been verified and the access tokens can be saved for future use */
  $_SESSION['status'] = 'verified';
  $sv = $_SERVER['SERVER_NAME'];
  
  print <<<POV
  <script type="text/javascript">
  window.opener.location.reload();      
    self.close(); 
      
    function closeW()
    {      
      self.close(); 
      window.opener.location.reload();      
    }
  </script>
  <div style="background-color:#FFFFF0;" width="100%" heigth="100%">
    <p>
      Ahora ya puedes enviar tuits desde esta aplicaci&oacute;n, s&oacute;lo cierra esta ventana.
    </p>
  </div>
  <br />
  <a onclick="closeW();" href="javascript:;">Cerrar esta ventana</a>  
POV;

  //die();
  //header('Location: http://' . $sv . '/ciudadano20');
} 
else 
{
  $sv = $_SERVER['SERVER_NAME'];
  /* Save HTTP status for error dialog on connnect page.*/
  //header('Location: ./clearsessions.php');
  header('Location: http://' . $sv . '/postt/twitteroauth/clearsessions.php');
}
?>
