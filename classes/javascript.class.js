/*========================================================================
    For main page, displaying tasks
/*=======================================================================*/
// Countdown timer for tasks
function countdownTimer(end_time, id, start_time) {
    // Convert, create and compare the different dates
    var end = new Date(end_time).getTime();
    var start = new Date(start_time).getTime();
    // Create timer that updates every second
    var timer = setInterval(function() {
        var now = new Date().getTime();
        var distance = end - now;
        
        // Calculate the days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        // Display propper indicator
        daysIndicator = ' dagen ';
        hoursIndicator = ' uren ';
        minutesIndicator = ' minuten ';
        secondsIndicator = ' seconden ';
        if (2 > days) {
            daysIndicator = ' dag ';
        } 
        if (2 > hours) {
            hoursIndicator = ' uur ';
        }
        if (2 > minutes) {
            minutesIndicator = ' minuut ';
        }
        if (2 > seconds) {
            secondsIndicator = ' seconde ';
        }
        if (days <= 0) {
            days = '';
            daysIndicator = '';
        }
        if ((days <= 0) && (hours <= 0)) {
            hours = '';
            hoursIndicator = '';
        }
        if ((days <= 0) && (hours <= 0) && (minutes <= 0)) {
            minutes = '';
            minutesIndicator = '';
        }
        // Display time in Task div element.
        document.getElementById('timer-' + id).innerHTML = 'Nog:<br> ' + days + daysIndicator + hours + hoursIndicator + minutes + minutesIndicator + seconds + secondsIndicator;
        // Check if task end time has passed.

        if (distance < 0) {
        clearInterval(timer);
        document.getElementById('timer-' + id).innerHTML = "EXPIRED";
        }
    }, 1000);
}
// Different Countdown timer for tasks that have not yet started.
function countdownTimerToBe(id, start_time) {
    var start = new Date(start_time).getTime();
    var now = new Date().getTime();
    var distance = start - now;

    var timer = setInterval(function() {
        var now = new Date().getTime();
        distance = start - new Date().getTime();

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display propper indicator
        daysIndicator = ' dagen ';
        hoursIndicator = ' uren ';
        minutesIndicator = ' minuten ';
        secondsIndicator = ' seconden ';
        if (2 > days) {
            daysIndicator = ' dag ';
        }
        if (2 > hours) {
            hoursIndicator = ' uur ';
        }
        if (2 > minutes) {
            minutesIndicator = ' minuut ';
        }
        if (2 > seconds) {
            secondsIndicator = ' seconde ';
        }
        if (days <= 0) {
            days = '';
            daysIndicator = '';
        }
        if ((days <= 0) && (hours <= 0)) {
            hours = '';
            hoursIndicator = '';
        }
        if ((days <= 0) && (hours <= 0) && (minutes <= 0)) {
            minutes = '';
            minutesIndicator = '';
        }
        if (start > now) {
            document.getElementById('timer-' + id).innerHTML = 'Kan gedaan worden over:<br> ' + days + daysIndicator + hours + hoursIndicator + minutes + minutesIndicator + seconds + secondsIndicator; 
        } else {
            document.getElementById('timer-' + id).innerHTML = 'Taak kan gedaan worden'; 
        }
    }, 1000);
    // If task has started
    if (distance < 0) {
        document.getElementById("timer-" + id).innerHTML = "De taak kan gedaan worden.";
        clearInterval(timer);
    }
}

// Progressbar for tasks
function progressBar(start_time, end_time, id) {
    var timer = setInterval(function() {
        var start = new Date(start_time).getTime();
        var end = new Date(end_time).getTime();
        var now = new Date().getTime();
    
        if (now < start) {
            // return "0%";
        }
    
        var total = end - start;
        var progress = (now - start) / total;
        document.getElementById('bar-' + id).value = (Math.round(progress * 100));
        numberr = (Math.floor(progress * 10000) / 100);
        document.getElementById('percentage-' + id).innerHTML = numberr.toFixed(1) + "%";
        // Style progress bar according to progress:
        number = (Math.round(progress * 100));
        if (number < 50) {
            document.getElementById('bar-' + id).classList.add("bar-green");
        } else if (number < 90) {
            document.getElementById('bar-' + id).classList.add("bar-orange");
        } else if (number <= 100) {
            document.getElementById('bar-' + id).classList.add("bar-red");
        }
        if (number >= 100) {
            document.getElementById('bar-' + id).classList.add("bar-red");
            document.getElementById('percentage-' + id).innerHTML = "100%";
        }
    }, 1000);
}
// Place error message somewhere fitting.
function setErrorMsg(object, place) {
    msg = document.getElementById(object);
    placeTo = document.getElementById(place);
    
    placeTo.parentNode.insertBefore(msg, placeTo.nextSibling);
}
// Prevent form submit on refreshing.
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}
// When clicked delete notification
function deleteObject() {
    const element = document.getElementById("taskCreated");
    element.remove();
    // Clean up the url. 
    window.history.replaceState(null, null, window.location.pathname);
}
function overlayMsg() {
    document.getElementById("overlay").style.display = "block";
}
function deleteOverlay() {
    document.getElementById("overlay").style.display = "none";
}
function onChanges(id) {
    document.getElementById(id).submit();
    // HTMLFormElement.prototype.submit.call(id);
}
// Theme switcher, change theme and css classes depoending on localstorage mode value
function toggleDarkMode(onload) {
    // onload to check the current theme
    if (onload) {
        var currentValue = localStorage.getItem('mode');
        if (currentValue == 'dark') {
            document.getElementsByTagName('link')[0].disabled = true;
            document.getElementsByTagName('link')[1].disabled = false;
        } else {
            document.getElementsByTagName('link')[1].disabled = true;
            document.getElementsByTagName('link')[0].disabled = false;
        }
    } else {
        // if button press, change theme
        var currentValue = localStorage.getItem('mode');
        if (currentValue === 'dark') {
            document.getElementsByTagName('link')[1].disabled = true;
            document.getElementsByTagName('link')[0].disabled = false;
            localStorage.setItem('mode', 'normal');
        } else {
            document.getElementsByTagName('link')[0].disabled = true;
            document.getElementsByTagName('link')[1].disabled = false;
            localStorage.setItem('mode', 'dark');
        }
    }
}