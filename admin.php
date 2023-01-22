<?php
include('header.php');
include("classes/user.class.php");
include("classes/UUID.class.php");
include("classes/task.class.php");
?>
<body>
    <div class="content">
    <?php
    if ((isset($_SESSION['active'])) && ($_SESSION['admin'] == true)) {
      // Connect to the database
        $db = database::connect();
        // check if task exists
        $where = './index';
        echo task::return_button($where);
        echo '<h2 id="pageTitle" class="pageTitle"> Admin Paneel </h2>';

        echo '<div class="gridcontainer">';
            echo '<div class="accounts">'; 
                echo '<h3 id="subTitle" class="subTitle"> Accounts beheren </h3>';
                $users = user::get_users();
                echo '<div class="users">';
                foreach ($users as $value) {
                    $type = user::user_premium($value['ID']);
                    echo '<div class="user">';
                        echo '<p class="email">'.$value['email'].'</p>';
                        echo '<form onchange="onChanges(`'.$value['ID'].'`)" id="'.$value['ID'].'" method="POST">';
                            // echo '<p class="type">'.$type.'</p>';
                            echo '<input type="hidden" name="id" value="'.$value['ID'].'">';
                            echo '<select class="select" id="'.$value['ID'].'" name="rOptions">';
                                $display_type = user::user_premium($value['ID']);
                                echo '<option value="'.$value['type_id'].'">'.$display_type.'</option>';
                                $types = user::get_types();
                                foreach ($types as $type) {
                                    if ($type[1] == $display_type){
                                        // already shows that type
                                    } else {
                                        echo '<option value="'.$type[0].'">'.$type[1].'</option>';
                                    }
                                }
                            echo '</select>';
                        echo '</form>';
                    echo '</div>';
                }
                echo '</div>';
            echo '</div>';
            if ((!empty($_POST['rOptions'])) && (!empty($_POST['id']))) {
                // update user
                $post = $_POST;
                $send = user::update_user($post);
                if($send) {
                    header("Location:admin");
                }
            }

            echo '<div class="tarief">';
                echo '<h3 id="subTitle" class="subTitle"> Tarief bewerken </h3>';
                echo '<p class="email">Het tarief voor het betaalde plan wordt hier weergegeven. <br>Dit bedrag wordt per maand gerekend.</p>';
                echo '<form id="tariefForm" method="POST">';
                    // get current value
                    $tarief = user::get_tarief();
                    echo '&#8364 <input type="number" min="1" max="1000" value="'.$tarief.'" name="thisTarief"><br>';
                    echo '<button type="submit" class="save" name="updateTarief">Opslaan</button>';
                echo '</form>';
            echo '</div>';

        echo '</div>';
        if (isset(($_POST['updateTarief']))) {
            if (($_POST['thisTarief'] >= 1) && ($_POST['thisTarief'] < 1000)) {
                $tarief = $_POST['thisTarief'];
                $send = user::update_tarief($tarief);
                if ($send) {
                    header("Location:admin");
                }
            } else {
                echo '<p id="error" class="error">Correcte data invullen!</p>';
                echo '<script>setErrorMsg("error", "pageTitle");</script>';
            }
        }
    } else {
      header('location: index');
    }
    ?>
    </div>
</body>
</html>