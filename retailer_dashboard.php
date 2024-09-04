<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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
    <title><?php echo htmlspecialchars($role_name); ?> Dashboard - MyShop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.css">
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Styles */
        header {
            background: linear-gradient(90deg, #4CAF50, #2e8b57);
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-bottom: 2px solid #2e8b57;
        }
        .logo {
            font-size: 2.2em;
            font-weight: bold;
            letter-spacing: 1px;
            cursor: pointer;
            transition: color 0.3s;
        }
        .logo:hover {
            color: #ffe500;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        nav ul li {
            position: relative;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 12px;
            transition: background-color 0.3s, color 0.3s;
            display: block;
            font-size: 16px;
        }
        nav ul li a:hover {
            background-color: #45a049;
            color: #fff;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            min-width: 200px;
        }
        .dropdown-menu a {
            color: #333;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }
        .dropdown-menu a:hover {
            background-color: #f5f5f5;
        }
        .nav-item:hover .dropdown-menu {
            display: block;
        }
        .search-bar {
            position: relative;
            margin-left: auto;
        }
        .search-bar input {
            padding: 10px;
            border-radius: 12px;
            border: 1px solid #ddd;
            font-size: 16px;
            width: 200px;
        }
        .search-bar button {
            position: absolute;
            right: 0;
            top: 0;
            border: none;
            background: transparent;
            color: #4CAF50;
            font-size: 16px;
            cursor: pointer;
            padding: 10px;
            border-radius: 0 12px 12px 0;
        }

        /* Dashboard Styles */
        .dashboard-content {
            flex: 1;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .dashboard-heading {
            font-size: 2.8em;
            color: #2e8b57;
            margin-bottom: 10px;
            font-weight: 700;
            text-align: center;
        }
        .dashboard-intro {
            font-size: 1.2em;
            color: #666;
            margin-bottom: 40px;
            text-align: center;
        }
        .dashboard-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .dashboard-link {
            display: inline-block;
            padding: 15px 30px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 12px;
            font-size: 18px;
            transition: background-color 0.4s, transform 0.4s, box-shadow 0.4s;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            font-weight: 500;
        }
        .dashboard-link:hover {
            background-color: #1f5d49;
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        /* Summary Card Styles */
        .dashboard-summary {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .summary-card {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            text-align: left;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        .summary-card h3 {
            font-size: 1.6em;
            color: #2c6b59;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .progress-bar {
            background-color: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            height: 20px;
            margin-bottom: 15px;
        }
        .progress {
            height: 100%;
            background-color: #4CAF50;
            border-radius: 10px;
            transition: width 0.4s ease;
        }
        .summary-link, button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            transition: background-color 0.4s, transform 0.4s, box-shadow 0.4s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-weight: 500;
            margin-top: 10px;
        }
        .summary-link:hover, button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: auto;
            position: relative;
            border-top: 2px solid #4CAF50;
        }
        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .footer-links {
            margin-top: 10px;
        }
        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">MyShop</div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li class="nav-item">
                    <a href="#">Orders</a>
                    <div class="dropdown-menu">
                        <a href="pending_orders.php">Pending Orders</a>
                        <a href="completed_orders.php">Completed Orders</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="#">Products</a>
                    <div class="dropdown-menu">
                        <a href="products.php">View Products</a>
                        <a href="add_product.php">Add Product</a>
                    </div>
                </li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
            <div class="search-bar">
                <input type="text" placeholder="Search...">
                <button><i class="fas fa-search"></i></button>
            </div>
        </nav>
    </header>

    <div class="dashboard-content">
        <h1 class="dashboard-heading">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p class="dashboard-intro">Here’s a quick overview of your dashboard.</p>

        <div class="dashboard-links">
            <a href="pending_orders.php" class="dashboard-link">Pending Orders</a>
            <a href="completed_orders.php" class="dashboard-link">Completed Orders</a>
            <a href="products.php" class="dashboard-link">View Products</a>
            <a href="add_product.php" class="dashboard-link">Add Product</a>
        </div>

        <div class="dashboard-summary">
            <div class="summary-card">
                <h3>Order Summary</h3>
                <div class="progress-bar">
                    <div class="progress" style="width: 75%;"></div>
                </div>
                <p>75% of your orders are completed.</p>
                <a href="orders.php" class="summary-link">View Details</a>
            </div>
            <div class="summary-card">
                <h3>Product Stats</h3>
                <div class="progress-bar">
                    <div class="progress" style="width: 50%;"></div>
                </div>
                <p>50% of your products are in stock.</p>
                <a href="products.php" class="summary-link">View Details</a>
            </div>
        </div>
        
        <!-- Placeholder for advanced charts -->
        <div style="margin-top: 40px;">
            <h2>Sales Overview</h2>
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 MyShop. All rights reserved.</p>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact Us</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
    <script>
        // Example of advanced chart with Chart.js
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Sales',
                    data: [50, 60, 70, 80, 90, 100],
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return 'Sales: $' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
