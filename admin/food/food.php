<section id="add-foods" class="page">
        
        <h2>Food Management</h2>
        
        <!-- Navigation -->
        <nav>
            <ul>
                <li><a href="#" id="view-food-btn">View Current Food Items</a></li>
                <li><a href="#" id="add-category-btn">Add New Category</a></li>
                <li><a href="#" id="add-food-btn">Add New Food Item</a></li>
            </ul>
        </nav>
        
        <!-- Page 1: Current Food Items -->
        <section id="current-food-items" class="tab-content">
        <h3>Current Food Items</h3>
        <table id="food-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price ($)</th>
                    <th>Category</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch food items
                $sql = "SELECT fm.food_id, fm.food_name, fm.discription, fm.price, fc.category_name, fi.img 
                        FROM food_menu fm 
                        JOIN food_category fc ON fm.category_id = fc.category_id 
                        LEFT JOIN food_img fi ON fm.food_id = fi.food_id";
                $result = $conn->query($sql);
    
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        while ($row = $result->fetch_assoc()) {
                        // Convert BLOB to base64 and display as image
                        $img_data = base64_encode($row['img']);
                        $img_src = "data:image/jpeg;base64,{$img_data}";
    
                        echo "<tr>
                                <td>{$row['food_name']}</td>
                                <td>{$row['discription']}</td>
                                <td>{$row['price']}</td>
                                <td>{$row['category_name']}</td>
                                <td><img src='{$img_src}' alt='{$row['food_name']}' class='food-img'></td>
                               
                              </tr>";}
                    }
                } else {
                    echo "<tr><td colspan='6'>No food items found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
    
        
        <!-- Page 2: Add New Category -->
     <?php
    // Add New Category Functionality
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category-name'])) {
        $category_name = $conn->real_escape_string($_POST['category-name']);
        
        $sql = "INSERT INTO food_category (category_name) VALUES ('$category_name')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New category added successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
    ?>
    
    <section id="add-category" class="tab-content" style="display:none;">
        <h3>Add New Category</h3>
        <form id="add-category-form" method="POST">
            <div class="form-group">
                <label for="category-name">Category Name:</label>
                <input type="text" id="category-name" name="category-name" required>
            </div>
            <button type="submit">Add Category</button>
        </form>
    </section>
    
        
        <!-- Page 3: Add New Food -->
        <?php
    // Add New Food Item Functionality
    
    // Add New Food Item Functionality
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['food-image'])) {
        $food_name = $conn->real_escape_string($_POST['food-name']);
        $discription = $conn->real_escape_string($_POST['food-description']);
        $price = floatval($_POST['food-price']);
        $category_id = intval($_POST['food-category']);
        
        // Handle image file as blob
        $image_file = $_FILES['food-image']['tmp_name'];
        $image_blob = file_get_contents($image_file); // Read file content as binary data
        
        // Insert the new food item into the food_menu table
        $sql = "INSERT INTO food_menu (food_name, discription, price, category_id) 
                VALUES ('$food_name', '$discription', $price, $category_id)";
        
        if ($conn->query($sql) === TRUE) {
            // Get the last inserted food_id
            $food_id = $conn->insert_id;
    
            // Insert the image into the food_img table as a BLOB
            $sql_img = $conn->prepare("INSERT INTO food_img (food_id, img) VALUES (?, ?)");
            $sql_img->bind_param("ib", $food_id, $image_blob);
            $sql_img->send_long_data(1, $image_blob); // Bind and send the binary data
            
            if ($sql_img->execute()) {
                echo "<script>alert('New food item added successfully!');</script>";
            } else {
                echo "<script>alert('Error saving image: " . $conn->error . "');</script>";
            }
    
            $sql_img->close();
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
    ?>
    
    
    
    <section id="add-food" class="tab-content" style="display:none;">
        <h3>Add Food Items</h3>
        <form id="add-food-form" enctype="multipart/form-data" method="POST">
            <div class="form-group">
                <label for="food-name">Food Name:</label>
                <input type="text" id="food-name" name="food-name" required>
            </div>
            <div class="form-group">
                <label for="food-description">Description:</label>
                <textarea id="food-description" name="food-description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="food-price">Price:</label>
                <input type="number" id="food-price" name="food-price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="food-category">Category:</label>
                <select id="food-category" name="food-category" required>
                    <option value="">Select Category</option>
                    <?php
                    // Fetch categories for dropdown
                    $cat_sql = "SELECT * FROM food_category";
                    $cat_result = $conn->query($cat_sql);
                    while ($cat_row = $cat_result->fetch_assoc()) {
                        echo "<option value='{$cat_row['category_id']}'>{$cat_row['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="food-image">Select Image:</label>
                <input type="file" id="food-image" name="food-image" accept="image/*" required>
            </div>
            <button type="submit">Add Food Item</button>
        </form>
    </section>
    
        
    <style>
        nav ul {
        list-style-type: none;
        padding: 0;
    }
    
    nav ul li {
        display: inline;
        margin-right: 20px;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    </style>
    <script>
        document.getElementById('view-food-btn').addEventListener('click', function(e) {
        e.preventDefault();
        showSection('current-food-items');
    });
    
    document.getElementById('add-category-btn').addEventListener('click', function(e) {
        e.preventDefault();
        showSection('add-category');
    });
    
    document.getElementById('add-food-btn').addEventListener('click', function(e) {
        e.preventDefault();
        showSection('add-food');
    });
    
    function showSection(sectionId) {
        // Hide all sections
        var sections = document.querySelectorAll('.tab-content');
        sections.forEach(function(section) {
            section.style.display = 'none';
        });
        
        // Show the selected section
        document.getElementById(sectionId).style.display = 'block';
    }
    
    </script>
    </section>