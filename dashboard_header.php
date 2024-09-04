<?php
session_start();
require_once 'config.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role_id = $_SESSION['role'];

$role_name = "";
switch ($role_id) {
    case 1:
        $role_name = "User";
        break;
    case 2:
        $role_name = "Retailer";
        break;
    case 3:
        $role_name = "Admin";
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $role_name; ?> Dashboard</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f2f4f7;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background: linear-gradient(to right, #2e8b57, #4CAF50);
            color: #fff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo h1 {
            font-size: 1.8em;
            margin: 0;
            font-weight: 700;
            background: -webkit-linear-gradient(left, #4CAF50, #2e8b57);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        nav ul li a:hover {
            background-color: #45a049;
            color: #fff;
        }

        section.dashboard {
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .dashboard-heading {
            font-size: 2.5em;
            color: #2e8b57;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
            font-weight: 700;
        }

        .dashboard-heading::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 70px;
            height: 8px;
            background: linear-gradient(to right, #2e8b57, #4CAF50);
            border-radius: 5px;
        }

        .dashboard-intro {
            font-size: 1.4em;
            color: #666;
            margin-bottom: 20px;
            font-weight: 300;
        }

        .tab-container {
            display: flex;
            border-bottom: 2px solid #2e8b57;
            margin-bottom: 20px;
        }

        .tab-button {
            flex: 1;
            padding: 10px 20px;
            background-color: #2e8b57;
            color: #fff;
            text-align: center;
            cursor: pointer;
            border: none;
            border-radius: 5px 5px 0 0;
            transition: background-color 0.3s;
        }

        .tab-button:hover {
            background-color: #256f43;
        }

        .tab-content {
            display: none;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }

        .tab-content.active {
            display: block;
        }

        .dashboard-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .dashboard-links li {
            margin: 25px 0;
        }

        .dashboard-link {
            display: inline-block;
            padding: 16px 32px;
            background-color: #2e8b57;
            color: #fff;
            text-decoration: none;
            border-radius: 12px;
            font-size: 18px;
            transition: background-color 0.4s, transform 0.4s, box-shadow 0.4s;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            font-weight: 500;
        }

        .dashboard-link:hover {
            background-color: #256f43;
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .dashboard-summary {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .summary-card {
            flex: 1;
            background-color: #f9f9f9;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .summary-card h3 {
            font-size: 1.6em;
            color: #2e8b57;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .progress-bar {
            background-color: #eee;
            border-radius: 15px;
            overflow: hidden;
            height: 10px;
            margin-bottom: 15px;
        }

        .progress {
            height: 100%;
            background-color: #2e8b57;
            border-radius: 15px;
            transition: width 0.4s;
        }

        .progress-text {
            font-size: 1.1em;
            color: #333;
            font-weight: 500;
        }

        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .activity-list li {
            background-color: #fff;
            border-radius: 12px;
            padding: 12px;
            margin: 8px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <h1>MyShop - <?php echo $role_name; ?> Dashboard</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <?php if ($role_id == 2): ?>
                    <li><a href="discussion_rooms.php">Dashboard</a></li>
                <?php elseif ($role_id == 3): ?>
                    <li><a href="manage_users.php">Manage Users</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section class="dashboard">
        <div class="dashboard-heading">Welcome to Your Dashboard</div>
        <div class="dashboard-intro">
            Here you can manage your activities, view reports, and more.
        </div>
        <div class="tab-container">
            <button class="tab-button" data-tab="tab1">Overview</button>
            <button class="tab-button" data-tab="tab2">Reports</button>
            <button class="tab-button" data-tab="tab3">Settings</button>
        </div>
        <div class="tab-content" id="tab1">
            <h2>Overview</h2>
            <p>Here is an overview of your recent activities and updates.</p>
        </div>
        <div class="tab-content" id="tab2">
            <h2>Reports</h2>
            <p>Check out your detailed reports and analytics here.</p>
        </div>
        <div class="tab-content" id="tab3">
            <h2>Settings</h2>
            <p>Manage your account settings and preferences here.</p>
        </div>
    </section>
    <script>
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.querySelector(`#${tabId}`).classList.add('active');
            });
        });
    </script>
</body>
</html>
