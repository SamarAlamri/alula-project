

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
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;


    /* Check if name or email fields are empty */
    if (name === "" || email === "") {

        /* Display an alert message if required fields are empty */
        alert("Name and Email must be filled out.");

        /* Prevent the form from submitting */
        return false;
    }

    if (name.length < 2) {
        alert("Please enter a valid name.");
        return false;
    }

    /* Check if the email address contains the "@" symbol */
   if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
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

    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (name === "" || email === "" || password === "") {
        alert("All fields must be filled out.");
        return false;
    }

    // 1. Username Validation: No spaces, and at least 4 characters long
    if (name.length < 4) {
        alert("Username must be at least 4 characters long.");
        return false;
    }
    if (name.includes(" ")) {
        alert("Username cannot contain spaces.");
        return false;
    }

    
    if (!emailPattern.test(email)) {
    /* Alert user to enter a valid, complete email */
    alert("Please enter a valid and complete email address (e.g., name@example.com).");

    return false;
    }

    if (!passwordPattern.test(password)) {
    alert("Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).");
    return false;
    }

    return true;
}

// ==========================================
// Real-time Password Requirements Validation
// ==========================================
const passwordInput = document.getElementById("pass");

if (passwordInput) {
    passwordInput.addEventListener("input", function () {
        const val = passwordInput.value;

        // Individual validation rules
        const rules = {
            "req-length": val.length >= 8,
            "req-uppercase": /[A-Z]/.test(val),
            "req-lowercase": /[a-z]/.test(val),
            "req-number": /\d/.test(val),
            "req-special": /[@$!%*?&]/.test(val)
        };

        // Text descriptions for resetting state easily
        const descriptions = {
            "req-length": "At least 8 characters",
            "req-uppercase": "At least one uppercase letter (A-Z)",
            "req-lowercase": "At least one lowercase letter (a-z)",
            "req-number": "At least one number (0-9)",
            "req-special": "At least one special character (@$!%*?&)"
        };

        // Loop through rules and update UI elements dynamically
        for (const [id, met] of Object.entries(rules)) {
            const element = document.getElementById(id);
            if (element) {
                if (met) {
                    element.style.color = "green";
                    element.innerHTML = "✓ " + descriptions[id];
                } else {
                    element.style.color = "red";
                    element.innerHTML = "❌ " + descriptions[id];
                }
            }
        }
    });
}

const addScheduleBtn = document.getElementById("addScheduleRow");
if (addScheduleBtn) {
    addScheduleBtn.addEventListener("click", function () {
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
}

const addTourForm = document.getElementById("addTourForm");
if (addTourForm) {
    addTourForm.addEventListener("submit", function(event) {
        event.preventDefault();
        const formData = new FormData(addTourForm);
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
                addTourForm.reset();
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
}

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
// =========================
// Smooth Scroll Effect
// =========================

// Adds smooth scrolling to internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {

    anchor.addEventListener('click', function (e) {

        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });

    });

});


// =========================
// Success Message Animation
// =========================

// Displays success messages smoothly
const successMessage = document.querySelector(".success-message");

if (successMessage) {

    successMessage.style.opacity = "0";

    setTimeout(() => {
        successMessage.style.transition = "opacity 0.5s ease";
        successMessage.style.opacity = "1";
    }, 200);

}


// =========================
// Navbar Shadow on Scroll
// =========================

// Adds shadow effect when scrolling
window.addEventListener("scroll", function () {

    const nav = document.querySelector("nav");

    if (window.scrollY > 50) {

        nav.style.boxShadow = "0 2px 10px rgba(0,0,0,0.15)";
        nav.style.backgroundColor = "rgba(122, 59, 29, 0.95)";

    } else {

        nav.style.boxShadow = "none";
        nav.style.backgroundColor = "transparent";

    }

});


// =========================
// Loading Button Effect
// =========================

// Adds loading effect to forms
document.querySelectorAll("form").forEach(form => {

    form.addEventListener("submit", function () {

        const submitButton = form.querySelector('input[type="submit"]');

        if (submitButton) {

            submitButton.value = "Submitting...";
            submitButton.disabled = true;

        }

    });

});

const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");
const durationFilter = document.getElementById("durationFilter");
const toursContainer = document.getElementById("toursContainer");
const dateFilter = document.getElementById("dateFilter");

