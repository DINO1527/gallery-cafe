document.addEventListener('DOMContentLoaded', function() {
    // Get Elements
    const openBtn = document.getElementById('open-dashboard-btn');
    const closeBtn = document.getElementById('close-dashboard-btn');
    const dashboard = document.getElementById('dashboard');
    
    const editProfileBtn = document.getElementById('edit-profile-btn');
    const updateProfileBtn = document.getElementById('update-profile-btn');
    const editIcons = document.querySelectorAll('.edit-icon');
    const signinBtn = document.getElementById('signin');

    const userDetailsBtn = document.getElementById('user_details_btn');
    const orderHistoryBtn = document.getElementById('order_history_btn');
    const logOutBtn = document.getElementById('log_out_btn');

    const userInfoSection = document.getElementById('user-info-section');
    const orderHistorySection = document.getElementById('order-history-section');

    const backbtn = document.getElementById('back');

    // Function to open dashboard
    const openDashboard = (event) => {
        event.preventDefault(); // Prevent default link behavior
        dashboard.style.right = '0';
        
    }

    // Function to close dashboard
    const closeDashboardFunc = () => {
        dashboard.style.right = '-800px';
        exitEditMode(); // Ensure all edit modes are closed
    }

    // Event Listeners for opening and closing
    openBtn.addEventListener('click', openDashboard);
    closeBtn.addEventListener('click', closeDashboardFunc);
   
    // Function to handle edit button clicks
    editProfileBtn.addEventListener('click', function() {
        editIcons.forEach(function(icon) {
            icon.style.display = 'inline-block';
        });
        updateProfileBtn.style.display = 'inline-block';
        editProfileBtn.style.display = 'none';
    });

    // Function to exit edit mode
    const exitEditMode = () => {
        editIcons.forEach(function(icon) {
            icon.style.display = 'none';
        });
        updateProfileBtn.style.display = 'none';
        editProfileBtn.style.display = 'inline-block';
        // Replace input fields with spans if they exist
        ['address', 'phone_no'].forEach(field => {
            const input = document.getElementById(`${field}-input`);
            if (input) {
                const span = document.createElement('span');
                span.id = field;
                span.textContent = input.value;
                input.parentNode.replaceChild(span, input);
            }
        });
    }

    // Fetch user data on window load
    window.onload = function() {
        fetch('/gallarycafe/php/user_dashbord/user_data.php', {
            credentials: 'include' // Ensure cookies are sent
        })
            .then(response => response.json())
            .then(data => {
                if (data.logged_in) {
                    document.getElementById('username').textContent = data.name;
                    document.getElementById('email').textContent = data.email;
                    document.getElementById('address').textContent = data.address;
                    document.getElementById('phone_no').textContent = data.phone_no;
                    document.getElementById('profile-pic').src = data.user_image; // Updated field
                    signinBtn.style.display = 'none';
                } else {
                    signinBtn.style.display = 'inline-block';
                    document.getElementById('user-info').style.display = 'none';
                    document.getElementById('edit-profile-btn').style.display = 'none';
                    document.getElementById('log_out').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
            });
    }

    // Handle profile updates
    updateProfileBtn.addEventListener('click', function() {
        // Gather updated data
        const addressInput = document.getElementById('address-input');
        const phoneInput = document.getElementById('phone_no-input');
        const profilePicInput = document.getElementById('profile-pic-input');

        const address = addressInput ? addressInput.value.trim() : document.getElementById('address').textContent.trim();
        const phone_no = phoneInput ? phoneInput.value.trim() : document.getElementById('phone_no').textContent.trim();

        let user_image = null; // Updated variable name
        if (profilePicInput.files && profilePicInput.files[0]) {
            const file = profilePicInput.files[0];
            const reader = new FileReader();
            reader.onloadend = function() {
                user_image = reader.result.split(',')[1]; // Get Base64 string

                // Send data to update_details.php
                sendUpdateRequest(address, phone_no, user_image);
            }
            reader.readAsDataURL(file);
        } else {
            // No new image, proceed without it
            sendUpdateRequest(address, phone_no, null);
        }

        function sendUpdateRequest(address, phone_no, user_image) { // Updated parameter name
            const payload = { address, phone_no };
            if (user_image) { // Updated condition
                payload.user_image = user_image; // Updated field name
            }

            fetch('/gallarycafe/php/user_dashbord/update_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'include', // Ensure cookies are sent
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully');
                    exitEditMode();
                    // Refetch user data to update the UI
                    return fetch('/gallarycafe/php/user_dashbord/user_data.php', {
                        credentials: 'include' // Ensure cookies are sent
                    });
                } else {
                    alert(data.error);
                }
            })
            .then(response => {
                if (response) {
                    return response.json();
                }
            })
            .then(data => {
                if (data && data.logged_in) {
                    document.getElementById('username').textContent = data.name;
                    document.getElementById('email').textContent = data.email;
                    document.getElementById('address').textContent = data.address;
                    document.getElementById('phone_no').textContent = data.phone_no;
                    document.getElementById('profile-pic').src = data.user_image; // Updated field
                }
            })
            .catch(error => {
                console.error('Error updating profile:', error);
            });
        }
    });

    // Handle individual field edits
    editIcons.forEach(icon => {
        icon.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent event bubbling
            const field = this.getAttribute('data-field');

            if (field === 'user_image') { // Updated condition
                // Trigger the file input to open the file directory
                document.getElementById('profile-pic-input').click();
            } else {
                const span = document.getElementById(field);
                if (!span) return;

                const currentValue = span.textContent;
                const input = document.createElement('input');
                input.type = 'text';
                input.value = currentValue;
                input.id = `${field}-input`;
                span.parentNode.replaceChild(input, span);
                this.style.display = 'none';
            }
        });
    });

    // Handle profile picture change
    const profilePicInput = document.getElementById('profile-pic-input');
    profilePicInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-pic').src = e.target.result; // Update the profile picture
            }
            reader.readAsDataURL(file); // Convert image to base64 for preview
        }
    });
