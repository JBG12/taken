<?php
include('header.php');
include("classes/user.class.php");
include("classes/task.class.php");
?>
<body>
    <div class="content">
        
    <?php
    if (isset($_SESSION['active'])) {
      // Connect to the database
      $db = database::connect();

      $where = './index';
      echo task::return_button($where);
      $user_id = $_SESSION['user_id'];

      echo '<h2 id="pageTitle" class="pageTitle"> Taak aanmaken </h2>';
      echo '<div class="form">';
        echo '<form class="createTask" method="POST">';
          echo '<input type="text" value="" name="title" placeholder="Titel*"> </br>';
          echo '<input type="text" value="" name="description" placeholder="Beschrijving*"> </br>';
          echo '<input type="text" timezone="EST" onfocus="(this.type=`date`)" class="time" value="" name="startTime" placeholder="Begin Tijd*">';
          echo '<input type="text" onfocus="(this.type=`date`)" class="time" value="" name="endTime" placeholder="Eind Tijd*"> </br>';

          $is_premium = user::user_premium($user_id);
          if ($is_premium == 'Premium') {
          echo '<label for="repeating">Herhaal de taak</label><br>';
          echo '<select name="rOptions" id="rOptions">';
            echo '<option value="null">Niet</option>';
            $values = task::get_repeating_values();
            foreach ($values as $value) {
              echo '<option value="'.$value[0].'">'.$value[1].'</option>';
            }
          echo '</select> </br>';
          }
          echo '<button type="submit" class="create" name="createTask">Taak aanmaken</button>';
        echo '</form>';
        if (isset($_POST['createTask'])) {
          if ($_POST['title'] && $_POST['description'] && $_POST['startTime'] && $_POST['endTime']) {

            $user_id = $_SESSION['user_id']; 
            $post = $_POST;
            $date_now = date('Y-m-d');
            $start_time = task::validateDate($_POST['startTime']);
            $end_time = task::validateDate($_POST['endTime']);
            if (($start_time == true) && ($end_time == true)) {
              $start = new DateTime($_POST['startTime']);
              $start1 = $start->format('y-m-d');
              $end = new DateTime($_POST['endTime']);
              $end1 = $end->format('y-m-d');

              $send = '';
              $allowed = true;
              // Check if a free user has reached his max ammount of tasks (2)
              if ($is_premium == 'Free') {
                $taskAmount = user::user_tasks($user_id);
                if ($taskAmount >= 2) {
                  $allowed = false;
                }
              }
              if ($allowed == true) {
                // check dates
                if (($end1 > $date_now) && ($end1 > $start1)) {
                  $send = task::create_task($post, $user_id);
                } else {
                  echo '<p id="error" class="error">Correcte data invullen!</p>';
                  echo '<script>setErrorMsg("error", "pageTitle");</script>';
                }
              } else {
                echo '<p id="error" class="error">Je hebt het maximale aantal taken bereikt. Wil je meer taken, koop dan ons Premium plan.</p>';
                echo '<script>setErrorMsg("error", "pageTitle");</script>';
              }
              if ($send) {
                echo '<p id="succes" class="error">De taak is aangemaakt.</p>';
                header("Location:index?create=true");
              }
            } else {
              echo '<p id="error" class="error">Correcte datum types invullen.</p>';
              echo '<script>setErrorMsg("error", "pageTitle");</script>';
            }
            
          } else {
            echo '<p id="error" class="error">Je moet alle verplichte velden invullen!</p>';
            // change position of error msg
            echo '<script>setErrorMsg("error", "pageTitle");</script>';
          }
          unset($_POST);
        }
      echo '</div>';
    } else {
      header('location: login');
    }

    ?>
    </div>
</body>
</html>