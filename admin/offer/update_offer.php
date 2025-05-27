<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food_id = $_POST['food-id'];
    $offer_title = $_POST['offer-title'];
    $offer_description = $_POST['offer-description'];
    $offer_price = $_POST['offer-price'];
    $offer_percentage = $_POST['offer-percentage'];

    // Check if an image file is uploaded
    if (isset($_FILES['offer-image']) && $_FILES['offer-image']['error'] == UPLOAD_ERR_OK) {
        $imgData = file_get_contents($_FILES['offer-image']['tmp_name']);
        $imgEncoded = base64_encode($imgData);
    }

    // Prepare the update query
    $sql = "UPDATE food_menu SET food_name = ?, discription = ?, price = ?, discount = ? WHERE food_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the parameters to the SQL query
        $stmt->bind_param("ssdii", $offer_title, $offer_description, $offer_price, $offer_percentage, $food_id);

        // Execute the query
        if ($stmt->execute()) {
            // If there is an image update, insert it
            if (isset($imgEncoded)) {
                $imgSql = "UPDATE food_img SET img = ? WHERE food_id = ?";
                $imgStmt = $conn->prepare($imgSql);
                if ($imgStmt) {
                    $imgStmt->bind_param("si", $imgEncoded, $food_id);
                    $imgStmt->execute();
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to prepare image update query.']);
                    exit;
                }
            }
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to execute the update query.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare the update query.']);
    }

    $stmt->close();
    $conn->close();
}
?>
