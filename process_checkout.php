// <?php
// $servername = "127.0.0.1"; // Change if needed
// $username = "root"; // Change if needed
// $password = ""; // Change if needed
// $dbname = "closetcraft"; // Your database name
//
// $conn = new mysqli($servername, $username, $password, $dbname);
//
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
//
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $name = $_POST["name"];
//     $phone = $_POST["phone"];
//     $address = $_POST["address"];
//     $order_items = $_POST["order_items"];
//     $total_price = $_POST["total_price"];
//
//     $sql = "INSERT INTO checkout_orders (name, phone, address, order_items, total_price)
//             VALUES (?, ?, ?, ?, ?)";
//
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ssssd", $name, $phone, $address, $order_items, $total_price);
//
//     if ($stmt->execute()) {
//         echo "<script>
//             alert('Order placed successfully!');
//             window.location.href = 'payment.html';
//         </script>";
//     } else {
//         echo "Error: " . $stmt->error;
//     }
//
//     $stmt->close();
// }
//
// $conn->close();
// ?>
<?php
// Database credentials
$servername = "127.0.0.1"; // Use IP instead of 'localhost' to avoid socket issues
$username = "root";
$password = ""; // Leave empty for XAMPP or WAMP if default
$dbname = "closetcraft";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<h3>Connection failed: " . $conn->connect_error . "</h3>");
}

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $name = $conn->real_escape_string($_POST["name"] ?? '');
    $phone = $conn->real_escape_string($_POST["phone"] ?? '');
    $address = $conn->real_escape_string($_POST["address"] ?? '');
    $order_items = $conn->real_escape_string($_POST["order_items"] ?? '');
    $total_price = floatval($_POST["total_price"] ?? 0);

    // Basic validation
    if (empty($name) || empty($phone) || empty($address) || empty($order_items) || $total_price <= 0) {
        echo "<script>
            alert('Please fill in all fields correctly.');
            window.history.back();
        </script>";
        exit;
    }

    // Insert order into database
    $sql = "INSERT INTO checkout_orders (name, phone, address, order_items, total_price)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssd", $name, $phone, $address, $order_items, $total_price);

        if ($stmt->execute()) {
            echo "<script>
                alert('Order placed successfully!');
                window.location.href = 'payment.html';
            </script>";
        } else {
            echo "<h3>Error executing query: " . $stmt->error . "</h3>";
        }

        $stmt->close();
    } else {
        echo "<h3>SQL Prepare failed: " . $conn->error . "</h3>";
    }
}

$conn->close();
?>
