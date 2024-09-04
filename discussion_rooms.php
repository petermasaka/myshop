<?php
session_start();
require_once 'config.php';

// Ensure the user is logged in; if not, redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user role to determine whether they are a retailer or admin.
$sql = "SELECT role_name FROM roles WHERE role_id = (SELECT role_id FROM users WHERE user_id = ?)";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user_role = strtolower(trim($result->fetch_assoc()['role_name']));

// Debugging output (remove in production)
echo "User role fetched: " . htmlspecialchars($user_role) . "<br>";

// Define SQL query based on user role
if ($user_role == 'retailer') {
    $sql = "SELECT * FROM discussionrooms WHERE retailer_id = ?";
} elseif ($user_role == 'admin') {
    $sql = "SELECT * FROM discussionrooms WHERE admin_id = ?";
} else {
    echo "Unauthorized access.";
    exit();
}

// Prepare the SQL statement to fetch discussion rooms
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$discussion_rooms = $stmt->get_result();

// Include the dashboard header
include 'dashboard_header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Rooms</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        /* Header Styles */
        header {
            background: linear-gradient(to right, #4CAF50, #2e8b57);
            color: #fff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: box-shadow 0.3s;
        }
        .logo img {
            height: 60px;
            transition: transform 0.3s;
        }
        .logo img:hover {
            transform: scale(1.1);
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }
        nav ul li {
            position: relative;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            display: block;
        }
        nav ul li a:hover {
            background-color: #45a049;
            color: #fff;
        }
        /* Discussion Rooms Styles */
        .discussion-rooms-container {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
        .discussion-rooms-container h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }
        .discussion-rooms-container a {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .discussion-rooms-container a:hover {
            background-color: #45a049;
        }
        .discussion-rooms-container ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .discussion-rooms-container ul li {
            margin: 10px 0;
        }
        .discussion-rooms-container ul li a {
            color: #333;
            text-decoration: none;
            padding: 10px;
            border-radius: 4px;
            background-color: #e0e0e0;
            display: block;
            transition: background-color 0.3s;
        }
        .discussion-rooms-container ul li a:hover {
            background-color: #d0d0d0;
        }
    </style>
</head>
<body>
    <!-- Discussion Rooms Content -->
    <div class="discussion-rooms-container">
        <h2>Discussion Rooms</h2>
        <a href="create_discussion.php">Create New Discussion Room</a>
        <ul>
            <li><a href="view_discussion.php">View Previous Discussion Rooms</a></li>
        </ul>
        <ul>
            <?php while ($room = $discussion_rooms->fetch_assoc()): ?>
                <li><a href="view_discussion.php?discussion_id=<?php echo htmlspecialchars($room['discussion_id']); ?>"><?php echo htmlspecialchars($room['topic']); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>

    <?php include 'dashboard_footer.php'; ?>
</body>
</html>
