<?php
  session_start();
  require_once('./do_not_open_password_inside.php');
  require_once('./sort_teams.php');

  // Fetching tournament data
  $tname = $_POST['tname'];
  $sname = $_POST['sname'];
  $format = $_POST['format'];
  $participants = $_POST['participants'];
  $partarr = explode("\n", str_replace("\r", "", $participants)); // Converting content of textarea to array
  $team_size = $_POST['team_size'];

  // Test Printing
  echo $tname;
  echo "<br>";
  echo $sname;
  echo "<br>";
  echo $format;
  echo "<br>";
  echo $team_size;
  echo "<br>";

  // Randomize teams or seeds, and insert into db
  if (isset($_POST['randomize_teams']))
  {
    shuffle($partarr);
    $teams = sort_teams($partarr, $team_size);
  }
  else if (isset($_POST['randomize_seeds']))
  {
    $teams = sort_teams($partarr, $team_size);
    shuffle($teams);
  }

  // Test print created teams teams
  for ($i=0; $i < count($teams); $i++)
  {
    echo "Team " . $i . ": ";
    for ($j=0; $j < count($teams[$i]); $j++) {
     echo $i . $j . " " . $teams[$i][$j] . ", ";
    }
    echo "<br>";
  }

  // Inserting into db if user logged in
  if (isset($_SESSION['email']))
  {
    // echo $_SESSION['email'];
    //
    // $sql = "SELECT email FROM users;";
    // $result = $mysqli->query($sql);
    // $email = $result->fetch_assoc();
    // echo $email['email'];

    //Fetching user id
    // $sql = "SELECT id FROM users WHERE email = skjelsbek@hotmail.com;";
    // $result = $mysqli->query($sql);
    // $user_id = $result->fetch_assoc();

    // $sql = "SELECT id FROM users
    //         WHERE email = " . $_SESSION['email'] . ";";
    // $result = $mysqli->query($sql);
    // $user_id = $result->fetch_assoc();

    // Insert tournament
    $sql = "INSERT INTO tournaments (name, sport, format, users_id) VALUES (" . $tname . "," . $sname . "," . $format . "," . 1 . ");";
    echo $sql;
    $mysqli->query($sql) or die($mysqli->error);

    // // Insert teams
    // for ($i=0; $i < count($teams); $i++)
    // {
    //   $sql = "INSERT INTO teams (seed) VALUES (" . $i . ");";
    //   $mysqli->query($sql);
    // }
    //
    // // Fetching tournament id
    // $sql = "SELECT id FROM tournaments WHERE name = " . $tname . " AND users_id = " . $user_id . ";";
    // $result = $mysqli->query($sql);
    // $tournament_id = $result->fetch_assoc();
    //
    // // Fetching team ids
    // $sql = "SELECT `AUTO_INCREMENT`
    //         FROM  INFORMATION_SCHEMA.TABLES
    //         WHERE TABLE_SCHEMA = 'bracketeer'
    //         AND   TABLE_NAME   = 'teams';";
    // $result = $mysqli->query($sql);
    // $last_team_id = $result->fetch_assoc() - 1;
    //
    // // Insert participants
    // for ($i=0; $i < count($teams); $i++)
    // {
    //   for ($j=0; $j < count($teams[$i]); $j++) {
    //     $sql = "INSERT INTO participants (name, teams_id, tournaments_id)
    //             VALUES (" . $teams[$i][$j] . "," . $last_team_id - count($teams) + $i . "," . $tournament_id . ");";
    //     $mysqli->query($sql);
    //   }
    // }
    //
    // // Calculate number of matches
    // $number_of_matches = 0;
    // if ($format == "single")
    // {
    //   $number_of_matches = count($teams) - 1;
    // }
    // else if ($format == "double")
    // {
    //   $number_of_matches = (count($teams) - 1) * 2;
    // }
    // else if ($format == "rr")
    // {
    //   $number_of_matches = count($teams) * (count($teams) - 1);
    // }
    //
    // // Insert matches
    // for ($i=0; $i < $number_of_matches; $i++) {
    //   $sql = "INSERT INTO matches (tournaments_id)
    //           VALUES (" . $tournament_id . ")";
    //   $mysqli->query($sql);
    // }
    //
    // // Fetching match id
    // $sql = "SELECT `AUTO_INCREMENT`
    //         FROM  INFORMATION_SCHEMA.TABLES
    //         WHERE TABLE_SCHEMA = 'bracketeer'
    //         AND   TABLE_NAME   = 'matches';";
    // $result = $mysqli->query($sql);
    // $match_id = $result->fetch_assoc() - 1;
    //
    // // Insert bracket
    // for ($i=0; $i < count($teams) ; $i++) {
    //   $sql = "INSERT INTO bracket (matches_tournaments_id, matches_id, teams_id)
    //           VALUES (" . $tournament_id . "," . $match_id . "," . $last_team_id - count($teams) + $i . ");";
    //   $mysqli->query($sql);
    // }
  }
?>
