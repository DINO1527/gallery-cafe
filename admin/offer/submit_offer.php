<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $offer_title = $_POST['offer-title'];
    $offer_description = $_POST['offer-description'];
    $offer_price = $_POST['offer-price'];
    $offer_percentage = $_POST['offer-percentage'];

    // Handle image upload
    if (isset($_FILES['offer-image']) && $_FILES['offer-image']['error'] == 0) {
        $image = $_FILES['offer-image']['tmp_name'];
        $imgData = addslashes(file_get_contents($image));

        // Insert into food_menu
        $sql = "INSERT INTO food_menu (category_id, food_name, discription, price, discount) 
                VALUES (3, '$offer_title', '$offer_description', '$offer_price', '$offer_percentage')";
        
        if ($conn->query($sql) === TRUE) {
            // Get the inserted food_id
            $food_id = $conn->insert_id;

            // Insert image into food_img
            $sql_img = "INSERT INTO food_img (food_id, img) VALUES ('$food_id', '$imgData')";
            if ($conn->query($sql_img) === TRUE) {
                echo "New offer added successfully.";
            } else {
                echo "Error uploading image: " . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Image upload failed.";
    }
    echo "<script>
            alert('Offer added successfully!');
            window.location.href = '/gallarycafe/admin/index.php';  // Replace 'offers_page.php' with your actual page name
          </script>";
}

//$conn->close();
?>
