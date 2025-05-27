<section id="staff-management" class="page">
    <h2>Staff Management</h2>
    <button id="add-staff-btn">Add New Staff Member</button>
    <table id="staff-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');
$sql = "SELECT username, email FROM staff";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['username'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>Operational Staff</td>
                            <td>
                                <button class='edit-btn' data-username='" . $row['username'] . "'>Edit</button>
                                <button class='delete-btn' data-username='" . $row['username'] . "'>Delete</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No staff members found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<!-- Add Staff Popup -->
<div id="add-staff-popup" style="display:none;">
    <form id="add-staff-form">
        <h3>Add New Staff Member</h3>
        <div>
            <label>Username:</label>
            <input type="text" id="add-username" name="username" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" id="add-email" name="email" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" id="add-password" name="password" required>
        </div>
        <div>
            <label>Confirm Password:</label>
            <input type="password" id="add-confirm-password" required>
        </div>
        <button type="submit">Add Staff</button>
        <button type="button" id="cancel-add">Cancel</button>
    </form>
</div>



<!-- Edit Staff Popup -->
<div id="edit-staff-popup" style="display:none;">
    <form id="edit-staff-form">
        <h3>Edit Staff Details</h3>
        <input type="hidden" id="edit-username" name="username">
        <div>
            <label>Email:</label>
            <input type="email" id="edit-email" name="email" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" id="edit-password" name="password" required>
        </div>
        <div>
            <label>Confirm Password:</label>
            <input type="password" id="edit-confirm-password" required>
        </div>
        <button type="submit">Update</button>
        <button type="button" id="cancel-edit">Cancel</button>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Edit button click handler
    document.querySelectorAll('.edit-btn').forEach(function (editBtn) {
        editBtn.addEventListener('click', function () {
            const username = this.getAttribute('data-username');
            const email = this.parentElement.previousElementSibling.textContent;

            // Populate popup form
            document.getElementById('edit-username').value = username;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-password').value = '';
            document.getElementById('edit-confirm-password').value = '';

            document.getElementById('edit-staff-popup').style.display = 'block';
        });
    });

    // Cancel button click handler
    document.getElementById('cancel-edit').addEventListener('click', function () {
        document.getElementById('edit-staff-popup').style.display = 'none';
    });

    // Delete button click handler
    document.querySelectorAll('.delete-btn').forEach(function (deleteBtn) {
        deleteBtn.addEventListener('click', function () {
            const username = this.getAttribute('data-username');
            if (confirm(`Are you sure you want to delete ${username}?`)) {
                window.location.href = `/gallarycafe/admin/staff/delete_staff.php?username=${username}`;
            }
        });
    });

    // Form submission handler
    document.getElementById('edit-staff-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const password = document.getElementById('edit-password').value;
        const confirmPassword = document.getElementById('edit-confirm-password').value;

        if (password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }

        // Send form data to server via AJAX (using fetch)
        const formData = new FormData(this);
        fetch('/gallarycafe/admin/staff/update_staff.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload(); // Reload page to show updated details
        })
        .catch(error => console.error('Error:', error));
    });
});
document.addEventListener('DOMContentLoaded', function () {
    // Add staff button click handler
    document.getElementById('add-staff-btn').addEventListener('click', function () {
        document.getElementById('add-username').value = '';
        document.getElementById('add-email').value = '';
        document.getElementById('add-password').value = '';
        document.getElementById('add-confirm-password').value = '';
        
        document.getElementById('add-staff-popup').style.display = 'block';
    });

    // Cancel button for add form
    document.getElementById('cancel-add').addEventListener('click', function () {
        document.getElementById('add-staff-popup').style.display = 'none';
    });

    // Add staff form submission handler
    document.getElementById('add-staff-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const password = document.getElementById('add-password').value;
        const confirmPassword = document.getElementById('add-confirm-password').value;

        if (password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }

        // Send form data to server via AJAX (using fetch)
        const formData = new FormData(this);
        fetch('/gallarycafe/admin/staff/add_staff.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload(); // Reload page to show the new staff member
        })
        .catch(error => console.error('Error:', error));
    });
});

</script>
<style>
    #add-staff-popup, #edit-staff-popup {
    background: #f9f9f9;
    border: 1px solid #ccc;
    padding: 20px;
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translate(-50%, -20%);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

form div {
    margin-bottom: 10px;
}

</style>