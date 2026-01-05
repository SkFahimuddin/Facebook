<?php
/* add_friend.php */
require_once 'config.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $friend_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    // Check if friendship already exists
    $check_query = "SELECT * FROM friends 
                    WHERE (user_id = $user_id AND friend_id = $friend_id)
                    OR (user_id = $friend_id AND friend_id = $user_id)";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) == 0) {
        $insert_query = "INSERT INTO friends (user_id, friend_id, status) 
                        VALUES ($user_id, $friend_id, 'pending')";
        mysqli_query($conn, $insert_query);
    }
    
    header("Location: profile.php?id=$friend_id");
    exit();
}
?>