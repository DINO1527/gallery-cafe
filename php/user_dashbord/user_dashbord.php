
<link rel="stylesheet" href="/gallarycafe/css/dashbord.css">

<?php 
    require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');
   
?>
<section>
    <!-- Dashboard Container -->
    <div id="dashboard" class="dashboard">
        <div class="dashboard-header">
        <button class="back-btn" id="back">&larr;</button>

            <h2>User Dashboard</h2>
            <button id="close-dashboard-btn" class="close-dashboard-btn">&times;</button>
        </div>
        <div class="dashboard-content">
            <div class="userdetails" id="userdetails">
                <!-- User Profile Section -->
                <div class="user-profile">

                    <div class="profile-pic-container">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTenADIUw_fFsdIot3JwbVFFFEIxPGY2Crg1PZejRO4JxPwt8qiCnKNJpFVYM1pOVuOc9E&usqp=CAU" alt="Profile Picture" id="profile-pic">
                        <button class="edit-icon" data-field="user_image" style="display:none;">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <input type="file" id="profile-pic-input" accept="image/*" style="display:none;">
                    </div>

                    <button id="signin" class="signin_btn" style="display:none;">Sign-in</button>
                    <button id="user_details_btn" class="user_details" style="display:block;">User Info</button>
                    <button id="order_history_btn" class="order_history" style="display:block;">Order History</button>
                    <button id="log_out_btn" class="log_out" style="display:block;">Logout</button>

                    <!-- User Info Section -->
                    <div class="user-info-section" id="user-info-section" style="display:none;">
                        <div class="user-info" id="user-info">
                            <div class="info-item">
                                <strong>Username:</strong> <span id="username"></span>
                            </div>
                            <div class="info-item">
                                <strong>Email:</strong> <span id="email"></span>
                            </div>
                            <div class="info-item">
                                <strong>Address:</strong> <span id="address"></span>
                                <button class="edit-icon" data-field="address" style="display:none;">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </div>
                            <div class="info-item">
                                <strong>Phone:</strong> <span id="phone_no"></span>
                                <button class="edit-icon" data-field="phone_no" style="display:none;">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </div>
                        </div>
                        <button id="edit-profile-btn" class="edit-profile-btn">Edit Profile</button>
                        <button id="update-profile-btn" class="update-profile-btn" style="display:none;">Update</button>
                    </div>

                    <!-- Order History Section -->
                    <div class="order-history-section" id="order-history-section" style="display:none;">
                        <h3>Order History</h3>
                        <div class="order-history-container">
                            <table id="order-history-table">
                                <thead>
                                    <tr>
                                        <th>Order Date & Time</th>
                                        <th>Order Number</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Orders will be dynamically inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="log_out">
                <!-- Logout button is now inside userdetails -->
                
            </div>
        </div>
    </div>
</section>


    <!-- Link to JavaScript -->
    
    <script src="/gallarycafe/js/dashbord.js"></script>
