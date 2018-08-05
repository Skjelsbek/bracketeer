<?php
  // Function creating and returning an array of teams
  function sort_teams($partarr, $team_size)
  {
    $teams = array();
    if (count($partarr) % $team_size == 0)
    {
      $g = 0;
      for ($i = 0; $i < (count($partarr) / $team_size); $i++) {
        for ($j=0; $j < $team_size; $j++)
        {
          $teams[$i][$j] = $partarr[$g+$j];
        }
        $g += $team_size;
      }
    }
    else
    {
      $g = 0;
      for ($i = 0; $i < (int)(count($partarr) / $team_size) + 1; $i++)
      {
        if ($i != (int)(count($partarr) / $team_size))
        {
          for ($j=0; $j < $team_size; $j++)
          {
            $teams[$i][$j] = $partarr[$g+$j];
          }
        }
        else
        {
          for ($j=0; $j < (count($partarr) % $team_size); $j++)
          {
            $teams[$i][$j] = $partarr[$g+$j];
          }
        }
        $g += $team_size;
      }
    }
    return $teams;
  }
?>
