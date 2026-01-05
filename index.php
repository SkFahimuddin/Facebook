<?php
require_once 'config.php';

// If already logged in, redirect to home
if (is_logged_in()) {
    header("Location: home.php");
    exit();
}

$error = '';
$success = '';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $gender = clean_input($_POST['gender']);
    
    // Validate email (2004 version required .edu)
    if (!strpos($email, '.edu')) {
        $error = "You must have a .edu email address to register.";
    } else {
        // Check if email already exists
        $check_query = "SELECT id FROM users WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "This email is already registered.";
        } else {
            // Insert new user
            $hashed_password = md5($password); // 2004 used MD5 (not secure by today's standards)
            $insert_query = "INSERT INTO users (email, password, first_name, last_name, gender) 
                            VALUES ('$email', '$hashed_password', '$first_name', '$last_name', '$gender')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success = "Registration successful! You can now log in.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = clean_input($_POST['login_email']);
    $password = md5($_POST['login_password']);
    
    $login_query = "SELECT id, first_name FROM users WHERE email = '$email' AND password = '$password'";
    $login_result = mysqli_query($conn, $login_query);
    
    if (mysqli_num_rows($login_result) == 1) {
        $user = mysqli_fetch_assoc($login_result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>TheFacebook</title>
    <style>
        body {
            font-family: Tahoma, Arial, sans-serif;
            background-color: #3B5998;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
        }
        .header {
            color: #3B5998;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f7f7f7;
        }
        h2 {
            color: #3B5998;
            font-size: 18px;
        }
        input[type="text"], input[type="password"], input[type="email"], select {
            padding: 5px;
            margin: 5px 0;
            width: 250px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #3B5998;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #2d4373;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        .success {
            color: green;
            margin: 10px 0;
        }
        label {
            display: block;
            margin-top: 8px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">TheFacebook</div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="section">
            <h2>Login</h2>
            <form method="POST" action="">
                <label>Email:</label>
                <input type="email" name="login_email" required>
                
                <label>Password:</label>
                <input type="password" name="login_password" required>
                
                <input type="submit" name="login" value="Login">
            </form>
        </div>
        
        <div class="section">
            <h2>Register</h2>
            <p>You must have a .edu email address to register.</p>
            <form method="POST" action="">
                <label>First Name:</label>
                <input type="text" name="first_name" required>
                
                <label>Last Name:</label>
                <input type="text" name="last_name" required>
                
                <label>Email (.edu required):</label>
                <input type="email" name="email" required>
                
                <label>Password:</label>
                <input type="password" name="password" required>
                
                <label>Gender:</label>
                <select name="gender" required>
                    <option value="">Select...</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                
                <input type="submit" name="register" value="Register">
            </form>
        </div>
    </div>
</body>
</html>