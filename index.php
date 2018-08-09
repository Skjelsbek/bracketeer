<?php
  // Starts the session
  session_start();

  require_once('./html/head.html');
  require_once('./php/header.php');

  // Start main section and section
  echo "<div class='main_section'>";
  echo "<section>";

  $pageFound = false;
  if (isset($_GET['page']))
  {
    $test = './html/' . $_GET['page'] . '.html';
    if (file_exists($test))
    {
      require_once($test);
      $pageFound = true;
    }

    $test = './php/' . $_GET['page'] . '.php';
    if (!$pageFound && file_exists($test))
    {
      require_once($test);
    }
  }
  else
  {
    header("Location: ?page=home");
  }

  // End of section
  echo "</section>";

  // Include sidebar
  require_once('./html/sidebar.html');

  // close main section div
  echo "</div>";

  // Include footer
  require_once('./html/footer.html');
?>
