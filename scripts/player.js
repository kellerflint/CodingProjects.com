console.log("loaded player.js");
let project = $("#project-id").val();
let videos = "";
let index = 0;
$.post("/ajax", {project_id : project}, function (result) {
    videos = JSON.parse(result);
    console.log(videos);
    updatePage();
});

$("#last").on("click", function() {
    if (index > 0) {
        index--;
        updatePage();
    }
});

$("#next").on("click", function() {
    if (index < videos.length - 1) {
        index++;
        updatePage();
    }
});

function updatePage() {
    $("#progress").html(index + 1);
    $("#video-title").html(videos[index]["video_title"]);
    $("#iframe").attr("src", videos[index]["video_url"]);
}