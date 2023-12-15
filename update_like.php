<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Check if the comment ID and status are provided
if (!isset($_POST['commentId']) || !isset($_POST['status'])) {
    echo json_encode(['error' => 'Missing comment ID or status']);
    exit;
}

$commentId = $_POST['commentId'];
$status = $_POST['status'];

// Validate and sanitize the input values
$commentId = filter_var($commentId, FILTER_VALIDATE_INT);
$status = filter_var($status, FILTER_SANITIZE_STRING);

if ($commentId === false || $status === false) {
    echo json_encode(['error' => 'Invalid comment ID or status']);
    exit;
}

// Connect to the database
$conn = mysqli_connect('localhost', 'yashas', 'ZxMn@123', 'opinionbox');

// Check if the connection was successful
if (!$conn) {
    echo json_encode(['error' => 'Database connection error']);
    exit;
}

// Retrieve the user ID from the session
$userId = $_SESSION['user_id'];

// Check if the user has already liked or disliked the comment
$query = "SELECT * FROM likes WHERE user_id = $userId AND comment_id = $commentId";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Database query error']);
    exit;
}

$existingLike = mysqli_fetch_assoc($result);

if ($existingLike) {
    $_SESSION['like_submission'] = "You have already liked this comment";
    echo json_encode(['error' => 'Already Liked']);
    exit;
}

// Insert the new like if the user has not already liked the comment
if (!$existingLike) {
    // Insert the new like
    $query = "INSERT INTO likes (user_id, comment_id, like_status) VALUES ($userId, $commentId, '$status')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode(['error' => 'Failed to insert like']);
        exit;
    }

    $_SESSION['like_submission'] = "Like successful";
} else {
    // User has already liked or disliked the comment
    $existingStatus = $existingLike['like_status'];

    if ($status === $existingStatus) {
        // User is trying to submit the same like status again
        echo json_encode(['error' => 'Cannot submit duplicate like status']);
        exit;
    }

    // Update the existing like status
    $query = "UPDATE likes SET like_status = '$status' WHERE user_id = $userId AND comment_id = $commentId";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode(['error' => 'Failed to update like status']);
        exit;
    }

    $_SESSION['like_submission'] = "Like status updated";
}

// Get the updated like count for the comment
$query = "SELECT COUNT(*) AS like_count FROM likes WHERE comment_id = $commentId AND like_status = 'like'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Failed to fetch updated like count']);
    exit;
}

$likeCount = mysqli_fetch_assoc($result)['like_count'];

// Return the updated like count as a JSON response
$response = ['likeCount' => $likeCount];
echo json_encode($response);

exit;
?>
