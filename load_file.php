<?php

/*========================================================================
  This file should be executed on the website server arround
  every minute. This is so that e-mails can be sent when needed and
  repeating tasks can be deleted and added.
/*=======================================================================*/

include("classes/task.class.php");
include("classes/mail.class.php");
include("classes/user.class.php");

//
// Here a check if a task is expired or not. When the task is set to loop, re-create it but add the propper time delay to it.
//
$date_now = date('Y-m-d H:i');
$db = database::connect();

// Get all tasks where end_time is smaller than the current date
$delay = 30;
$date_now = time();
$time_min = $date_now - $delay;
$time_min = date("Y-m-d H:i:s", $time_min);
$time_max = $date_now + $delay;
$time_max = date("Y-m-d H:i:s", $time_max);
$sql = "SELECT * FROM tasks WHERE end_time < '$time_min'";
$result = mysqli_query($db, $sql);
$tasks = mysqli_fetch_all($result,MYSQLI_ASSOC);
$date_now = date('Y-m-d H:i');

// Create new task if task is repeatable.
foreach ($tasks as $task) {
    $user_email = user::get_email_by_id($task['user_id']);
    $subject = 'Taak "'.$task['title'].'" is verlopen';
    $body = '<p>Je taak is verlopen.</p>';
    if (!empty($task['repeat_id'])) {
        $uuid = $task['uuid'];
        $user_id = $task['user_id'];
        $title = $task['title'];
        $description = $task['description'];
        $start_time = $task['start_time'];
        $var = $task['repeat_id'];
        if ($var == '3') {
            $var = 'year';
        } elseif ($var == '2') {
            $var = 'month';
        } elseif ($var == '1') {
            $var = 'day';
        }
        $id = $task['ID'];
        // Delete all tasks except if repeat_id is not null(task does not repeat), then create a new task
        $sqlA = "DELETE FROM tasks WHERE ID = '$id'";
        mysqli_query($db, $sqlA);
        $start_time = date('Y-m-d H:i', strtotime($task['start_time'] . ' +1 '.$var));
        $end_time = date('Y-m-d H:i', strtotime($task['end_time'] . ' +1 '.$var));
        $repeat_id = $task['repeat_id'];

        $sql = "INSERT INTO tasks (uuid, user_id, title, description, start_time, end_time, repeat_id) VALUES ('$uuid', '$user_id', '$title', '$description', '$start_time', '$end_time', '$repeat_id')";
        mysqli_query($db, $sql);
        // Send reset password request email.
        $body = '<p>Je taak is verlopen. Omdat het een herhalende taak is die meteen weer opnieuw toegevoegd.</p>';
        $body .= '<a href="https://jelte.lesonline.nu/taken/task?id='.$task['uuid'].'">Taak bekijken</a>';
    }
    if ($task['notification_delay'] <= 0 && $task['notification_delay'] !== 'mute') {
        // $mail = mail::sendEmail($user_email, $subject, $body);
    } elseif ($task['notification_delay'] > 0) {
        $value = $task['notification_delay'] - 1;
        task::reduce_snooz($task['uuid'], $value);
    }
}
// 
// Here a check if a task has reached a specific 'milestone'. If so send an email that reminds the user of the specific task. In this email is the option to either
// mute or snooze emails for that specific email.
// 
$sql = "SELECT * FROM tasks";
$result = mysqli_query($db, $sql);
$tasks = mysqli_fetch_all($result,MYSQLI_ASSOC);
$date_now = time();
$date_now = date('Y-m-d H:i');
$date_now = strtotime($date_now);

$delay = 30;

foreach ($tasks as $task) {
    $start_time = strtotime($task['start_time']);
    $end_time = strtotime($task['end_time']);
    $time_diff = $end_time - $start_time;
    $to = user::get_email_by_id($task['user_id']);
    $halfway_time = ($start_time + $end_time) / 2;
    $halfway_time_min = $halfway_time - $delay;
    $halfway_time_max = $halfway_time + $delay;
    // If the task is halfway done
    if ($date_now > $halfway_time_min && $date_now < $halfway_time_max) {
        $subject = 'Je taak "'.$task['title'].'" is halverwege';
        $body = '<p>Je taak "<a href="https://jelte.lesonline.nu/taken/task?id='.$task['uuid'].'">'.$task['title'].'</a>" is nu halverwege zijn periode. Je krijgt dit bericht ter herinnering voor het maken van de taak.</p><br>';
        $body .= '<p>Wil je de meldingen van deze taak snoozen? <a href="https://jelte.lesonline.nu/taken/snooz?id='.$task['uuid'].'">Snooz de taak</a></p>';
        $body .= '<p>Wil je geen meldingen voor deze taak krijgen? <a href="https://jelte.lesonline.nu/taken/mute?id='.$task['uuid'].'">Demp de taak</a></p>';
        // $mail = mail::sendEmail($to, $subject, $body);
        if ($task['notification_delay'] > 0 && $task['notification_delay'] !== 'mute') {
            $value = $task['notification_delay'] - 1;
            task::reduce_snooz($task['uuid'], $value);
        }
    }
    // If there is a day left for the task
    if ($time_diff > 86370 && $time_diff < 86430) {
        $subject = 'Nog een dag over voor "'.$task['title'].'"';
        $body = '<p>Je hebt nog een dag om je taak "<a href="https://jelte.lesonline.nu/taken/task?id='.$task['uuid'].'">'.$task['title'].'</a>" af te ronden</p>';
        $body .= '<p>Wil je de meldingen van deze taak snoozen? <a style="" href="https://jelte.lesonline.nu/taken/snooz?id='.$task['uuid'].'">Snooz de taak</a></p>';
        $body .= '<p>Wil je geen meldingen voor deze taak krijgen? <a href="https://jelte.lesonline.nu/taken/mute?id='.$task['uuid'].'">Demp de taak</a></p>';
        // $mail = mail::sendEmail($to, $subject, $body);
        if ($task['notification_delay'] > 0 && $task['notification_delay'] !== 'mute') {
            $value = $task['notification_delay'] - 1;
            task::reduce_snooz($task['uuid'], $value);
        }
    }
}
//
// Here a check for when a task can be started with (the start_time of the task gets passed).
//
$date_now = time();
$time_min = $date_now - $delay;
$time_min = date("Y-m-d H:i:s", $time_min);
$time_max = $date_now + $delay;
$time_max = date("Y-m-d H:i:s", $time_max);

$sql = "SELECT * FROM tasks WHERE start_time > '$time_min' AND start_time < '$time_max'";
$result = mysqli_query($db, $sql);
$tasks = mysqli_fetch_all($result,MYSQLI_ASSOC);
foreach ($tasks as $task) {
    $to = user::get_email_by_id($task['user_id']);
    $subject = 'Taak "'.$task['title'].'" kan nu mee begonnen worden';
    $body = '<p>Je taak "<a href="https://jelte.lesonline.nu/taken/task?id='.$task['uuid'].'">'.$task['title'].'</a>" kan nu mee begonnen worden!</p>';
    $body .= '<p>Wil je de meldingen van deze taak snoozen? <a style="" href="https://jelte.lesonline.nu/taken/snooz?id='.$task['uuid'].'">Snooz de taak</a></p>';
    $body .= '<p>Wil je geen meldingen voor deze taak krijgen? <a href="https://jelte.lesonline.nu/taken/mute?id='.$task['uuid'].'">Demp de taak</a></p>';
    // $mail = mail::sendEmail($to, $subject, $body);
}
mysqli_close($db);
?>