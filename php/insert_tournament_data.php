<?php
  session_start();
  require_once('./do_not_open_password_inside.php');
  require_once('./sort_teams.php');
  require_once('./insert_bracket.php');

  // Fetching tournament data
  $tname = $_POST['tname'];
  $sname = $_POST['sname'];
  $format = $_POST['format'];
  $participants = $_POST['participants'];
  $partarr = explode("\n", str_replace("\r", "", $participants)); // Converting content of textarea to array
  $team_size = $_POST['team_size'];

  // Inserting into db if user logged in
  if (isset($_SESSION['email']))
  {
    // Fetching names of tournaments the user already have
    $sql = "SELECT t.name
            FROM tournaments t
            JOIN users u
            ON u.id  = t.users_id
            WHERE u.email = '" . $_SESSION['email'] . "';";
    $result = $mysqli->query($sql) or die($mysqli->error);

    // If one of the tournaments fetched have the same name as the one to create, prompt already exists
    while ($row = $result->fetch_assoc())
    {
      if ($row['name'] == $tname) {
        header("Location: ../?page=create_tournament&tournament_exists");
        die();
      }
    }

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
    else
    {
      $teams = sort_teams($partarr, $team_size);
    }

    // Fetching user id
    $sql = "SELECT id
            FROM users
            WHERE email = '" . $_SESSION['email'] . "';";
    $result = $mysqli->query($sql);
    $user_id = $result->fetch_assoc();

    // Insert tournament
    $sql = "INSERT INTO tournaments (name, sport, format, users_id)
            VALUES ('" . $tname . "','" . $sname . "','" . $format . "'," . $user_id['id'] . ");";
    $mysqli->query($sql) or die($mysqli->error);

    // Fetching tournament id
    $sql = "SELECT id
            FROM tournaments
            WHERE name = '" . $tname . "'
            AND users_id = " . $user_id['id'] . ";";
    $result = $mysqli->query($sql);
    $tournament_id = $result->fetch_assoc();

    // Insert teams
    for ($i=0; $i < count($teams); $i++)
    {
      $sql = "INSERT INTO teams (seed)
              VALUES (" . ($i + 1) . ");";
      $mysqli->query($sql) or die($mysqli->error);
    }

    // Fetching last inserted team id
    $last_team_id = $mysqli->insert_id;

    // Insert participants
    for ($i=0; $i < count($teams); $i++)
    {
      for ($j=0; $j < count($teams[$i]); $j++)
      {
        $sql = "INSERT INTO participants (name, tournaments_id, tournaments_users_id, teams_id)
                VALUES ('" . $teams[$i][$j] . "'," . $tournament_id['id'] . "," . $user_id['id'] . "," . ($last_team_id - count($teams) + $i + 1) . ");";
        $mysqli->query($sql) or die($mysqli->error);
      }
    }

    // Calculate number of matches
    $number_of_matches = 0;
    if ($format == "single")
    {
      $number_of_matches = count($teams) - 1;
    }
    else if ($format == "double")
    {
      $number_of_matches = (count($teams) - 1) * 2;
    }
    else if ($format == "rr")
    {
      $number_of_matches = count($teams) * (count($teams) - 1);
    }

    // Insert matches
    for ($i=0; $i < $number_of_matches; $i++)
    {
      $sql = "INSERT INTO matches (tournaments_id)
              VALUES (" . $tournament_id['id'] . ")";
      $mysqli->query($sql) or die($mysqli->error);
    }

    // Fetching match id
    $last_match_id = $mysqli->insert_id;

    // Insert bracket
    $match_id = $last_match_id - $number_of_matches + 1;
    $team_counter = $last_team_id - count($teams) + 1;
    insert_bracket($mysqli, $tournament_id['id'], $match_id, $team_counter, count($teams));

    header("Location: ../?page=tournament&tournament" . $tournament_id['id']);
  }
  else
  {
    header("Location: ../?page=login");
    die();
  }
?>
