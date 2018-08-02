<?php
  // logging out by closing the session
  session_start();
  session_unset();
  session_destroy();

  // Relocate to home screen
  header("Location: ../?page=home");
?>
