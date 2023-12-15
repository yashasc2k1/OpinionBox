

<?php

session_start();

if(isset($_SESSION['registration_success'])){
    $text = "Registration Successful";
}
else{
    $text = "";
}
// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the home page or dashboard
    $uid = $_SESSION['user_id'];
    header('Location: home.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate login credentials and perform database query
    // Add your database connection code here
    $db_host = 'localhost';
    $db_name = 'opinionbox';
    $db_user = 'yashas';
    $db_pass = 'ZxMn@123';

    // Create a database connection
    $conn =  new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


    // Prepare and execute the query to check the user's credentials
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    // If the user is authenticated, store the user ID in session and redirect to the home page
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        $_SESSION['user_id'] = $user_id;
        header('Location: home.php');
        exit;
    } else {
        // If login fails, display an error message
        $error_message = "Invalid email or password.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}

// Display the login page
include 'index.php';
?>
