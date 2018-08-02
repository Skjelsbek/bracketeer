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
  else if (isset($_GET['id']))
  {
    require_once('./php/get_page.php');
  }
  else
  {
    header("Location: ?page=home");
  }
  // Check if url is valid, redirect to home screen if not
  // if (isset($_GET['id']) && $_GET['id'] > 0 && $_GET['id'] < 8)
  // {
  //   if ($_GET['id'] == 1) {
  //     // id 1 is the home screen, this page is not stored in db, therefor it's included
  //     require_once('./php/home.php');
  //   }
  //   else
  //   {
  //     // Getting page from db
  //     require_once('./php/get_page.php');
  //   }
  // }
  // else if (isset($_GET['page']) && isset($_SESSION['uname']) &&  $_GET['page'] == 'tickets')
  // {
  //   // If a user is logged in, page=ticket is a valid url.
  //   // Displays tickets
  //   require_once('./php/tickets.php');
  // }
  // else
  // {
  //   // Redirection to home screen
  //   header("Location: ?id=1");
  // }

  // End of section
  echo "</section>";

  // Include sidebar
  require_once('./html/sidebar.html');

  // close main section div
  echo "</div>";

  // Include footer
  require_once('./html/footer.html');
?>
