  <!-- navBar -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
    <div class="container-fluid">
      <!-- Logo or site name -->
      <a class="navbar-brand" href="#">Uploads</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>


      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- the buttons -->
        <ul class="navbar-nav mb-2 mb-lg-0">
       <?php
        if(htmlspecialchars($_SESSION["id"]== 1)){
          
          echo'';
          echo'<li class="nav-item">';
            echo'<a class="nav-link active" aria-current="page" href="./users.php">Dashboard</a></li>';
            echo '<li class="nav-item">';
            echo'<a class="nav-link" href="allfiles.php"> All Files</a></li>';

        }
        ?>
          <li class="nav-item">
            <a class="nav-link" href="files.php"> My Files</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"> vpn</a>
          </li>
        </ul>
        <!-- /the buttons -->

        <!-- settings button ( on the right) -->
        <ul class="navbar-nav ms-auto pe-5 mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?= htmlspecialchars($_SESSION["username"]); ?>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="./settings.php">Settings</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="./logout.php">Log Out</a></li>
            </ul>
          </li>
        </ul>
        <!-- settings button ( on the right) -->

      </div>
    </div>
  </nav>
<!-- /navBar -->
