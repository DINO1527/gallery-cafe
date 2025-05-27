
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<section id="offers" class="page">
    <h2>Offers and Promotions</h2>
    <form id="offers-form" method="POST" enctype="multipart/form-data" action="offer/submit_offer.php">
        <div class="form-group">
            <label for="offer-title">Offer Title:</label>
            <input type="text" id="offer-title" name="offer-title" required>
        </div>
        <div class="form-group">
            <label for="offer-description">Description:</label>
            <textarea id="offer-description" name="offer-description" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="offer-image">Upload Image:</label>
            <input type="file" id="offer-image" name="offer-image" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="offer-price">Offer Price:</label>
            <input type="number" id="offer-price" name="offer-price" required>
        </div>
        <div class="form-group">
            <label for="offer-percentage">Offer Percentage (%):</label>
            <input type="number" id="offer-percentage" name="offer-percentage" required>
        </div>
        <button type="submit">Add Offer</button>
    </form>

    <h3>Current Offers</h3>
    <div id="offers-list" class="offers-container">
        <?php
            require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

            $sql = "SELECT food_menu.food_id, food_menu.food_name, food_menu.discription, food_menu.price, food_menu.discount, food_img.img 
                FROM food_menu 
                INNER JOIN food_img ON food_menu.food_id = food_img.food_id 
                WHERE food_menu.category_id = 3"; // category_id 3 for offers
             $result = $conn->query($sql);

            if ($result->num_rows > 0) {
             while ($row = $result->fetch_assoc()) {
                echo '<div class="offer-item">';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['img']) . '" alt="Offer Image">';
                echo '<h4>' . $row['food_name'] . '</h4>';
                echo '<p>' . $row['discription'] . '</p>';
                echo '<p>Price: $' . $row['price'] . '</p>';
                echo '<p>Discount: ' . $row['discount'] . '%</p>';
                // Update and Delete buttons
                echo '<button class="btn-update" data-id="' . $row['food_id'] . '">Update</button>';
                echo '<a href="offer/delete_offer.php?id=' . $row['food_id'] . '" class="btn-delete" onclick="return confirm(\'Are you sure you want to delete this offer?\')">Delete</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No current offers available.</p>';
        }
        ?>
    </div>
    <!-- Update Offer Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="update-offer-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="update-title">Offer Title:</label>
                <input type="text" id="update-title" name="offer-title" required>
            </div>
            <div class="form-group">
                <label for="update-description">Description:</label>
                <textarea id="update-description" name="offer-description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="update-image">Upload New Image (Optional):</label>
                <input type="file" id="update-image" name="offer-image" accept="image/*">
            </div>
            <div class="form-group">
                <label for="update-price">Offer Price:</label>
                <input type="number" id="update-price" name="offer-price" required>
            </div>
            <div class="form-group">
                <label for="update-percentage">Offer Percentage (%):</label>
                <input type="number" id="update-percentage" name="offer-percentage" required>
            </div>
            <input type="hidden" id="food-id" name="food-id">
            <button type="submit">Update Offer</button>
        </form>
    </div>
    <style>/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fff;
    margin: 1% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>
</div>
<script>// Modal handling
const modal = document.getElementById("updateModal");
const span = document.getElementsByClassName("close")[0];
const updateButtons = document.querySelectorAll(".btn-update");

updateButtons.forEach(button => {
    button.addEventListener("click", function() {
        const foodId = this.getAttribute("data-id");
        
        // Fetch the offer details using AJAX (or pass them directly)
        fetch(`offer/fetch_offer.php?id=${foodId}`)
            .then(response => response.json())
            .then(data => {
                // Populate the modal fields with the fetched data
                document.getElementById("update-title").value = data.food_name;
                document.getElementById("update-description").value = data.description;
                document.getElementById("update-price").value = data.price;
                document.getElementById("update-percentage").value = data.discount;
                document.getElementById("food-id").value = foodId;
                
                // Open the modal
                modal.style.display = "block";
            });
    });
});

// Close the modal when the user clicks the "x" button
span.onclick = function() {
    modal.style.display = "none";
}

// Close the modal when the user clicks outside the modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Update the offer when the form is submitted
document.getElementById("update-offer-form").addEventListener("submit", function(event) {
    event.preventDefault();

    // Prepare the form data
    const formData = new FormData(this);

    fetch("offer/update_offer.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Assume the response is JSON for success/failure
    .then(data => {
        if (data.success) {
            alert("Offer updated successfully.");
            modal.style.display = "none";
            window.location.reload(); // Reload to reflect changes
        } else {
            alert("Error updating offer: " + data.error);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("There was a problem with the update request.");
    });
});


</script>
</section> 
</body>
</html>