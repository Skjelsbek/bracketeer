<?php
  class Display_bracket
  {
    private $mysqli;
    private $tournament_data;

    // Constructor
    function __construct($mysqli, $tournament_id, $tournament_format)
    {
      $this->mysqli = $mysqli;

      // Fetching tournament data from db
      $sql = "SELECT m.id 'match', p.name 'participant', t.id 'team'
              FROM matches m
              LEFT OUTER JOIN bracket b
               ON b.matches_id = m.id
              LEFT OUTER JOIN teams t
                ON t.id = b.teams_id
              LEFT OUTER JOIN participants p
               ON p.teams_id = t.id
              WHERE m.tournaments_id = " . $tournament_id . ";";
      $result = $this->mysqli->query($sql) or die($this->mysqli->error);

      // Converting tournament data into readable array
      $this->tournament_data = array();
      $i = 0;
      while ($row = $result->fetch_assoc())
      {
        $this->tournament_data[$i] = $row;
        $i++;
      }

      // Select display method
      if ($tournament_format == "Single Elimination")
      {
        $this->display_single_elim();
      }
      else if ($tournament_format == "Double Elimination")
      {
        $this->display_double_elim();
      }
      else
      {
        $this->display_rr_elim();
      }
    }

    private function display_single_elim()
    {
      echo "Single Elimination!";
    }

    private function display_double_elim()
    {
      echo "Double Elimination!";
    }

    private function display_rr_elim()
    {
      echo "Round Robin!";
    }
  }
?>
