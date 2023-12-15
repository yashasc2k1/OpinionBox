<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
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

        .university-name {
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
    <center><p class="university-name">Reva University</p></center>
    <div class="container">
        <h2>Login Page</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
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

    <div class="alert-container">
        <div class="alert" id="register-alert" role="alert alert-success">
            <?php echo isset($text) ? $text : ''; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
    <script>
        //showing the error if error_message is not empty
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

    <script>
        
        $(document).ready(function() {
            <?php if (isset($_SESSION['registration_success'])) : ?>
                $('#register-alert').addClass('show');
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
        *You can submit only one opinion for each classmate.<br>
        *You can choose to submit anonymously. <br><br><br>

    View Public Opinions:<br>
        *Within the student's Opinion Box, you will find a "View Public Opinions" button.<br>
        *Click on this button to see the opinions submitted by other students for the same classmate.<br>
        *You can read and view the ratings given by other students on that classmate.<br><br><br>

    Like Other Opinions:<br>
        *In the "View Public Opinions" section, you will have the option to "like" the opinions submitted by other students.<br>
        *Click the "Like" button to express your agreement or appreciation for a particular opinion.<br>
        *You can only like an opinion once.<br>
    </div>
</body>
</html>