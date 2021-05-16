/* Template Name: Doctris - Doctor Appointment Booking System
   Author: Shreethemes
   Website: http://shreethemes.in/
   Mail: shreethemes@gmail.com
   Version: 1.0.0
   Created: Jan 2021
   File Description: Main JS file of the template
*/

/*********************************/
/*         INDEX                 */
/*================================
 *     01.  Loader               *
 *     02.  Clickable Menu       *
 *     03.  Active Menu          *
 *     04.  DD Menu              *
 *     05.  Tooltip              *
 *     06.  Feather icon         *
 ================================*/
 

window.onload = function loader() {
    // Preloader
    setTimeout(() => {
        document.getElementById('preloader').style.visibility = 'hidden';
        document.getElementById('preloader').style.opacity = '0';
    }, 350);

    // Menus
    activateMenu();
}

// Clickable Menu
if(document.getElementById("sidebar")){
    var elements = document.getElementById("sidebar").getElementsByTagName("a");
    for(var i = 0, len = elements.length; i < len; i++) {
        elements[i].onclick = function (elem) {
            if(elem.target.getAttribute("href") === "javascript:void(0)") {
                elem.target.parentElement.classList.toggle("active");
                elem.target.nextElementSibling.classList.toggle("d-block");
            }
        }
    }
}

// document.querySelectorAll('.active').forEach(function(a){
//     a.classList.remove("active");
//     if(a.querySelector(".d-block")) {
//         a.querySelector(".d-block").classList.remove("d-block");
//     }
// });

//Active Sidebar
function activateMenu() {
    var current = location.pathname.substring(location.pathname.lastIndexOf('/') + 1);;
    if (current !== ""){
        var menuItems = document.querySelectorAll('#sidebar a');
        for (var i = 0, len = menuItems.length; i < len; i++) {
            if (menuItems[i].getAttribute("href").indexOf(current) !== -1) {
                menuItems[i].parentElement.className += " active";
                if(menuItems[i].closest(".sidebar-submenu")) {
                    menuItems[i].closest(".sidebar-submenu").classList.add("d-block");
                }
                if(menuItems[i].closest(".sidebar-dropdown")) {
                    menuItems[i].closest(".sidebar-dropdown").classList.add("active");
                }
            }
        }
    }
}

if(document.getElementById("close-sidebar")){
    document.getElementById("close-sidebar").addEventListener("click", function() {
        document.getElementsByClassName("page-wrapper")[0].classList.toggle("toggled");
    });
}  

// dd-menu
if(document.getElementsByClassName("dd-menu")) {
    var ddmenu = document.getElementsByClassName("dd-menu");
    for(var i = 0, len = ddmenu.length; i < len; i++) {
        ddmenu[i].onclick = function (elem) {
            elem.stopPropagation();
        }
    }
}

//Tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

//Feather icon
feather.replace();