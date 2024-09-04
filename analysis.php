<?php include 'dashboard_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales & Engagement Analysis</title>
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

        /* Analysis Page Styles */
        .analysis-page-container {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }

        .analysis-page-container h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }

        .chart-container {
            margin-bottom: 30px;
        }

        canvas {
            width: 100% !important;
            height: 400px !important;
        }
    </style>
</head>
<body>
    <!-- Analysis Page Content -->
    <div class="analysis-page-container">
        <h2>Sales & Engagement Analysis</h2>
        
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
        
        <div class="chart-container">
            <canvas id="engagementChart"></canvas>
        </div>
        
        <div class="chart-container">
            <canvas id="purchaseChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Example data for charts (Replace with dynamic data from the database)
        var salesData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Sales',
                data: [1200, 1500, 1800, 1600, 1400, 1700, 2000], // Adjusted data to show trends
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var engagementData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Engagement',
                data: [300, 400, 500, 600, 700, 600, 500], // Adjusted data to show trends
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        var purchaseData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Purchases',
                data: [800, 1000, 1200, 1300, 1200, 1100, 1400], // Adjusted data to show trends
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        var ctxSales = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctxSales, {
            type: 'line',
            data: salesData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctxEngagement = document.getElementById('engagementChart').getContext('2d');
        var engagementChart = new Chart(ctxEngagement, {
            type: 'line',
            data: engagementData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctxPurchase = document.getElementById('purchaseChart').getContext('2d');
        var purchaseChart = new Chart(ctxPurchase, {
            type: 'line',
            data: purchaseData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
