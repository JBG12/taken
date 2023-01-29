<?php
include('header.php');
include("classes/user.class.php");
// include("classes/database.class.php");
include("classes/UUID.class.php");
include("classes/task.class.php");
?>
<body>
    <div class="content">
    <?php
    if (isset($_SESSION['active'])) {
      // Connect to the database
        $db = database::connect();
        $task_id = $_GET['id'];
        $user_id = $_SESSION['user_id'];
        $task_exists = task::check_task($task_id);
        // check if task exists
        if (mysqli_num_rows($task_exists) > 0) {
            $where = './index';
            echo task::return_button($where);

            $title = task::get_task_title($task_id);
            echo '<h2 id="pageTitle" class="pageTitle"> Taak: '.$title.'</h2>';

            $task = task::get_specific_task($task_id);
            echo '<div class="form">';
            echo '<form class="createTask" method="POST">';
                echo '<input type="text" value="'.$task['title'].'" name="title"> </br>';
                echo '<input type="text" value="'.$task['description'].'" name="description"> </br>';
                // for timezone accurate displaying.
                $ts                   = strtotime($task['start_time']);
                $task['start_time']   = date('Y-m-d\TH:i', $ts);
                $ts                   = strtotime($task['end_time']);
                $task['end_time']     = date('Y-m-d\TH:i', $ts);
                echo '<label for="startTime"> Start Tijd </label>';
                echo '<input type="datetime-local" class="time" value="'.$task['start_time'].'" name="startTime">';
                echo '<label for="startTime"> Eind Tijd </label>';
                echo '<input type="datetime-local" class="time" value="'.$task['end_time'].'" name="endTime"> </br>';

                $is_premium = user::user_premium($user_id);
                if ($is_premium == 'Premium') {
                    echo '<label for="repeating">Herhaal de taak</label><br>';
                    echo '<select class="select" name="rOptions" id="rOptions">';
                        $repeat_name = task::task_repeat_name($task_id);
                        if (!empty($repeat_name)) {
                          echo '<option value="'.$task['repeat_id'].'">'.$repeat_name.'</option>';
                        }
                        echo '<option value="">Niet</option>';
                        $values = task::get_repeating_values();
                        foreach ($values as $value) {
                            if ($task['repeat_id'] != $value[0]) {
                              echo '<option value="'.$value[0].'">'.$value[1].'</option>';
                            }
                        }
                    echo '</select> </br>';
                }
                echo '<button type="submit" class="create" name="updateTask">Taak Bijwerken</button><br>';
                echo '<button type="submit" class="create del" name="deleteTask">Taak Verwijderen</button>';
            echo '</form>';
            echo '</div>';

            if (isset($_POST['updateTask'])) {
                if ($_POST['title'] && $_POST['description'] && $_POST['startTime'] && $_POST['endTime']) {
                    $_POST['title']       = database::validate($_POST['title']);
                    $_POST['description'] = database::validate($_POST['description']);
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
                    
                    $post = $_POST;
                    $date_now = date('Y-m-d H:i');
                    $send = '';
                    $user_id = $_SESSION['user_id']; 
                    $start_time = task::validateDate($_POST['startTime']);
                    $end_time = task::validateDate($_POST['endTime']);
                    if (($start_time == true) && ($end_time == true)) {

                      $start_date = new DateTime($_POST['startTime']);
                      $end_date = new DateTime($_POST['endTime']);
                      $start_date->format('Y-m-d H:i');
                      $end_date->format('Y-m-d H:i');
                      
                      if (($end_date > $date_now) && ($end_date > $start_date)) {
                        $send = task::update_task($post, $task_id);
                      } else {
                        echo '<p id="error" class="error">Correcte data invullen!</p>';
                        echo '<script>setErrorMsg("error", "pageTitle");</script>';
                      }
                      if ($send) {
                        header("Location:task?id=".$task_id);
                      }
                    } else {
                      echo '<p id="error" class="error">Correcte data invullen!</p>';
                      echo '<script>setErrorMsg("error", "pageTitle");</script>';
                    }
                }
            }
            if (isset($_POST['deleteTask'])) {
              $send = task::delete_task($task_id);

              if ($send) {
                header("Location:index");
              } else {
                echo '<p id="error" class="error">Error</p>';
                echo '<script>setErrorMsg("error", "pageTitle");</script>';
              }
            } 
        } else {
          header('location: index');
        }

    } else {
      header('location: login');
    }
    ?>
    </div>
</body>
</html>