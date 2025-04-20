<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "closetcraft";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, full_name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $user_email, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $full_name;
            $_SESSION['user_email'] = $user_email;

            header("Location: http://localhost/dbforip/home.html");
            exit();
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('No user found with this email.'); window.location.href='login.php';</script>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ClosetCraft</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 100%;
            max-width: 400px;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: scale(0.95);}
            to {opacity: 1; transform: scale(1);}
        }
        h2 {
            margin-bottom: 25px;
            color: #333;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: 0.3s ease;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ff758c;
            outline: none;
            background-color: #fff;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #ff758c, #ff7eb3);
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: linear-gradient(90deg, #ff7eb3, #ff758c);
        }
        .link {
            margin-top: 15px;
            font-size: 14px;
        }
        .link a {
            color: #ff758c;
            text-decoration: none;
            font-weight: bold;
        }
        .link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 480px) {
            .container {
                margin: 0 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login to ClosetCraft</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <div class="link">
            <a href="register.php">New here? Register</a>
        </div>
        <div class="link">
            <a href="home.html">Enter Website</a>
        </div>
    </div>
</body>
</html>
