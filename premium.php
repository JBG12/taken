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

        $where = './index';
        echo task::return_button($where);

        echo '<p> Koop ons premium pakket en krijg toegang tot de volgende functionaliteiten:</p>';
        echo '<ul class="list">';
            echo '<li>Een oneindig aantal taken</li>';
            echo '<li>De optie om taken te herhalen</li>';
        echo '</ul>';

    } else {
      header('location: login');
    }
    ?>
    </div>
</body>
</html>