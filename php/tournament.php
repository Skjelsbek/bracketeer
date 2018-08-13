<?php
  require_once('./php/do_not_open_password_inside.php');
  require_once('./php/display_bracket.php');

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

      $sql = "SELECT id, sport, format
              FROM tournaments
              WHERE users_id = " . $user_id . "
              AND name = '" . $tournament_name . "';";
      $result = $mysqli->query($sql) or die($mysqli->error);
      $result = $result->fetch_assoc();

      if (isset($result))
      {
        new Display_bracket($mysqli, $result['id'], $tournament_name, $result ['sport'], $result['format']);        
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
