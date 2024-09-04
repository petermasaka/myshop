<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$discussion_id = isset($_GET['discussion_id']) ? intval($_GET['discussion_id']) : 0;

// Fetch discussion room details
$sql = "SELECT * FROM discussionrooms WHERE discussion_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $discussion_id);
$stmt->execute();
$discussion_room = $stmt->get_result()->fetch_assoc();

if (!$discussion_room) {
    echo "Discussion room not found.";
    exit();
}

// Fetch messages in the discussion room
$sql = "SELECT m.message, m.created_at, u.username 
        FROM discussionmessages m
        JOIN users u ON m.sender_id = u.user_id
        WHERE m.discussion_id = ?
        ORDER BY m.created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $discussion_id);
$stmt->execute();
$messages = $stmt->get_result();

// Handle new message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_message'])) {
    $message = $_POST['message'];
    $sender_id = $_SESSION['user_id'];

    $sql = "INSERT INTO discussionmessages (discussion_id, sender_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $discussion_id, $sender_id, $message);

    if ($stmt->execute()) {
        header("Location: view_discussion.php?discussion_id=" . $discussion_id);
        exit();
    } else {
        echo "Error sending message.";
    }
}

include 'dashboard_header.php';
?>

<div class="discussion-room-view-container">
    <h2><?php echo htmlspecialchars($discussion_room['topic']); ?></h2>
    <div class="messages">
        <?php while ($msg = $messages->fetch_assoc()): ?>
            <div class="message-item">
                <p><strong><?php echo htmlspecialchars($msg['username']); ?>:</strong> <?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                <p><em><?php echo htmlspecialchars($msg['created_at']); ?></em></p>
            </div>
        <?php endwhile; ?>
    </div>

    <form action="view_discussion.php?discussion_id=<?php echo $discussion_id; ?>" method="POST">
        <textarea name="message" placeholder="Type your message here..." required></textarea>
        <button type="submit" name="send_message">Send</button>
    </form>
</div>

<?php include 'dashboard_footer.php'; ?>

<style>
    .discussion-room-view-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .discussion-room-view-container h2 {
        color: #4CAF50;
        margin-bottom: 20px;
    }

    .discussion-room-view-container .messages {
        margin-bottom: 20px;
        max-height: 400px;
        overflow-y: auto;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
    }

    .discussion-room-view-container .message-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .discussion-room-view-container .message-item p {
        margin: 5px 0;
    }

    .discussion-room-view-container textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        margin-bottom: 10px;
        min-height: 100px;
        resize: vertical;
    }

    .discussion-room-view-container button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .discussion-room-view-container button:hover {
        background-color: #45a049;
    }
</style>
