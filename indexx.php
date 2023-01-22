<header>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</header>
<body onload="doRefresh()">
  <div class="box">
    <p class="time" id="time">00:00:00:000</p>
    <div id="show">
    <?php
      $db = mysqli_connect("localhost", "root", "", "rapporteersysteem");
      $name = $db->query("SELECT location FROM locations WHERE location_id = '7a234569-7e50-4fd3-b66c-01867da962e6'")->fetch_assoc();
      echo $name['location'];
      ?>
    </div>
    <input type="text">
    <a id="refresh">test testest testest testest test</a>
  </div>
</body>

<script>
// JS:
function startTime() {

var dateTime      = new Date();
var hours         = dateTime.getHours();
var minutes       = dateTime.getMinutes();
var seconds       = dateTime.getSeconds();
var milliSeconds  = dateTime.getMilliseconds();

if (seconds < 10) {
  seconds = "0" + seconds;
} 
if (minutes < 10) {
  minutes = "0" + minutes;
}
if (milliSeconds == 0) {
  milliSeconds = '00' + milliSeconds;
}
if (milliSeconds <= 10) {
  milliSeconds = '00' + milliSeconds;
}
if (milliSeconds <= 100) {
  milliSeconds = '0' + milliSeconds;
}

document.getElementById('time').innerHTML = hours + ":" + minutes + ":" + seconds;// + ':' + milliSeconds;

// Timeout on 0 miliseconds, 1000 for update every second
// setTimeout(startTime, 10);
}
function doRefresh() {
  // $("#show").load("#show");
  $("#show").load(" #show"); 
  // document.getElementById("refresh").innerHTML = Math.random();
  setTimeout(doRefresh, 1000);
  setTimeout(startTime);
}

</script>