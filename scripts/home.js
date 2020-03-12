console.log("loaded home js");

// Assigns default category and user
let category = $("#category").val();
let user = $(".user-button").get(0);
$(user).removeClass("btn-secondary");
$(user).addClass("btn-primary");
let userId = $(user).val();

updateProjects();

$("#category").on("change",function () {
    category = $(this).val();
    console.log(category);

    updateProjects();
});


// user-button event listener. Calls projectsAjax function and assigns color to selected user.
$(".user-button").on("click",function(){
    // reset color of all .user-button to btn-secondary
    $(".user-button").addClass("btn-secondary");
    $(".user-button").removeClass("btn-primary");
    // set the color of the clicked button to blue
    $(this).removeClass("btn-secondary");
    $(this).addClass("btn-primary");

    userId =$(this).val();
    updateProjects();

});

/**
 * Calls ajax to update projects on the page.
 */
function updateProjects() {
    $.post("/ajax",{user_id :userId, category_id : category}, function (result) {
        $("#projects").html(result);
    });
}