document.addEventListener('DOMContentLoaded', function() {
    fetchRecommendations();
    fetchWardrobe();
    fetchStylists();
    // Call loadStylistOptions if it's defined
    if (typeof loadStylistOptions === 'function') {
        loadStylistOptions();
    }

    function showSection(sectionId) {
        const sections = document.querySelectorAll('main section');
        sections.forEach(section => {
            section.classList.add('hidden');
        });
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.remove('hidden');
        }
    }

    window.showSection = showSection;

    const scheduleAppointmentForm = document.getElementById('scheduleAppointmentForm');
    if (scheduleAppointmentForm) {
        scheduleAppointmentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            scheduleAppointment();
        });
    }

    function scheduleAppointment() {
        const formData = new FormData(scheduleAppointmentForm);

        fetch('PHP/schedule_appointment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const messageDiv = document.getElementById('scheduleMessage');
            if (data.success) {
                messageDiv.textContent = 'Appointment scheduled successfully!';
                messageDiv.style.color = 'green';
                scheduleAppointmentForm.reset();
            } else {
                messageDiv.textContent = 'Failed to schedule appointment. ' + data.message;
                messageDiv.style.color = 'red';
            }
        })
        .catch(error => console.error('Error scheduling appointment:', error));
    }


});

function fetchRecommendations() {
    fetch('../PHP/fetch_recommendations.php')
    .then(response => response.json())
    .then(data => {
        const recommendationsContent = document.getElementById('recommendationsContent');
        if (recommendationsContent) {
            recommendationsContent.innerHTML = '';
            data.forEach(item => {
                const card = document.createElement('div');
                card.classList.add('card');
                card.innerHTML = `<img src="${item.image_url}" alt="${item.description}">`;
                recommendationsContent.appendChild(card);
            });
        }
    })
    .catch(error => console.error('Error fetching recommendations:', error));
}

function fetchWardrobe() {
    fetch('../PHP/fetch_wardrobe.php')
    .then(response => response.json())
    .then(data => {
        const wardrobeContent = document.getElementById('wardrobeContent');
        if (wardrobeContent) {
            wardrobeContent.innerHTML = '';
            data.forEach(item => {
                const div = document.createElement('div');
                div.classList.add('wardrobe-item');
                div.innerHTML = `<p>${item.name}</p>`;
                wardrobeContent.appendChild(div);
            });
        }
    })
    .catch(error => console.error('Error fetching wardrobe:', error));
}

function fetchStylists() {
    fetch('../PHP/fetch_stylists.php')
    .then(response => response.json())
    .then(data => {
        const stylistsContent = document.getElementById('stylistsContent');
        if (stylistsContent) {
            stylistsContent.innerHTML = '';
            data.forEach(stylist => {
                const card = document.createElement('div');
                card.classList.add('stylist-card');
                card.innerHTML = `
                    <h3>${stylist.name}</h3>
                    <p>${stylist.description}</p>
                `;
                stylistsContent.appendChild(card);
            });
        }
    })
    .catch(error => console.error('Error fetching stylists:', error));
}

// Add event listener only if element exists
const logoutButton = document.getElementById('logoutButton');
if (logoutButton) {
    logoutButton.addEventListener('click', function() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'PHP/logout.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                window.location.href = 'PHP/login.php';
            } else {
                console.error('Logout request failed. Status:', xhr.status);
            }
        };
        xhr.send();
    });
}
