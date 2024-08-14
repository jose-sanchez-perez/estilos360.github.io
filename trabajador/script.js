document.addEventListener("DOMContentLoaded", function() {
    var dropdown = document.querySelector(".dropdown-toggle");
    var submenu = document.querySelector(".submenu");

    dropdown.addEventListener("click", function() {
        submenu.classList.toggle("show");
    });
});

submenu {
    display: none;
}

.show {
    display: block;
}
