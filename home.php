<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
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

        .section-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .section-list li {
            margin-bottom: 10px;
        }

        .section-list li a {
            display: block;
            padding: 10px;
            background-color: #f8f9fa;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
        }

        .section-list li a:hover {
            background-color: #e9ecef;
        }

               .branch-name {
  font-size: 12px;
  color: #888;
  margin-top: -10px;
}

            </style>
</head>
<body>

	<?php 
		session_start();
		if(!isset($_SESSION['user_id'])){
			exit();
		}
	 ?>

	<center><h1>Opinion Box</h1></center>
	<center><p class="branch-name">Computer Science and Engineering</p></center>
    <div class="container">
        
        <h2>Sections</h2>
        <ul class="section-list">
            <li><a href="student.php?section=1">Section A</a></li>
            <li><a href="student.php?section=2">Section B</a></li>
            <li><a href="student.php?section=3">Section C</a></li>
            <li><a href="student.php?section=4">Section D</a></li>
            <li><a href="student.php?section=5">Section E</a></li>
            <li><a href="student.php?section=6">Section F</a></li>
            <li><a href="student.php?section=7">Section G</a></li>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
</body>
</html>


