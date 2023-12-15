<!DOCTYPE html>
<html>
<head>
    <title>Opinion Box - Students</title>
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

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        h2 {
            text-align: center;
        }

        p {
            text-align: center;
        }
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .student-table th,
        .student-table td {
            padding: 10px; 
            text-align: center;
        }

               .branch-name {
  font-size: 12px;
  color: #888;
  margin-top: -10px;
}

  @keyframes blinking {
  0% { opacity: 2; }
  50% { opacity: 0.7; }
  100% { opacity: 2; }
}

.blinking-button {
  animation: blinking 1.5s infinite;
  transition: background-color 0.3s ease-in-out;
}

.blinking-button:hover {
  background-color: #ffcc00;
}
    </style>
</head>
<body>
    <center><h1>Opinion Box</h1></center>
    <center><p class="branch-name">Computer Science and Engineering</p></center>
    <div class="container">
        <?php
        session_start();
     
        // Check if the section is provided in the URL
        if (isset($_GET['section'])) {
            $section = $_GET['section'];

            // Connect to the database
            $conn = mysqli_connect('localhost', 'yashas', 'ZxMn@123', 'opinionbox');

            // Check if the connection was successful
            if ($conn) {
                // Retrieve the section title
                $query = "SELECT name FROM sections WHERE id = $section";
                $result = mysqli_query($conn, $query);
                $sectionName = mysqli_fetch_assoc($result)['name'];

                // Display the section title
                echo '<h2> CSE-' . $sectionName . '</h2>';

                // Retrieve the students belonging to the section
                $query = "SELECT * FROM students WHERE section_id = $section";
                $result = mysqli_query($conn, $query);

                // Check if there are any students
                if (mysqli_num_rows($result) > 0) {
                    echo '<table class="student-table">';
                    

                    // Display the students in a table
                    while ($row = mysqli_fetch_assoc($result)) {
                        $studentName = $row['name'];
                        $studentId = $row['id'];
                        $query2 = "SELECT * FROM comments where student_id = $studentId";
                        $res2 = mysqli_query($conn, $query2);
                        if(mysqli_num_rows($res2) > 0){
                            echo '<tr>';
                            echo '<td><a href="comment.php?studentId=' . $studentId . '" class="btn btn-success blinking-button">' . $studentName . '</a></td>';
                            echo '</tr>';
                        }
                        else{
                            echo '<tr>';
                            echo '<td><a href="comment.php?studentId=' . $studentId . '" class="btn btn-dark">' . $studentName . '</a></td>';
                            echo '</tr>';
                        }
                    }

                    echo '</table>';
                } else {
                    echo '<center><p>No students found for Section ' . $sectionName . '</p></center>';
                }

                // Close the database connection
                mysqli_close($conn);
            } else {
                echo '<p>Database connection error</p>';
            }
        } else {
            echo '<p>Invalid section</p>';
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
