function toggleReturnDate() {
    var returnDateDiv = document.getElementById("returnDateDiv");
    var allerRetourCheckbox = document.getElementById("allerretour");
    var allerSimpleCheckbox = document.getElementById("allersimple");


    returnDateDiv.style.display = allerRetourCheckbox.checked ? "" : "none";
    returnDateDiv.style.display = allerSimpleCheckbox.checked ? "none" : "";

}
