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

function validateRegisterForm() {
    var name = document.getElementById("new-user").value;
    var email = document.getElementById("new-email").value;
    var password = document.getElementById("pass").value;

    if (name === "" || email === "" || password === "") {
        alert("All fields must be filled out.");
        return false;
    }

    if (name.includes(" ")) {
        alert("Username cannot contain spaces.");
        return false;
    }

    if (email.indexOf("@") === -1) {
        alert("Please enter a valid email address.");
        return false;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters.");
        return false;
    }

    return true;
}

document.getElementById("addScheduleRow").addEventListener("click", function () {

    const scheduleRows = document.getElementById("scheduleRows");

    const newRow = document.createElement("div");

    newRow.classList.add("schedule-row");

    newRow.innerHTML = `
        <input type="time" name="schedule_time[]" required>
        <input type="text" name="schedule_activity[]" placeholder="Activity" required>
        <button type="button" onclick="this.parentElement.remove()">Remove</button>
    `;

    scheduleRows.appendChild(newRow);
});

document.getElementById("addTourForm").addEventListener("submit", function(event) {

    event.preventDefault();

    const form = document.getElementById("addTourForm");
    const formData = new FormData(form);

    const tourId = document.getElementById("tour_id").value;
    const apiUrl = tourId ? "../api/edit-tour.php" : "../api/add-tour.php";

    fetch(apiUrl, {
        method: "POST",
        body: formData
    })

    .then(response => response.json())

    .then(data => {

        alert(data.message);

        if (data.success) {

            form.reset();

            document.getElementById("scheduleRows").innerHTML = `
                <div class="schedule-row">
                    <input type="time" name="schedule_time[]" required>
                    <input type="text" name="schedule_activity[]" placeholder="Activity" required>
                </div>
            `;
        }
    })

    .catch(error => {

        alert("Something went wrong.");
        console.error(error);
    });
});

function deleteTour(tourId) {

    if (!confirm("Are you sure you want to delete this tour?")) {
        return;
    }

    const formData = new FormData();

    formData.append("tour_id", tourId);

    fetch("../api/delete-tour.php", {
        method: "POST",
        body: formData
    })

    .then(response => response.json())

    .then(data => {

        alert(data.message);

        if (data.success) {
            location.reload();
        }
    })

    .catch(error => {

        alert("Something went wrong.");
        console.error(error);
    });
}

function editTour(id, title, description, duration, category, price, buttonElement) {
    // 1. Fill out the main inputs
    document.getElementById("tour_id").value = id;
    document.querySelector("input[name='title']").value = title;
    document.querySelector("textarea[name='description']").value = description;
    document.querySelector("input[name='price']").value = price;

    // 2. Set dropdown selections
    document.querySelector("select[name='duration']").value = duration;
    document.querySelector("select[name='category']").value = category;

    // 3. Change the button text
    document.getElementById("submitTourButton").textContent = "Update Tour";

    // 4. Clear existing schedule rows in the form
    const scheduleRowsContainer = document.getElementById("scheduleRows");
    scheduleRowsContainer.innerHTML = "";

    // 5. Find the table row containing this button
    const tableRow = buttonElement.closest("tr");
    
    // Find the schedule cell (the 5th column, index 4)
    const scheduleCell = tableRow.cells[4]; 
    
    // Split the schedule text by line breaks to get each activity line
    const scheduleLines = scheduleCell.innerHTML.split("<br>").map(line => line.trim()).filter(line => line !== "");

    if (scheduleLines.length > 0) {
        scheduleLines.forEach(line => {
            // Expecting line format to be "HH:MM:SS - Activity Text" or "HH:MM - Activity Text"
            const parts = line.split(" - ");
            if (parts.length >= 2) {
                let time = parts[0].trim();
                const activity = parts.slice(1).join(" - ").trim(); // Rejoins if activity has hyphens

                // Standardize time format to HH:MM for HTML5 time input (drops seconds if present)
                if (time.length > 5) {
                    time = time.substring(0, 5);
                }

                // Create a new row element
                const newRow = document.createElement("div");
                newRow.classList.add("schedule-row");
                newRow.innerHTML = `
                    <input type="time" name="schedule_time[]" value="${time}" required>
                    <input type="text" name="schedule_activity[]" placeholder="Activity" value="${activity}" required>
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                `;
                scheduleRowsContainer.appendChild(newRow);
            }
        });
    }

    // Fallback: If no schedules were found in the table cell, insert one blank row
    if (scheduleRowsContainer.children.length === 0) {
        scheduleRowsContainer.innerHTML = `
            <div class="schedule-row">
                <input type="time" name="schedule_time[]" required>
                <input type="text" name="schedule_activity[]" placeholder="Activity" required>
            </div>
        `;
    }

    // 6. Scroll smoothly back to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.addEventListener("click", function(event) {
    if (event.target && event.target.classList.contains("edit-tour-btn")) {
        const btn = event.target;
        editTour(
            btn.getAttribute("data-id"),
            btn.getAttribute("data-title"),
            btn.getAttribute("data-description"),
            btn.getAttribute("data-duration"),
            btn.getAttribute("data-category"),
            btn.getAttribute("data-price")
        );
    }
});

const uploadCsvForm = document.getElementById("uploadCsvForm");

if (uploadCsvForm) {
    uploadCsvForm.addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(uploadCsvForm);

        fetch("../api/upload-tours-csv.php", {
            method: "POST",
            body: formData
        })

        .then(response => response.json())

        .then(data => {
            alert(data.message);

            if (data.success) {
                uploadCsvForm.reset();
                location.reload();
            }
        })

        .catch(error => {
            alert("Something went wrong while uploading the CSV.");
            console.error(error);
        });
    });
}