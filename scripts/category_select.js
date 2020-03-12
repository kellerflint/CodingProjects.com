console.log("loaded category select");

$('#selectedCategory').on("change", function () {
    $('#userForm').submit();
    console.log("changed");
});
