console.log("loaded client validation");

// classes to be validated
let validations = [
    ["val-empty", isEmpty],
    ["val-lessThan255", lessThan255],
    ["val-lessThan5000", lessThan5000],
    ["val-hasSpaces", hasSpaces]
];

/* --- Validation functions --- */
function isEmpty(input, valClass) {
    let isValid = false;
    if (input.val().trim() === "") {
        isValid = true;
    }
    toggleErrors(input, valClass, !isValid, "Cannot be empty.");
    return !isValid;
}

function lessThan255(input, valClass) {
    let isValid = false;
    if (input.val().length <= 255) {
        isValid = true;
    }
    toggleErrors(input, valClass, isValid, "Cannot be longer than 255 characters.");
    return isValid;
}

function lessThan5000(input, valClass) {
    let isValid = false;
    if (input.val().length <= 5000) {
        isValid = true;
    }
    toggleErrors(input, valClass, isValid, "Cannot be longer than 5000 characters.");
    return isValid;
}

function hasSpaces(input, valClass) {
    let isValid = false;
    if (/\s/.test(input.val())) {
        isValid = true;
    }
    toggleErrors(input, valClass, !isValid, "Cannot contain whitespace.");
    return !isValid;
}



/* --- Helper functions --- */

// sets all of the input and submit event listeners to validate the classes given in validations
for (let i = 0; i < validations.length; i++) {
    // validation on input
    $("." + validations[i][0]).find(".input").on("input focus blur", function () {
        validations[i][1]($(this), validations[i][0]);
    });
    //
    // // validation on submit
    // $("form").submit(function (event) {
    //     if (!validations[i][1]($("." + validations[i][0]).find(".input"), validations[i][0])) {
    //         event.preventDefault();
    //     }
    // });
}

function toggleErrors(object, valClass, isValid, message) {
    if (!isValid) {
        if ($(object).parent().find(".errors").find("." + valClass + "-error").length == 0) {
            $(object).parent().find(".errors").append("<div class='error " + valClass + "-error'>" + message + "</div>");
        }
    } else {
        $(object).parent().find(".errors").find("." + valClass + "-error").remove();
    }
}