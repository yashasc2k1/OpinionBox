<!DOCTYPE html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <title>Opinion Box - Comments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>

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
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .comment-section {
            margin-bottom: 30px;
        }

        .comment-section h2 {
            margin-bottom: 10px;
        }

        .comment-form {
            margin-top: 20px;
        }

        .comment-form .form-group {
            margin-bottom: 20px;
        }

        .comment-form textarea {
            height: 150px;
            resize: none;
        }

        .comment-form label {
            font-weight: bold;
        }

        .comment-form .rating-label {
            display: block;
            margin-bottom: 5px;
        }

        .comment-form .star-rating {
            display: flex;
            justify-content: center;
            flex-direction: row-reverse;
        }

        .comment-form .star-rating input {
            display: none;
        }

        .comment-form .star-rating label {
            cursor: pointer;
            font-size: 24px;
            color: #ddd;
        }

        .comment-form .star-rating label:hover,
        .comment-form .star-rating label:hover ~ label,
        .comment-form .star-rating input:checked ~ label {
            color: #ffcc00;
        }

        .comment-form .anonymous-label {
            margin-top: 10px;
        }

        .comment-form .submit-btn {
            margin-top: 20px;
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
        <?php
        session_start();

        // Check if the user is logged in
        if (isset($_SESSION['user_id'])) {
            // Check if the student ID is provided in the URL
            if (isset($_GET['studentId'])) {
                $studentId = $_GET['studentId'];
                $userId = $_SESSION['user_id'];

                // Connect to the database
                $conn = mysqli_connect('localhost', 'yashas', 'ZxMn@123', 'opinionbox');

                // Check if the connection was successful
                if ($conn) {
                    // Retrieve the student's name
                    $query = "SELECT name FROM students WHERE id = $studentId";
                    $result = mysqli_query($conn, $query);
                    $studentName = mysqli_fetch_assoc($result)['name'];

                    // Display the student's name
                    echo '<div class="comment-section">';
                    echo '<h2>' . $studentName . "'s Opinion Box</h2>";

                    // View Comments button
                    echo '<a href="view_comments.php?studentId=' . $studentId . '" class="btn btn-dark">View Public Opinion Box</a>';

                    // Comment Form
                    echo '<div class="comment-form">';
                    echo '<h3>Submit Your Opinion</h3>';
                    echo '<form action="submit_comment.php" method="POST">';
                    echo '<div class="form-group">';
                    echo '<label for="comment">Your Opinion:</label>';
                    echo '<textarea class="form-control" name="comment" id="comment" required></textarea>';
                    echo '</div>';
                    echo '<div class="form-group rating-label">';
                    echo '<label for="rating">Rate this person:</label>';
                    echo '<div class="star-rating">';
                    echo '<input type="radio" id="rating1" name="rating" value="1" required>';
                    echo '<label for="rating1">&#9733;</label>';
                    echo '<input type="radio" id="rating2" name="rating" value="2">';
                    echo '<label for="rating2">&#9733;</label>';
                    echo '<input type="radio" id="rating3" name="rating" value="3">';
                    echo '<label for="rating3">&#9733;</label>';
                    echo '<input type="radio" id="rating4" name="rating" value="4">';
                    echo '<label for="rating4">&#9733;</label>';
                    echo '<input type="radio" id="rating5" name="rating" value="5">';
                    echo '<label for="rating5">&#9733;</label>';
                    echo '<input type="radio" id="rating6" name="rating" value="6">';
                    echo '<label for="rating6">&#9733;</label>';
                    echo '<input type="radio" id="rating7" name="rating" value="7">';
                    echo '<label for="rating7">&#9733;</label>';
                    echo '<input type="radio" id="rating8" name="rating" value="8">';
                    echo '<label for="rating8">&#9733;</label>';
                    echo '<input type="radio" id="rating9" name="rating" value="9">';
                    echo '<label for="rating9">&#9733;</label>';
                    echo '<input type="radio" id="rating10" name="rating" value="10">';
                    echo '<label for="rating10">&#9733;</label>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="form-group form-check">';
                    echo '<input type="checkbox" class="form-check-input" name="anonymous" id="anonymous">';
                    echo '<label class="form-check-label anonymous-label" for="anonymous">Submit Anonymously</label>';
                    echo '</div>';
                    echo '<input type="hidden" name="studentId" value="' . $studentId . '">';
                    echo '<input type="hidden" name="userId" value="' . $userId . '">';
                    echo '<button type="submit" class="btn btn-primary submit-btn">Submit Comment</button>';
                    echo '</form>';
                    echo '</div>';

                    echo '</div>'; // End of comment-section
                } else {
                    echo '<p>Database connection error</p>';
                }
            } else {
                echo '<p>Invalid student ID. Try Logging in again.</p>';
                header('Location: index.php');
            }
        } else {
            echo '<p>Please log in to view or submit comments</p>';
            header('Location: index.php');
        }
        ?>
    </div>


    <div class="alert-container">
        <div class="alert" id="error-alert" role="alert">
            <?php 
            if(isset($_SESSION['comment_submission'])){
                $submitted = $_SESSION['comment_submission'];
            }

            echo isset($submitted) ? $submitted : ''; 
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php unset($_SESSION['comment_submission']); ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
    <script>
        //showing the error if error_message is not empty
        $(document).ready(function() {
            <?php 
            if(isset($_SESSION['comment_submission'])){
                $submitted = $_SESSION['comment_submission'];
            }

            if (isset($submitted)) : ?>
                $('#error-alert').addClass('show');
            <?php endif; ?>
        });

        // Close the alert when the close button is clicked
        $('.alert .close').on('click', function() {
            $(this).closest('.alert').removeClass('show');
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        // Update hidden rating input with the selected star rating
        $('.star-rating input').on('change', function () {
            var rating = $(this).val();
            $('#rating').val(rating);
        });
    </script>

    
</body>
</html>
