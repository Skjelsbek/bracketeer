<form class="container" action="?page=create_tournament" method="post">
  <input type="submit" name="ctButton" value="Create Tournament">
</form>

<?php
  require_once('./php/do_not_open_password_inside.php');

  if (isset($_SESSION['email']))
  {
    $sql = "SELECT id
            FROM users
            WHERE email = '" . $_SESSION['email'] . "';";
    $result = $mysqli->query($sql) or die($mysqli->error);
    $result = $result->fetch_assoc();

    if (isset($result))
    {
      echo "<div class='container'>";
      echo "<h1>My Tournaments</h1><br>";
      echo "<div class='tournament_list'>";

      $user_id = $result['id'];

      $sql = "SELECT name, sport, format
              FROM tournaments
              WHERE users_id = " . $user_id . ";";
      $result = $mysqli->query($sql) or die($mysqli->error);

      $i = 1;
      while ($row = $result->fetch_assoc())
      {
        echo "<div class='tournament_wrap'>";
        echo "<h2>" . $row['name'] . "</h2>";
        echo "<p> " . $row['sport'] . " - " . $row['format'];
        echo "</div>";
        $i++;
      }
      echo "</div>";
    }
  }
  else
  {
    header("Location: ../?page=login");
    die();
  }
?>
</div>
