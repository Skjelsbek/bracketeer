<?php
  // If not logged in, promt login page
  if (!isset($_SESSION['uname']))
  {
    header("Location: ?page=login");
  }
?>

<form id="ctf" action="#" method="post">
  <input type="text" id="tname" placeholder="Tournament Name" name="tname">
  <input type="text" id="sname" placeholder="Sport being played" name="sname">

  <select name="format">
    <option value="single">Single Elimination</option>
    <option value="double">Double Elimination</option>
    <option value="rr">Round Robin</option>
  </select>

  <textarea id="participantsta" name="participants" rows="8" cols="80"></textarea>
  <select id="team_size" name="team_size">
    <option>Team Size</option>
  </select>
  <input type="checkbox" name="randomize" value=""> Randomize teams<br>
  <input type="submit" name="ct" value="Create Tournament">
</form>
