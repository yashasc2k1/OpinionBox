<!DOCTYPE html>
<html>
<head>

    <title>Opinion Box - View Comments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .like-button {
    background-color: #eaeaea;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
  }

  .like-icon {
    margin-right: 5px;
  }

  .like-count {
    font-weight: bold;
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

        .comment {
            margin-bottom: 20px;
        }

        .comment .meta {
            margin-bottom: 10px;
        }

        .comment .meta span {
            font-weight: bold;
        }

        .comment .comment-text {
            background-color: #f7f7f7;
            padding: 10px;
            border-radius: 8px;
        }

        .rating-stars {
            color: #ffcc00;
            margin-top: 10px;
        }
        
        .like-button {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #f7f7f7;
            cursor: pointer;
        }

        .like-button.like {
            color: green;
        }

        .like-count {
            margin-left: 10px;
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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
                    echo '<h2>Opinions for ' . $studentName . '</h2>';

                    // Retrieve the comments for the student
                    $query = "SELECT c.comment, c.rating, c.anonymous, u.email, c.id AS comment_id
                              FROM comments c
                              JOIN users u ON c.user_id = u.id
                              WHERE c.student_id = $studentId";
                    $result = mysqli_query($conn, $query);

                    // Check if comments exist
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $comment = $row['comment'];
                            $rating = $row['rating'];
                            $anonymous = $row['anonymous'];
                            $email = $row['email'];
                            $commentId = $row['comment_id'];

                            // Comment HTML
                            echo '<div class="comment">';
                            echo '<div class="meta">';
                            echo '<span>By: ' . ($anonymous ? 'Anonymous' : $email) . '</span>';
                            echo '</div>';
                            echo '<div class="rating-stars">' . generateStarRating($rating) . '</div>';
                            echo '<div class="comment-text">' . $comment . '</div>';
                            echo '<div class="actions">';
                            echo '<button type="button" class="like-button" data-comment-id="' . $commentId . '" onclick="updateLikeStatus(' . $commentId . ')">Like</button>';
                            echo '<span class="like-count">' . getLikeCount($conn, $commentId) . ' likes</span>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Opinion Box Empty.</p>';
                    }

                    echo '</div>'; // End of comment-section
                } else {
                    echo '<p>Database connection error</p>';
                }
            } else {
                echo '<p>Invalid student ID.</p>';
            }
        } else {
            echo '<p>Please log in to view comments</p>';
        }

        // Function to generate star rating HTML
        function generateStarRating($rating)
        {
            $ratingHtml = '';

            for ($i = 1; $i <= 10; $i++) {
                if ($i <= $rating) {
                    $ratingHtml .= '&#9733;';
                } else {
                    $ratingHtml .= '&#9734;';
                }
            }

            return $ratingHtml;
        }
        
        // Function to get the like count for a comment
        function getLikeCount($conn, $commentId)
        {
            $query = "SELECT COUNT(*) AS like_count FROM likes WHERE comment_id = $commentId";
            $result = mysqli_query($conn, $query);
            $likeCount = mysqli_fetch_assoc($result)['like_count'];

            return $likeCount;
        }
        ?>
    </div>


    


    <script>
        function updateLikeStatus(commentId) {
            // AJAX request to update the like status
            $.ajax({
                url: 'update_like.php',
                method: 'POST',
                data: { commentId: commentId, status: 'like' },
                success: function(response) {
                    // Parse the response JSON
                    var responseData = JSON.parse(response);

                    // Update the like count
                    var likeCountElement = $('[data-comment-id="' + commentId + '"]').find('.like-count');
                    likeCountElement.text(responseData.likeCount + ' likes');

                    // Update the like button style
                    var likeButton = $('[data-comment-id="' + commentId + '"]').find('.like-button');
                    likeButton.toggleClass('like', responseData.liked);
                }
            });
        }
    </script>


    <script>
    // Check if a like submission message is available in the session
    var likeSubmission = "<?php echo isset($_SESSION['like_submission']) ? $_SESSION['like_submission'] : ''; ?>";

    // Clear the session variable
    <?php unset($_SESSION['like_submission']); ?>

    // Display pop-up alert if a like submission message exists
    if (likeSubmission) {
        var alertContainer = $('<div class="alert-container"></div>');
        var alertElement = $('<div class="alert"></div>');
        var closeButton = $('<span class="close-button">&times;</span>');

        // Set the alert message
        alertElement.text(likeSubmission);

        // Append the close button to the alert
        alertElement.append(closeButton);

        // Append the alert to the alert container
        alertContainer.append(alertElement);

        // Append the alert container to the body
        $('body').append(alertContainer);

        // Show the alert
        alertElement.addClass('show');

        // Close the alert when the close button is clicked
        closeButton.on('click', function() {
            alertElement.removeClass('show');
            alertContainer.remove();
        });
    }
</script>


    
</body>
</html>