function liveSearchTours() {
    if (!searchInput || !categoryFilter || !durationFilter || !toursContainer) {
        return;
    }

    const search = searchInput.value;
    const category = categoryFilter.value;
    const duration = durationFilter.value;
    const tourDate = dateFilter ? dateFilter.value : "";

    fetch("../api/search-tours.php?search=" + encodeURIComponent(search) +
      "&category=" + encodeURIComponent(category) +
      "&duration=" + encodeURIComponent(duration) +
      "&tour_date=" + encodeURIComponent(tourDate))
        .then(response => response.json())
        .then(tours => {
            toursContainer.innerHTML = "";

            if (tours.length === 0) {
                toursContainer.innerHTML = "<p>No tours found.</p>";
                return;
            }

            tours.forEach(tour => {
                toursContainer.innerHTML += `
                    <section class="service-card">
                        <h3>${tour.title}</h3>
                        <p>${tour.description}</p>
                        <p><strong>Category:</strong> ${tour.category}</p>
                        <p><strong>Duration:</strong> ${tour.duration}</p>
                        <p><strong>Price:</strong> ${tour.price} SAR</p>
                        <p><strong>Date:</strong> ${tour.tour_date}</p>
                        <button type="button" class="select-btn book-tour-btn" data-tour-id="${tour.id}">
                        Book Tour
                    </button>
                    </section>
                `;
            });
        })
        .catch(error => {
            console.error("Live search error:", error);
        });
}

if (searchInput) {
    searchInput.addEventListener("input", liveSearchTours);
    categoryFilter.addEventListener("change", liveSearchTours);
    durationFilter.addEventListener("change", liveSearchTours);
    dateFilter.addEventListener("change", liveSearchTours);
}

// ==========================================
// Protected Booking Buttons Check
// ==========================================
const loginRequiredButtons = document.querySelectorAll(".login-required-btn");

if (loginRequiredButtons.length > 0) {
    loginRequiredButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            // Prevent native link navigation or form submission if applicable
            event.preventDefault(); 
            
            alert("Please sign in to book a tour.");
            window.location.href = "login.php";
        });
    });
}


// ==========================================
// Booking Policy Modal Management
// ==========================================

let selectedTourId = null;

document.addEventListener("click", function(event) {

    if (event.target.classList.contains("book-tour-btn")) {
        selectedTourId = event.target.getAttribute("data-tour-id");

        const modal = document.getElementById("bookingPolicyModal");

        if (modal) {
            modal.style.display = "flex";
        }
    }

    if (event.target.id === "cancelBookingBtn") {
        const modal = document.getElementById("bookingPolicyModal");

        if (modal) {
            modal.style.display = "none";
        }

        selectedTourId = null;
    }

    if (event.target.id === "confirmBookingBtn") {
        if (selectedTourId) {
            window.location.href = "mytour.php?tour_id=" + selectedTourId;
        }
    }
});

// ==========================================
// Safe Tour Selection & Search Handling
// ==========================================
const selectButtons = document.querySelectorAll('.select-btn');
const confirmationMessage = document.getElementById('confirmation-message');

// 2. Protect the search form submit event listener
const tourSearchForm = document.getElementById('tourSearchForm');
if (tourSearchForm) {
    tourSearchForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Stops form from submitting and refreshing the page
    });
}

// ==========================================
// Cancel Tour Modal Management
// ==========================================

if (document.getElementById("cancelPolicyModal")) {

    let cancelTourId = null;

    document.addEventListener("click", function(event) {

        // Open cancellation modal
        if (event.target.classList.contains("cancel-tour-btn")) {

            cancelTourId = event.target.getAttribute("data-tour-id");
            const tourDate = event.target.getAttribute("data-tour-date");

            const refundMessage = document.getElementById("refundMessage");
            const modal = document.getElementById("cancelPolicyModal");

            const today = new Date();
            const tourDay = new Date(tourDate);

            const differenceInDays = Math.ceil(
                (tourDay - today) / (1000 * 60 * 60 * 24)
            );

            if (differenceInDays >= 7) {
                refundMessage.textContent = "You are eligible for a full refund.";
            } else if (differenceInDays >= 3) {
                refundMessage.textContent = "You are eligible for a 50% refund.";
            } else {
                refundMessage.textContent = "This cancellation is not eligible for a refund.";
            }

            modal.style.display = "flex";
        }

        // Close modal
        if (event.target.id === "closeCancelBtn") {

            document.getElementById("cancelPolicyModal").style.display = "none";
            cancelTourId = null;
        }

        // Confirm cancellation
        if (event.target.id === "confirmCancelBtn") {

            const formData = new FormData();
            formData.append("tour_id", cancelTourId);

            fetch("../api/cancel-tour.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {

                    const tourBox = document.querySelector(
                        ".cancel-tour-btn[data-tour-id='" + cancelTourId + "']"
                    ).closest(".itinerary-container");

                    tourBox.remove();

                    document.getElementById("cancelPolicyModal").style.display = "none";
                    cancelTourId = null;

                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error(error);
                alert("Something went wrong while cancelling the tour.");
            });
        }
    });
}

const profileUploadForm = document.getElementById("profileUploadForm");

if (profileUploadForm) {
    profileUploadForm.addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(profileUploadForm);

        fetch("../api/upload-pfp.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const message = document.getElementById("profileMessage");

            message.textContent = data.message;
            message.className = data.success ? "success-message" : "error-message";

            if (data.success) {
                document.getElementById("profileImage").src = data.image_path;
                profileUploadForm.reset();
            }
        })
        .catch(error => {
            console.error(error);
            alert("Something went wrong while uploading the profile picture.");
        });
    });
}

    
