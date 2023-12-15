<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '/var/www/html/OpinionBox/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/var/www/html/OpinionBox/vendor/phpmailer/phpmailer/src/Exception.php';
require '/var/www/html/OpinionBox/vendor/phpmailer/phpmailer/src/SMTP.php';
require '/var/www/html/OpinionBox/vendor/autoload.php';

function sendVerificationEmail($email, $verificationCode) {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration (replace with your own settings)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'opinionbox2023@gmail.com';
        $mail->Password = 'buwfdrwgokrtayvj';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('opinionbox2023@gmail.com', 'OpinionBox');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Verification Code';
        $mail->Body = 'Your verification code is: ' . $verificationCode;

        // Send the email
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}


// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the home page or dashboard
    header('Location: home.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate form data (e.g., check if email is valid, password meets requirements, etc.)
    // Add your validation code here

    // Check if the password and confirm password match
    if ($password !== $confirmPassword) {
        $error_message = "Password and Confirm Password do not match.";
        } 
        else {
        // Perform registration and database query
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

        // Check if the email already exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "User already exists with this email.";
        } else {
            // Generate a 6-digit OTP (verification code)
            $verificationCode = mt_rand(100000, 999999);

            // Store the verification code in the session
            $_SESSION['verification_code'] = $verificationCode;

            $email = $_POST["email"];
			sendVerificationEmail($email, $verificationCode);
            

            // Store the user data in the session
        	$_SESSION['user_data'] = array(
                'email' => $email,
                'name' => $name,
                'password' => $password
            );

            // Redirect to the verification page
            header('Location: verification.php');
            exit;
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 4px 6px 12px rgba(0, 0, 0, 0.1);
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

 .description {
            text-align: center;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <center><h1>Opinion Box</h1></center>
    <center><p class="branch-name">Computer Science and Engineering</p></center>

    
    <div class="container">
        <h2>Registration Page</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
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
            <?php if (isset($error_message)) : ?>
                $('#error-alert').addClass('show');
            <?php endif; ?>
        });

        // Close the alert when the close button is clicked
        $('.alert .close').on('click', function() {
            $(this).closest('.alert').removeClass('show');
        });
    </script>

    <div class="description">
            Instructions: <br><br>
            Registration and Login:<br>
        *Visit the Opinion Box website. <br>
        *Click on the "Register" link to create a new account. <br>
        *Fill in the required registration details, such as your name, email, and password. <br>
        *Click the "Register" button to create your account. <br>
        *Once registered, you can log in using your email and password. <br> <br><br>

    Classmates List: <br>
        *After logging in, you will be able to see a list of all your classmates. <br>
        *The classmates will be categorized according to their respective classrooms. <br>
        *Click on a classmate's name to view their Opinion Box. <br><br><br>

    Opinion Submission: <br>
        *In the classmate's Opinion Box, you will find a text field and a star rating system.<br>
        *Enter your opinion about the classmate in the text field.<br>
        *Rate the classmate using the 10-star rating system.<br>
        *Both the opinion and rating fields are mandatory, so make sure to provide your feedback.<br>
        *You can submit only one opinion for each classmate.<br><br><br>

    View Public Opinions:<br>
        *Within the classmate's Opinion Box, you will find a "View Public Opinions" button.<br>
        *Click on this button to see the opinions submitted by other students for the same classmate.<br>
        *You can read and view the ratings given by other students on that classmate.<br><br><br>

    Like Other Opinions:<br>
        *In the "View Public Opinions" section, you will have the option to "like" the opinions submitted by other students.<br>
        *Click the "Like" button to express your agreement or appreciation for a particular opinion.<br>
        *You can only like an opinion once.<br>
    </div>
</body>
</html>
