<?php
  class Insertion {
    // Database connection
    private $mysqli;

    // Tournament data
    private $tname;
    private $sname;
    private $format;
    private $partarr;
    private $team_size;

    private $teams;
    private $number_of_matches;

    private $user_id;
    private $tournament_id;
    private $last_team_id;
    private $last_match_id;

    // Constructor
    function __construct($mysqli, $tname, $sname, $format, $partarr, $team_size, $randomize_teams, $randomize_seeds)
    {
      // Setting member variables
      $this->mysqli = $mysqli;
      $this->tname = $tname;
      $this->sname = $sname;
      $this->format = $format;
      $this->partarr = $partarr;
      $this->team_size = $team_size;

      // Sorting teams
      if ($randomize_teams)
      {
        shuffle($this->partarr);
        $this->teams = $this->sort_teams();
      }
      else if ($randomize_seeds)
      {
        $this->teams = $this->sort_teams();
        shuffle($this->teams);
      }
      else
      {
        $this->teams = $this->sort_teams();
      }
    }

    // Check if a number is decimal
    private function is_decimal($val)
    {
      return is_numeric($val) && floor($val) != $val;
    }

    // Creating and returning an array of teams
    private function sort_teams()
    {
      $teams = array();
      if (count($this->partarr) % $this->team_size == 0)
      {
        $g = 0;
        for ($i = 0; $i < (count($this->partarr) / $this->team_size); $i++) {
          for ($j=0; $j < $this->team_size; $j++)
          {
            $teams[$i][$j] = $this->partarr[$g+$j];
          }
          $g += $this->team_size;
        }
      }
      else
      {
        $g = 0;
        for ($i = 0; $i < (int)(count($this->partarr) / $this->team_size) + 1; $i++)
        {
          if ($i != (int)(count($this->partarr) / $this->team_size))
          {
            for ($j=0; $j < $this->team_size; $j++)
            {
              $teams[$i][$j] = $this->partarr[$g+$j];
            }
          }
          else
          {
            for ($j=0; $j < (count($this->partarr) % $this->team_size); $j++)
            {
              $teams[$i][$j] = $this->partarr[$g+$j];
            }
          }
          $g += $this->team_size;
        }
      }
      return $teams;
    }

    // Insert into tournaments tbl
    function insert_tournament()
    {
      // Fetching user id
      $sql = "SELECT id
              FROM users
              WHERE email = '" . $_SESSION['email'] . "';";
      $result = $this->mysqli->query($sql);
      $tmp_user_id = $result->fetch_assoc();
      $this->user_id = $tmp_user_id['id'];

      // Insert tournaments
      $sql = "INSERT INTO tournaments (name, sport, format, users_id)
              VALUES ('" . $this->tname . "','" . $this->sname . "','" . $this->format . "'," . $this->user_id . ");";
      $this->mysqli->query($sql) or die($this->mysqli->error);

      // Fetching tournament id
      $this->tournament_id = $this->mysqli->insert_id;
    }

    // Insert teams tbl
    function insert_teams()
    {
      // Insert teams
      for ($i=0; $i < count($this->teams); $i++)
      {
        $sql = "INSERT INTO teams (seed)
                VALUES (" . ($i + 1) . ");";
        $this->mysqli->query($sql) or die($this->mysqli->error);
      }

      // Fetching last inserted team id
      $this->last_team_id = $this->mysqli->insert_id;
    }

    // Insert participants tbl
    function insert_participants()
    {
      // Insert participants
      for ($i=0; $i < count($this->teams); $i++)
      {
        for ($j=0; $j < count($this->teams[$i]); $j++)
        {
          $sql = "INSERT INTO participants (name, tournaments_id, tournaments_users_id, teams_id)
                  VALUES ('" . $this->teams[$i][$j] . "'," . $this->tournament_id . "," . $this->user_id . "," . ($this->last_team_id - count($this->teams) + $i + 1) . ");";
          $this->mysqli->query($sql) or die($this->mysqli->error);
        }
      }
    }

    // Insert matches tbl
    function insert_matches()
    {
      // Calculate number of matches
      $number_of_matches = 0;
      if ($this->format == "Single Elimination")
      {
        $this->number_of_matches = count($this->teams) - 1;
      }
      else if ($this->format == "Double Elimination")
      {
        $this->number_of_matches = (count($this->teams) - 1) * 2;
      }
      else if ($this->format == "Round Robin")
      {
        $this->number_of_matches = count($this->teams) * (count($this->teams) - 1) / 2;
      }

      // Insert matches
      for ($i=0; $i < $this->number_of_matches; $i++)
      {
        $sql = "INSERT INTO matches (tournaments_id)
                VALUES (" . $this->tournament_id . ")";
        $this->mysqli->query($sql) or die($this->mysqli->error);
      }

      // Fetching match id
      $this->last_match_id = $this->mysqli->insert_id;
    }

    private function calculate_byes()
    {
      // Calculate number of byes
      $closest_pow_of_two = count($this->teams);
      while ($this->is_decimal(log($closest_pow_of_two)/log(2)))
      {
        $closest_pow_of_two++;
      }
      $number_of_byes = $closest_pow_of_two - count($this->teams);

      // Middle and last index of team array
      $middle = ceil(count($this->teams)/2) - 1;
      $last = count($this->teams) - 1;

      // Bye array and counter
      $byes = array();
      $byes_left = $number_of_byes;

      for ($i=0; $i < count($this->teams); $i++) {
        $byes[$i] = false;
      }

      // Calculating byes
      $i = 0;
      while ($byes_left != 0)
      {
        $j = 0;
        while ($j < 4 && $byes_left != 0)
        {
          switch ($j)
          {
            case 0:
              $byes[$last - $i] = true;
              break;
            case 1:
              $byes[0 + $i] = true;
              break;
            case 2:
              $byes[$middle + 1 + $i] = true;
              break;
            case 3:
              $byes[$middle - $i] = true;
              break;
            default:
              echo "Hope this never happens";
              die();
              break;
          }
          $j++;
          $byes_left--;
        }
        $i++;
      }
      return $byes;
    }

    // Insert into bracket tbl
    function insert_bracket()
    {
      $first_match_id = $this->last_match_id - $this->number_of_matches + 1;
      $match_id = $first_match_id;
      $team_id = $this->last_team_id - count($this->teams) + 1;

      if (!$this->is_decimal(log(count($this->teams))/log(2)))
      {
        $added_teams = 0;
        for ($i=0; $i < count($this->teams); $i++)
        {
          if ($added_teams == 2)
          {
            $added_teams = 0;
            $match_id++;
          }
          $sql = "INSERT INTO bracket (matches_tournaments_id, matches_id, teams_id)
          VALUES (" . $this->tournament_id . "," .  $match_id . "," . $team_id++ . ");";
          $this->mysqli->query($sql) or die($this->mysqli->error);
          $added_teams++;
        }
      }
      else
      {
        $byes = $this->calculate_byes();
        $match_id = $this->last_match_id - $this->number_of_matches + 1;
        $team_id = $this->last_team_id - count($this->teams) + 1;
        $added_teams = 0;

        for ($i=0; $i < count($this->teams); $i++)
        {
          if (!isset($byes[$i]))
          {
            if ($added_teams == 2)
            {
              $added_teams = 0;
              $match_id++;
            }
            $sql = "INSERT INTO bracket (matches_tournaments_id, matches_id, teams_id)
            VALUES (" . $this->tournament_id . "," .  $match_id . "," . $team_id++ . ");";
            $this->mysqli->query($sql) or die($this->mysqli->error);
            $added_teams++;
          }
        }

        for ($i=0; $i < count($this->teams); $i++)
        {
          if (isset($byes[$i]))
          {
            if ($added_teams == 2)
            {
              $added_teams = 0;
              $match_id++;
            }
            $sql = "INSERT INTO bracket (matches_tournaments_id, matches_id, teams_id)
            VALUES (" . $this->tournament_id . "," .  $match_id . "," . $team_id++ . ");";
            $this->mysqli->query($sql) or die($this->mysqli->error);
            $added_teams++;
          }
        }
      }
    }
  }
?>
