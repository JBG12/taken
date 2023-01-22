<?php
include('header.php');
include("classes/user.class.php");
include("classes/UUID.class.php");
include("classes/task.class.php");
?>
<body>
    <div class="content">
    <?php
    if (isset($_SESSION['active'])) {
      // Connect to the database
      $db = database::connect();

      echo '<div class="createButton">';
      echo '<a href="create_task">Taak aanmaken</a>';
      echo '</div>';

      // Check if a task was created, then show confirmation3
      if (!empty($_GET['create'])) {
        echo '<div id="taskCreated" class="taskCreated">';
          echo '<p>Je hebt succesvol een taak aangemaakt.</p> <i onclick="deleteObject()" id="cross" class="fas fa-times-circle"></i>';
        echo '</div>';
      }
      $user_id = $_SESSION['user_id'];
      $is_premium = user::user_premium($user_id);
      echo '<h2 class="tasksTitle"> Uw openstaande taken </h2>';
      echo '<div class="tasks">';
        // echo $user_id;
        $tasks = task::get_open_tasks($user_id);
        if (mysqli_num_rows($tasks) > 0) {
          foreach ($tasks as $value) {
            echo '<a href="./task?id='.$value['uuid'].'">';
            echo '<div id="task" class="task">';
              echo '<p>'.$value['title'].'</p>';
              echo '<p id="timer-'.$value['ID'].'" class="time">Nog:<br>00 dag 00 uren 00 minuten 00 seconden </p>';
              echo '<script>countdownTimer("'.$value['end_time'].'", "'.$value['ID'].'");</script>';
              echo '<progress max="100" value="10" id="bar-'.$value['ID'].'" class="bar"></progress><p class="percentage" id="percentage-'.$value['ID'].'">0%</p>';
              echo '<script>progressBar("'.$value['start_time'].'", "'.$value['end_time'].'", "'.$value['ID'].'");</script>';
            echo '</div>';
            echo '</a>';
          }
        } else {
          echo '<p class="msg">Er zijn geen komende taken. </p>';
        }
      echo '</div>';

      echo '<h2 class="tasksTitle"> Uw komende taken </h2>';
      $tasks = task::get_upcoming_tasks($user_id);
      if (mysqli_num_rows($tasks) > 0) {
        echo '<div class="tasks">';
        foreach ($tasks as $value) {
          echo '<a href="./task?id='.$value['uuid'].'">';
          echo '<div id="task" class="task">';
            echo '<p>'.$value['title'].'</p>';
            echo '<p id="timer-'.$value['ID'].'" class="time">Kan gedaan worden over:<br>00 dag 00 uren 00 minuten 00 seconden </p>';
            echo '<script>countdownTimerToBe("'.$value['ID'].'", "'.$value['start_time'].'");</script>';
          echo '</div>';
          echo '</a>';
        }
        echo '</div>';
      } else {
        echo '<p class="msg">Er zijn geen komende taken. </p>';
      }

      echo '<h2 class="tasksTitle"> Uw verlopen taken </h2>';
      $tasks = task::get_expired_tasks($user_id);
      if (mysqli_num_rows($tasks) > 0) {
        echo '<div class="tasks">';
        foreach ($tasks as $value) {
          echo '<div class="container">';
          echo '<div id="task" class="task taskExpired">';
          echo '<a class="taskhref" href="./task?id='.$value['uuid'].'">';
            echo '<p>'.$value['title'].'</p>';
            echo '<p class="time">Deze taak is verlopen. </p>';
          echo '</a>';
          echo '</div>';
          if ($is_premium == 'Free') {
            echo '<a onclick="overlayMsg()"><i class="fas fa-sync-alt repeat"></i></a>';
          }
          echo '</div>';
        }
        echo '</div>';
      } else {
        echo '<p class="msg">Er zijn geen komende taken. </p>';
      }
    } else {
      header('location: login');
    }
    echo '<div onclick="deleteOverlay()" id="overlay">';
      echo '<p class="txt">Je hebt premium nodig om taken te kunnen herhalen!</p> <a href="./premium"><h2> KOOP PREMIUM </h2></a>';
    echo '</div>';
    ?>
    
    </div>
</body>
</html>