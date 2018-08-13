<?php
  session_start();
  require_once('./do_not_open_password_inside.php');
  require_once('./insertion.php');

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
      if ($row['name'] == $_POST['tname'])
      {
        header("Location: ../?page=create_tournament&tournament_exists");
        die();
      }
    }

    // Converting participant textarea to array
    $partarr = explode("\n", str_replace("\r", "", $_POST['participants']));

    // Inserting tournament data in db
    new Insertion($mysqli, $_POST['tname'], $_POST['sname'], $_POST['format'], $partarr, $_POST['team_size'], isset($_POST['randomize_teams']), isset($_POST['randomize_seeds']));

    header("Location: ../?page=tournament&tournament=" . $_POST['tname']);
  }
  else
  {
    header("Location: ../?page=login");
    die();
  }
?>