// Handle User Info Button Click
userDetailsBtn.addEventListener('click', function() {
 //   document.querySelector('.user-profile').style.display = 'none';
    document.getElementById('user-info-section').style.display = 'block';
    backbtn.style.display = 'block';

});

// Handle Order History Button Click
orderHistoryBtn.addEventListener('click', function() {
    document.querySelector('.user-info-section').style.display = 'none';
    orderHistorySection.style.display = 'block';
    backbtn.style.display = 'block';
    fetchOrderHistory(); // Fetch and display order history
});

// Handle Logout Button Click
logOutBtn.addEventListener('click', function() {
    

    window.location.href = '/gallarycafe/php/login/logout.php';
});
signinBtn.addEventListener('click', function() {
    window.location.href = '/gallarycafe/php/login/loginpage.php';
});


// Handle Back Buttons
backbtn.addEventListener('click', function() {
    userInfoSection.style.display = 'none';
    orderHistorySection.style.display = 'none';
    document.querySelector('.user-profile').style.display = 'flex';
    backbtn.style.display = 'none';

});



// Function to fetch and display order history
function fetchOrderHistory() {
    fetch('/gallarycafe/php/user_dashbord/order_history.php', {
        credentials: 'include' // Ensure cookies are sent
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const tbody = document.querySelector('#order-history-table tbody');
            tbody.innerHTML = ''; // Clear existing rows
            if (data.orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4">No orders found.</td></tr>';
                return;
            }
            data.orders.forEach(orders => {
                const tr = document.createElement('tr');

                // Order Date & Time
                const dateTd = document.createElement('td');
                dateTd.textContent = orders.order_time;
                tr.appendChild(dateTd);

                // Order Number
                const numberTd = document.createElement('td');
                numberTd.textContent = orders.order_id;
                tr.appendChild(numberTd);

                // Status
                const statusTd = document.createElement('td');
                statusTd.textContent = orders.status;
                tr.appendChild(statusTd);

                // Action (Cancel Button)
                const actionTd = document.createElement('td');
                if (orders.status.toLowerCase() !== 'cancelled') {
                    const cancelBtn = document.createElement('button');
                    cancelBtn.textContent = 'Cancel';
                    cancelBtn.classList.add('cancel-btn');
                    cancelBtn.dataset.orderId = orders.order_id; // Assuming order_id is unique
                    cancelBtn.addEventListener('click', cancelOrder);
                    actionTd.appendChild(cancelBtn);
                } else {
                    actionTd.textContent = '-';
                }
                tr.appendChild(actionTd);

                tbody.appendChild(tr);
            });
        } else {
            alert(data.error || 'Failed to fetch order history.');
        }
    })
    .catch(error => {
        console.error('Error fetching order history:', error);
    });
}

// Function to handle order cancellation
function cancelOrder(event) {
    const orderId = event.target.dataset.orderId;
    if (!confirm('Are you sure you want to cancel this order?')) {
        return;
    }

    fetch('/gallarycafe/php/user_dashbord/cancel_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'include', // Ensure cookies are sent
        body: JSON.stringify({ order_id: orderId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order cancelled successfully.');
            fetchOrderHistory(); // Refresh the order history
        } else {
            alert(data.error || 'Failed to cancel order.');
        }
    })
    .catch(error => {
        console.error('Error cancelling order:', error);
    });
}
});







