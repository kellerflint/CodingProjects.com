console.log("loaded user select");

$('#userSelect').on("change", function () {
    $('#userForm').submit();
    console.log("changed");
});


