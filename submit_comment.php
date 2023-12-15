<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve the form data
        $comment = $_POST['comment'];
        $rating = (10 - $_POST['rating']) + 1;
        $anonymous = isset($_POST['anonymous']) ? 1 : 0;
        $studentId = $_POST['studentId'];
        $userId = $_POST['userId'];

        // Validate the form data (you can add your own validation logic here)

        // Connect to the database
        $conn = mysqli_connect('localhost', 'yashas', 'ZxMn@123', 'opinionbox');

        // Check if the connection was successful
        if ($conn) {
            // Check if the user has already submitted a comment for the corresponding student
            $query = "SELECT id FROM comments WHERE student_id = $studentId AND user_id = $userId";
            $result = mysqli_query($conn, $query);

            // If the user has already submitted a comment, redirect to comment.php with an alert
            if (mysqli_num_rows($result) > 0) {
                mysqli_close($conn);
    		$_SESSION['comment_submission'] = "You've already submitted for this student!";
    		header('Location: comment.php?studentId=' . $studentId);
    		exit();
            }

            // Prepare the SQL statement to insert the comment
            $query = "INSERT INTO comments (student_id, user_id, comment, rating, anonymous) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);

            // Bind the parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, 'iisii', $studentId, $userId, $comment, $rating, $anonymous);

            // Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Comment submitted successfully
                $_SESSION['comment_submission'] = "Submission Successful!";
    			header('Location: comment.php?studentId=' . $studentId);
    			exit();
            } else {
                // Comment submission failed
                echo '<p>Failed to submit the comment. Please try again later.</p>';

            }

            // Close the prepared statement
            mysqli_stmt_close($stmt);

            // Close the database connection
            mysqli_close($conn);
        } else {
            // Database connection error
            echo '<p>Database connection error</p>';
        }
    } else {
        // Invalid request
        echo '<p>Invalid request</p>';
    }
} else {
    // User not logged in
    echo '<p>Please log in to submit a comment.</p>';
    header('Location: index.php');
}
?>
