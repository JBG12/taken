// $(document).ready(function(){
    
//     /*======================================================================== #
//       Misc js functions
//     /*=======================================================================*/

//     // Prevent submit form on refresh.
    

// });
function toggleDarkMode() {
  if (localStorage.getItem("darkMode") === "true") {
    localStorage.setItem("darkMode", "false");
    const link = document.querySelector("link[rel='stylesheet']");
    link.href = "./CSS/css.css";
  } else {
    localStorage.setItem("darkMode", "true");
    const link = document.querySelector("link[rel='stylesheet']");
    link.href = "./CSS/css-dark.css";
  }
}

const toggle = document.querySelector(".switch");

toggle.addEventListener("click", toggleDarkMode);

window.addEventListener("load", () => {
  if (localStorage.getItem("darkMode") === null) {
    localStorage.setItem("darkMode", "false");
  }

  if (localStorage.getItem("darkMode") === "true") {
    const link = document.querySelector("link[rel='stylesheet']");
    link.href = "./CSS/css-dark.css";
  }
});
// Get the button and the link element that points to the CSS file from the DOM
// const button = document.querySelector(".switch");

// const link = document.querySelector("link[rel='stylesheet']");
// // if (button === null){
// //   link.classList.remove("./CSS/css-dark.css");
// //   link.classList.add("./CSS/css.css");
// // }

// // Set up an event listener that will fire when the button is clicked
// button.addEventListener("click", function() {
//   // If the link element has the "./CSS/css-dark.css" class, remove it and add the "./CSS/css.css" class
//   // Otherwise, remove the "./CSS/css.css" class and add the "./CSS/css-dark.css" class
//   if (link.classList.contains("./CSS/css-dark.css")) {
//     link.classList.remove("./CSS/css-dark.css");
//     link.classList.add("./CSS/css.css");
//   } else {
//     link.classList.remove("./CSS/css.css");
//     link.classList.add("./CSS/css-dark.css");
//   }
// });
