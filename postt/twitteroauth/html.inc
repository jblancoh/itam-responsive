<div>
  <h2>!Participa!</h2>

  <p>
    Si experimentas problemas para participar, intenta <a href="http://<?php echo $_SERVER['SERVER_NAME'] . "/postt/twitteroauth/clearsessions.php"; ?>">reiniciar tu acceso a twitter</a>.
  </p>
  
  <?php if (isset($menu)) { ?>
    <?php echo $menu; ?>
  <?php } ?>
</div>
<?php if (isset($status_text)) { ?>
  <?php echo '<h3>'.$status_text.'</h3>'; ?>
<?php } ?>
<p>
  <pre>
    <?php print_r($content); ?>
  </pre>
</p>