console.log("loaded form selects");

$('#userSelect').on("change", function () {
    $('#userForm').submit();
    console.log("changed");
});

$('#selectedCategory').on("change", function () {
    $('#userForm').submit();
    console.log("changed");
});


