<?php
  // If not logged in, promt login page
  if (!isset($_SESSION['uname']))
  {
    header("Location: ?page=login");
  }
?>
