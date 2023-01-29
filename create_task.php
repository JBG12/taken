<?php
include('header.php');
include("classes/user.class.php");
// include("classes/database.class.php");
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
      // for timezone accurate displaying.
      $date_now = date('Y-m-d\TH:i');
      echo '<h2 id="pageTitle" class="pageTitle"> Taak aanmaken </h2>';
      echo '<div class="form">';
        echo '<form class="createTask" method="POST">';
          echo '<input type="text" value="'.$_SESSION['data']['title'].'" name="title" placeholder="Titel*"> </br>';
          echo '<input type="text" value="'.$_SESSION['data']['description'].'" name="description" placeholder="Beschrijving*"> </br>';
          echo '<label for="startTime"> Start Tijd </label>';
          echo '<input type="datetime-local" class="time" value="'.$_SESSION['data']['startTime'].'" name="startTime" placeholder="Begin Tijd*">';
          echo '<label for="startTime"> Eind Tijd </label>';
          echo '<input type="datetime-local" class="time" value="'.$_SESSION['data']['endTime'].'" name="endTime" placeholder="Eind Tijd*"> </br>';

          $is_premium = user::user_premium($user_id);
          if ($is_premium == 'Premium') {
          echo '<label for="repeating">Herhaal de taak</label><br>';
          echo '<select name="rOptions" id="rOptions">';
            echo '<option value="">Niet</option>';
            $values = task::get_repeating_values();
            foreach ($values as $value) {
              echo '<option value="'.$value[0].'">'.$value[1].'</option>';
            }
          echo '</select> </br>';
          }
          echo '<button type="submit" class="create" name="createTask">Taak aanmaken</button>';
        echo '</form>';
        if (isset($_POST['createTask'])) {
          $_SESSION['data'] = $_POST;
          if ($_POST['title'] && $_POST['description'] && $_POST['startTime'] && $_POST['endTime']) {
            $_POST['title']       = database::validate($_POST['title']);
            $_POST['description'] = database::validate($_POST['description']);
            $start_time           = database::validate($_POST['startTime']);
            $end_time             = database::validate($_POST['endTime']);
            $_POST['startTime']   = str_replace("T", " ", $_POST['startTime']);
            $_POST['endTime']     = str_replace("T", " ", $_POST['endTime']);
            $ts                   = strtotime($_POST['startTime']);
            $ts2                  = strtotime($_POST['endTime']);
            if ($ts == false || $ts2 == false) {
              echo '<p id="error" class="error">Correcte data invullen!</p>';
              echo '<script>setErrorMsg("error", "pageTitle");</script>';
              exit();
            }
            $_POST['startTime']   = date('Y-m-d H:i', $ts);
            $_POST['endTime']     = date('Y-m-d H:i', $ts2);
            $_POST['rOptions']    = database::validate($_POST['rOptions']);
            
            $user_id = $_SESSION['user_id']; 
            $post = $_POST;

            $date_now = date('Y-m-d H:i');
            $start_time = task::validateDate($_POST['startTime']);
            $end_time = task::validateDate($_POST['endTime']);
            if (($start_time == true) && ($end_time == true)) {
              $start = new DateTime($_POST['startTime']);
              $start1 = $start->format('Y-m-d H:i');
              $end = new DateTime($_POST['endTime']);
              $end1 = $end->format('Y-m-d H:i');

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
                if (($_POST['endTime'] > $date_now) && ($end1 > $start1)) {
                  $send = task::create_task($post, $user_id);
                } else {
                  echo '<p id="error" class="error">Correcte data invullen!</p>';
                  echo '<script>setErrorMsg("error", "pageTitle");</script>';
                }
              } else {
                echo '<p id="error" class="error">Je hebt het maximale aantal taken bereikt. Wil je meer taken, koop dan ons <a href="./premium">Premium plan</a>.</p>';
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