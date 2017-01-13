<?php
/**
 * @file
 * Clears PHP sessions and redirects to the connect page.
 */
 
/* Load and clear sessions */
session_start();
session_destroy();
 
$sv = $_SERVER['SERVER_NAME'] . "/itam/";
/* Redirect to page with the connect to Twitter option. */
header('Location: http://' . $sv);
?>