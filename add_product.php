<?php
session_start();
require_once 'config.php';

// Check if user is a retailer
if ($_SESSION['role'] !== 'retailer') {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    $sql = "INSERT INTO products (retailer_id, product_name, description, price, stock, category) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssis", $user_id, $product_name, $description, $price, $stock, $category);

    if ($stmt->execute()) {
        echo "<p>Product added successfully.</p>";
    } else {
        echo "<p>Error adding product.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - MyShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 700px;
            width: 100%;
            margin: 20px;
            text-align: center;
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #4CAF50;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 700;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        input[type="text"], input[type="number"], select, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            background-color: #f9f9f9;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus, input[type="number"]:focus, select:focus, textarea:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.2);
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 14px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #45a049;
            transform: scale(1.02);
        }

        p {
            color: #333;
            font-size: 18px;
            margin-top: 20px;
        }

        .success-message, .error-message {
            font-size: 18px;
            margin-top: 20px;
        }

        .success-message {
            color: #4CAF50;
        }

        .error-message {
            color: #f44336;
        }

        .form-footer {
            margin-top: 20px;
        }

        .form-footer a {
            color: #4CAF50;
            text-decoration: none;
            font-size: 16px;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add Product</h1>
        <form method="POST" action="">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="price">Price:</label>
            <input type="text" name="price" id="price" required>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" required>

            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="farm">Farm</option>
                <option value="industrial">Industrial</option>
                <option value="other">Other</option>
            </select>

            <button type="submit">Add Product</button>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($stmt->execute()) {
                    echo '<p class="success-message">Product added successfully.</p>';
                } else {
                    echo '<p class="error-message">Error adding product. Please try again.</p>';
                }
            }
            ?>
        </form>

        <div class="form-footer">
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
