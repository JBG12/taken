<?php
include('header.php');
include("classes/user.class.php");
include("classes/UUID.class.php");
?>
<body>
    <div class="content">
        
    <?php
    // Connect to the database
    $db = database::connect();

    // Display the form
    echo '<form class="RegisterForm" method="POST">';
    echo '<p>Title</p> <br/> <input type="text" name="title"><br/>';
    echo '<p>Description</p> <br/> <input type="text" name="description"> <br/>';
    echo '<p>Start time</p> <br/> <input type="text" name="start_time"> <br/>';
    echo '<p>End time</p> <br/> <input type="text" name="end_time"> <br/>';
    echo '<input type="radio" name="repeat" value="1"> <br/>';
    echo '<button type="submit"> Opslaan </button';
    echo '</form>';
    

    ?>
    </div>
</body>

