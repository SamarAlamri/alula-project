/* Name: [Zain Aljifry], ID: [2107808], Section: [DAR], Date: [8 march] *//* Name: Samar Alamri, ID: 2206831, Section: DAR, Date: 8 march |Name: Talah Faloudah, ID: 2206666, Section: DAR, Date: 8 march */


/* Function used to validate the feedback form before submission */
function validateForm() {

    /* Gets the value entered in the Full Name field */
    var name = document.getElementById("username").value;

    /* Gets the value entered in the Email field */
    var email = document.getElementById("useremail").value;

    /* Gets all radio buttons with the name "rating" */
    var rating = document.getElementsByName("rating");

    /* Gets the selected value from the travel mode dropdown */
    var travelMode = document.getElementById("travel-mode").value;

    /* Variable used to check if a rating option was selected */
    var ratingSelected = false;

    /* Variable used for looping through the rating options */
    var i;


    /* Check if name or email fields are empty */
    if (name === "" || email === "") {

        /* Display an alert message if required fields are empty */
        alert("Name and Email must be filled out.");

        /* Prevent the form from submitting */
        return false;
    }


    /* Check if the email address contains the "@" symbol */
    if (email.indexOf("@") === -1) {

        /* Alert user to enter a valid email */
        alert("Please enter a valid email address.");

        /* Prevent form submission */
        return false;
    }


    /* Loop through the radio buttons to see if one is selected */
    for (i = 0; i < rating.length; i++) {

        /* If a rating option is selected */
        if (rating[i].checked) {

            /* Set the variable to true */
            ratingSelected = true;

            /* Stop the loop */
            break;
        }
    }


    /* If no rating option was selected */
    if (!ratingSelected) {

        /* Display alert message */
        alert("Please select a rating.");

        /* Prevent form submission */
        return false;
    }


    /* Check if the user selected a travel mode from the dropdown menu */
    if (travelMode === "") {

        /* Show alert message */
        alert("Please select your preferred travel mode.");

        /* Prevent form submission */
        return false;
    }


    /* Display a success message after successful validation */
    document.getElementById("formMessage").innerHTML =
        "Thank you, " + name + "! Your feedback has been submitted successfully.";


    /* Prevent page reload (keeps the message visible) */
    return false;
}

