console.log("loading");
$('#userSelect').on("change", function () {

    $('#userForm').submit();
    console.log("changed");
});

$('#selectedCategory').on("change", function () {
    $('#userForm').submit();
    console.log("changed");
});
