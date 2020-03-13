console.log("loaded home js");

// Assigns default category and user
let category = $("#category").val();
let user = $(".user-button").get(0);
$(user).removeClass("btn-secondary");
$(user).addClass("btn-primary");
let userId = $(user).val();

updateProjects();

// Category select event listener
$("#category").on("change", function () {
    category = $(this).val();
    console.log(category);

    updateProjects();
});

// user-button event listener. Calls projectsAjax function and assigns color to selected user.
$(".user-button").on("click", function () {

    // reset color of all .user-button to btn-secondary
    $(".user-button").addClass("btn-secondary");
    $(".user-button").removeClass("btn-primary");

    // set the color of the clicked button to blue
    $(this).removeClass("btn-secondary");
    $(this).addClass("btn-primary");

    userId = $(this).val();
    updateProjects();

});

/**
 * Calls ajax to update projects on the page.
 */
function updateProjects() {
    $.post("/ajax", {user_id: userId, category_id: category}, function (result) {
        $("#projects").html(result);
        assignProjectsEventListeners();
    });
}

/**
 * Assigns event listeners for projects that may have been overridden in an ajax callback.
 */
function assignProjectsEventListeners() {

    // unbind any previous event listeners to prevent duplicate assignment.
    $(".btn-give").off("click");
    $(".btn-remove").off("click");

    $(".btn-give").on("click", function () {
        console.log("give");
        $.post("/ajax", {
            give: "true",
            user_id: userId,
            project_id: $(this).attr("id")
        }, function (result) {
            console.log($(this));
            $("#" + result).removeClass("btn-give");
            $("#" + result).removeClass("btn-success");
            $("#" + result).addClass("btn-remove");
            $("#" + result).addClass("btn-warning");
            $("#" + result).html("Remove");
            assignProjectsEventListeners();
        });
    });

    $(".btn-remove").on("click", function () {
        console.log("remove");
        $.post("/ajax", {
            remove: "true",
            user_id: userId,
            project_id: $(this).attr("id")
        }, function (result) {
            $("#" + result).removeClass("btn-remove");
            $("#" + result).removeClass("btn-warning");
            $("#" + result).addClass("btn-give");
            $("#" + result).addClass("btn-success");
            $("#" + result).html("Give");
            assignProjectsEventListeners();
        });
    });
}