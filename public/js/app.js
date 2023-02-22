/* Responsive Nav Bar */
let showOnOff = 0;

function toggleSidebar() {
showOnOff = showOnOff === 0 ? 1 : 0;

  if (showOnOff === 1) {
    document.getElementById("sidebar").style.left = "0px";
    document.querySelector("#sidebar ul").style.display = "block";
  } else {
    document.getElementById("sidebar").style.left = "-200px";
    document.querySelector("#sidebar ul").style.display = "none";
  }
}

window.addEventListener("resize", reportWindowSize);

function reportWindowSize() {
  if (window.innerWidth > 768) {
    /* Display sidebar */
    if (document.getElementById("sidebar").style.left === "-200px") {
      document.getElementById("sidebar").style.left = "0px";
      document.querySelector("#sidebar ul").style.display = "block";
    }
  } else {
    /* Don't display sidebar */
    if (document.getElementById("sidebar").style.left === "0px") {
      document.getElementById("sidebar").style.left = "-200px";
      document.querySelector("#sidebar ul").style.display = "none";
      showOnOff = 0;
    }
  }
}

/* Copy link to clipboard */
function copyLink() {
  link = "http://localhost/PA_Journal_Webapplikation/"

   // Copy the text inside the text field
  navigator.clipboard.writeText(link);

  // Alert the copied text
  alert("Copied the text: " + link);
}