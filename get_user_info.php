<?php
// Start session
session_start();


// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Include database configuration
    include 'config.php';  

    // Get user data from the database
    $userId = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT name, email, phone FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userName, $userEmail, $userPhone);
        $stmt->fetch();

        // Return user data as JSON
        $response = [
            'status' => 'success',
            'name' => $userName,
            'email' => $userEmail,
            'phone' => $userPhone
        ];
    } else {
        $response = ['status' => 'error', 'message' => 'User not found'];
    }

    // Close the database connection
    $stmt->close();
    $db->close();
} else {
    // User is not logged in
    $response = ['status' => 'error', 'message' => 'User not logged in'];
}

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
