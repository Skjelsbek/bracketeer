<?php
  require_once('./php/do_not_open_password_inside.php');

  if (isset($_SESSION['email']))
  {
    $tournament_name = $_GET['tournament'];

    $sql = "SELECT id
            FROM users
            WHERE email = '" . $_SESSION['email'] . "';";
    $result = $mysqli->query($sql) or die($mysqli->error);
    $result = $result->fetch_assoc();

    if (isset($result))
    {
      $user_id = $result['id'];

      $sql = "SELECT sport, format
              FROM tournaments
              WHERE users_id = " . $user_id . "
              AND name = '" . $tournament_name . "';";
      $result = $mysqli->query($sql) or die($mysqli->error);
      $row = $result->fetch_assoc();

      if (isset($row))
      {
        echo "<div class='container'>";
        echo "<h1>" . $tournament_name . "</h1>";
        echo "<p> " . $row['sport'] . " - " . $row['format'];
        echo "</div>";
      }
      else
      {
        header("Location: ../?page=my_tournaments&tournament=not_found");
        die();
      }
    }
  }
  else
  {
    header("Location: ../?page=login");
    die();
  }
?>
