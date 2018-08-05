<?php
  function is_decimal($val)
  {
    return is_numeric($val) && floor($val) != $val;
  }

  function insert_bracket($mysqli, $tournament_id, $match_id, $team_counter, $number_of_teams)
  {
    $comparator = floor($number_of_teams/4) + 0.5;

    if (!is_decimal(log($number_of_teams)/log(2)))
    {
      $match_count = 0;
      for ($i=0; $i < $number_of_teams; $i++)
      {
        if ($match_count == 2)
        {
          $match_count = 0;
          $match_id++;
        }
        $sql = "INSERT INTO bracket (matches_tournaments_id, matches_id, teams_id)
                VALUES (" . $tournament_id . "," .  $match_id . "," . $team_counter++ . ");";
        $mysqli->query($sql) or die($mysqli->error);
        $match_count++;
      }
    }
    else if ($number_of_teams/4 < $comparator)
    {
      echo "behind";
      die();
    }
    else if ($number_of_teams/4 > $comparator)
    {
      echo "one in front";
      die();
    }
    else
    {
      echo "two in front";
      die();
    }
  }
?>
