<?php
header('Content-Type: application/json');

// Database connection
require_once('dbconn.php');

// Fetch data from table 'offer'
$sql = "SELECT food_menu.food_name, food_menu.discount, food_img.img
        FROM food_menu
        JOIN food_category ON food_menu.category_id = food_category.category_id
        JOIN food_img ON food_menu.food_id = food_img.food_id
        WHERE food_category.category_name = 'offer'";
$result = $conn->query($sql);

// Prepare data to send to the front end
$offers = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $offers[] = [ 
            'food_name' => $row['food_name'],
            'discount' => $row['discount'],
            'image' => 'data:image/jpeg;base64,' . base64_encode($row['img']) // Correct field name
        ];
    }
}

$conn->close();

// Encode data to JSON and send it to the frontend
echo json_encode($offers);
?>
