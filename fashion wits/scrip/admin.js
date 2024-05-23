// Tab functionality
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";

    // Fetch data for the selected tab
    fetchData(tabName);
}

// Fetch data for the selected tab
function fetchData(tabName) {
    switch (tabName) {
        case 'Users':
            fetchUsers();
            break;
        case 'Stylists':
            fetchStylists();
            break;
        case 'Products':
            fetchProducts();
            break;
        case 'Appointments':
            fetchAppointments();
            break;
        case 'Recommendations':
            fetchRecommendations();
            break;
        default:
            break;
    }
}

// Placeholder functions for fetching data
function fetchUsers() {
    // Fetch users data from the database and display it
    fetch('PHP/fetch_users.php')
    .then(response => response.json())
    .then(data => {
        var usersTableBody = document.getElementById('usersTableBody');
        usersTableBody.innerHTML = '';
        data.forEach(user => {
            var row = `
                <tr>
                    <td>${user.user_id}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <!-- Add more columns as needed -->
                </tr>
            `;
            usersTableBody.innerHTML += row;
        });
    })
    .catch(error => console.error('Error fetching users:', error));
}

function fetchStylists() {
  fetch('fetch_stylists.php')
  .then(response => response.json())
  .then(data => {
      var productsTableBody = document.getElementById('stylistsTableBody');
      productsTableBody.innerHTML = '';
      data.forEach(product => {
          var row = `
                <tr>
                    <td>${stylist.stylist_id}</td>
                    <td>${stylist.username}</td>
                    <td>${stylist.email}</td>
                </tr>
                    `;
                    stylistsTableBody.innerHTML += row;
                });
            })
            .catch(error => console.error('Error fetching products:', error));
        }

function fetchProducts() {
    fetch('PHP/fetch_products.php')
    .then(response => response.json())
    .then(data => {
        var productsTableBody = document.getElementById('productsTableBody');
        productsTableBody.innerHTML = '';
        data.forEach(product => {
            var row = `
                <tr>
                    <td>${product.product_id}</td>
                    <td>${product.name}</td>
                    <td>${product.category}</td>
                    <!-- Add more columns as needed -->
                </tr>
            `;
            productsTableBody.innerHTML += row;
        });
    })
    .catch(error => console.error('Error fetching products:', error));
}

function fetchAppointments() {
    fetch('PHP/fetch_appointments.php')
    .then(response => response.json())
    .then(data => {
        var appointmentsTableBody = document.getElementById('appointmentsTableBody');
        appointmentsTableBody.innerHTML = '';
        data.forEach(appointment => {
            var row = `
                <tr>
                    <td>${appointment.appointment_id}</td>
                    <td>${appointment.user_id}</td>
                    <td>${appointment.stylist_id}</td>
                    <td>${appointment.appointment_time}</td>
                    <td>${appointment.status}</td>
                    <!-- Add more columns as needed -->
                </tr>
            `;
            appointmentsTableBody.innerHTML += row;
        });
    })
    .catch(error => console.error('Error fetching appointments:', error));
}

function fetchRecommendations() {
    fetch('PHP/fetch_recommendations.php')
    .then(response => response.json())
    .then(data => {
        var recommendationsTableBody = document.getElementById('recommendationsTableBody');
        recommendationsTableBody.innerHTML = '';
        data.forEach(recommendation => {
            var row = `
                <tr>
                    <td>${recommendation.user_id}</td>
                    <td>${recommendation.product_id}</td>
                    <!-- Add more columns as needed -->
                </tr>
            `;
            recommendationsTableBody.innerHTML += row;
        });
    })
    .catch(error => console.error('Error fetching recommendations:', error));
}


// Initialize dashboard by opening default tab
document.addEventListener('DOMContentLoaded', function() {
    document.getElementsByClassName("tablink")[0].click();
});

document.addEventListener('DOMContentLoaded', function() {
     const makeChangesBtn = document.getElementById('makeChangesBtn');
     const addStylistForm = document.getElementById('addStylistForm');
     const addStylistDiv = document.querySelector('.addStylist');

     makeChangesBtn.addEventListener('click', function() {
         addStylistDiv.style.display = 'block';
     });

     // Close the form when clicking outside of it
     window.addEventListener('click', function(event) {
         if (event.target !== addStylistDiv && !addStylistDiv.contains(event.target) && event.target !== makeChangesBtn) {
             addStylistDiv.style.display = 'none';
         }
     });
 });
