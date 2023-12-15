<?php
session_start();


$success_message = "Check you E-mail for the 6 digit pin.";

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the home page or dashboard
    header('Location: home.php');
    exit;
}

// Check if the verification code is not stored in the session
if (!isset($_SESSION['verification_code'])) {
    // Redirect to the registration page
    header('Location: register.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $verificationCoder = $_POST['verify_code'];
    $sessionCoder = $_SESSION['verification_code'];
    echo "$verificationCode";
    echo " $sessioncode";
    // Check if the verification code matches the one stored in the session
    if ($verificationCoder == $sessionCoder) {
        // Retrieve user data from the session
        $userData = $_SESSION['user_data'];

        // Perform database query to register the user
        // Add your database connection code here
        $db_host = 'localhost';
        $db_name = 'opinionbox';
        $db_user = 'yashas';
        $db_pass = 'ZxMn@123';

        // Create a database connection
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert the user data into the database
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $userData['email'], $userData['name'], $userData['password']);
        $stmt->execute();

        // Close the database connection
        $stmt->close();
        $conn->close();

        // Clear the session data
        unset($_SESSION['verification_code']);
        unset($_SESSION['user_data']);

        // Display a success message
        
        $_SESSION['registration_success'] = true;
        // Redirect to the login page
        header('Location: login.php');

        exit;
    } else {
        // If verification fails, display an error message
        $error_message = "Invalid verification code.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verification Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 0;
        }

        .alert-container {
            position: fixed;
            bottom: 10px;
            right: 10px;
            width: 300px;
        }

        .alert {
            display: none;
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }

        .alert.show {
            display: block;
        }
               .branch-name {
  font-size: 12px;
  color: #888;
  margin-top: -10px;
}
    </style>
</head>
<body>
	
    <center><h1>Opinion Box</h1></center>
    <center><p class="branch-name">Computer Science and Engineering</p></center>
    <div class="container">
        <h2>Verification Page</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Verification Code:</label>
                <input type="text" name="verify_code" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Verify</button>
            </div>
        </form>
    </div>

    <div class="alert-container">
        <div class="alert" id="success-alert" role="alert">
            <?php echo isset($success_message) ? $success_message : ''; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <div class="alert-container">
        <div class="alert" id="error-alert" role="alert">
            <?php echo isset($error_message) ? $error_message : ''; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
    <script>
        // Showing the error if error_message is not empty
        $(document).ready(function() {
            <?php if (isset($success_message)) : ?>
                $('#success-alert').addClass('show');
            <?php endif; ?>
        });

        // Close the alert when the close button is clicked
        $('.alert .close').on('click', function() {
            $(this).closest('.alert').removeClass('show');
        });
    </script>
    <script>
        // Showing the error if error_message is not empty
        $(document).ready(function() {
            <?php if (isset($error_message)) : ?>
                $('#error-alert').addClass('show');
            <?php endif; ?>
        });

        // Close the alert when the close button is clicked
        $('.alert .close').on('click', function() {
            $(this).closest('.alert').removeClass('show');
        });
    </script>
</body>
</html>
