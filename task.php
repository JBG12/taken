<?php
include('header.php');
include("classes/user.class.php");
include("classes/UUID.class.php");
?>
<body>
    <div class="content">
        
    <?php
    if (isset($_SESSION['active'])) {
      // Connect to the database
      $db = database::connect();

      echo '<h2 class="pageTitle"> Taak aanmaken </h2>';
      echo '<div class="createButton">';
      echo '<a href="task.php">Taak aanmaken</a>';
      echo '</div>';

    } else {
      header('location: login.php');
    }


    ?>
    </div>
</body>
</html>