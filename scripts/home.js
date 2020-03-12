console.log("loaded home js");

let category = 1;

$.post("/ajax", {category_id : category, user_id : 1}, function (result) {
    $("#projects").html(result);
});

$("#category").on("change",function () {
    let category = $(this).val();
    console.log(category);

    $.post("/ajax", {category_id : category, user_id : 1}, function (result) {
        $("#projects").html(result);
    });
});

