<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/gallarycafe/dbconn.php');

// Initialize variables
$category = isset($_POST['category']) ? $_POST['category'] : '';
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

// Base SQL query
$sql1 = "SELECT fm.food_id, fc.category_name, fm.food_name, fm.discription, fm.price, fi.img 
         FROM food_menu fm
         INNER JOIN food_category fc ON fm.category_id = fc.category_id
         INNER JOIN food_img fi ON fm.food_id = fi.food_id";

// Parameters for prepared statement
$params = [];
$types = "";

// Modify SQL based on search or category
if (!empty($search)) {
    $sql1 .= " WHERE fc.category_name LIKE ? OR fm.food_name LIKE ?";
    $searchParam = '%' . $search . '%';
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "ss";
} elseif (!empty($category) && strtolower($category) !== 'all') {
    $sql1 .= " WHERE fc.category_name = ?";
    $params[] = $category;
    $types .= "s";
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql1);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result1 = $stmt->get_result();

if ($result1->num_rows > 0) {
    echo '<div class="filters-content">';
    echo '<div class="row grid">';
    
    while ($row = $result1->fetch_assoc()) {
        $foodId = $row['food_id'];
        $foodName = htmlspecialchars($row['food_name'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($row['discription'], ENT_QUOTES, 'UTF-8');
        $price = number_format($row['price'], 2);
        $imgData = base64_encode($row['img']); // Encode the image

        echo '<div class="col-sm-6 col-lg-4 style1 all ' . strtolower($row['category_name']) . '">';
        echo '  <div class="box">';
        echo '    <div>';
        echo '      <div class="img-box">';
        echo '        <img src="data:image/jpeg;base64,' . $imgData . '" alt="' . $foodName . '">';
        echo '      </div>';
        echo '      <div class="detail-box">';
        echo '        <h5>' . $foodName . '</h5>';
        echo '        <p>' . $description . '</p>';
        echo '        <div class="options">';
        echo '          <h6>RS ' . $price . '</h6>';
        echo '           <button class="add-to-cart fas fa-shopping-cart" data-id="' . $foodId . '" data-name="' . $foodName . '" data-price="' . $row['price'] . '"></button>';
        echo '        </div>';
        echo '      </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo '<div class="filters-content">';
    echo '<div class="row grid">';
    echo '<p>No food items found for this ';
    echo !empty($search) ? 'search term.' : 'category.';
    echo '</p>';
    echo '</div>';
    echo '</div>';
}

if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
