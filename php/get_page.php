<?php
  // Include connection to db
  require_once('do_not_open_password_inside.php');

  // Query grabbing page titles and content from db
  $sql = "SELECT title, content, has_sub_pages FROM pages WHERE id = '" . $_GET['id'] . "'";
  $result = $mysqli->query($sql);

  if (isset($result))
  {
    // Make query result into readable array
    $result = $result->fetch_assoc();

    echo "<div class='container'>";

    // Printing title with utf8 encoding because of norwegian letters
    echo "<h1>";
    echo utf8_encode($result['title']);
    echo "</h1>";

    if ($result['has_sub_pages']) {
      echo "<div class='background_shader'>
        <div class='leftbtn_container'>
          <img id='leftbtn2' src='../img/arrows.png'>
        </div>
        <div class='rightbtn_container'>
          <img id='rightbtn2' src='../img/arrows.png'>
        </div>
        <div class='news_container' id='scroller2'>";

        require_once('get_sub_page.php');

        echo "</div>
        <div class='dot_container2'></div></div>";
    }

    // Printing content
    echo "<p>";
    echo utf8_encode($result['content']);
    echo "</p></div>";
  }
  else
  {
      header("Location: ?page=home");
  }
?>
