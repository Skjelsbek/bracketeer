<body>
  <header>
    <div class="container">
      <!--style="background-color: darkgrey"-->
      <div id="branding">
        <!-- The branding is also a link to the home page -->
        <a href="?page=home">
          <h1>
            <span id="highlight">Bracket</span>eer
          </h1>
        </a>
      </div>

      <!-- Navigation bar -->
      <nav id="topics">
        <ul>
          <!-- Info dropdown menu -->
          <li class="dropdown">
            <img class="dropbtn" src="./img/menu.png" height="20px">
            <div class="drop-content">
              <a href="?page=create_tournament">Create Tournament</a>
              <a href="?id=3">About</a>
            </div>
          </li>

          <!-- Login/profile section -->
          <li class="dropdown">
            <?php
              if (isset($_SESSION['uname']))
              {
                echo
                '<li class="dropdown">
                  <a class="dropbtn">' . $_SESSION['uname'] . '</a>
                  <div class="drop-content">
                    <a href="?page=tickets">Mine billetter</a>
                    <form id="logout_form" action="./php/logout.php" method="post">
                      <input id="logout_button" type="submit" value="Logg ut">
                    </form>
                  </div>
                </li>';
              }
              else
              {
                echo
                '<button id="loginbtn">Logg Inn</button>
                <div id="login_drop">
                  <form id="login_form" action="./php/login.php" method="post">
                    <input type="email" placeholder="E-post addresse" name="email">
                    <input type="password" placeholder="Passord" name="passwd">
                    <input id="btn1" type="submit" value="Logg Inn">
                    <p id="create_account">Opprett Konto</p>
                    <p>Glemt Passord</p>
                  </form>

                  <form id="registration_form" action="./php/register.php" method="post">
                    <input type="text" placeholder="Brukernavn" name="uname">
                    <input type="email" placeholder="E-post addresse" name="email">
                    <input type="password" placeholder="Passord" name="passwd">
                    <input id="btn2" type="submit" value="Registrer">
                    <p id="log_in">Har du allerede bruker? Logg Inn</p>
                  </form>
                </div>';
              }
            ?>
          </li>
        </ul>
      </nav>
    </div>
  </header>
