<?php
/* reject_friend.php */
require_once 'config.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $request_id = intval($_GET['id']);
    
    $delete_query = "DELETE FROM friends WHERE id = $request_id";
    mysqli_query($conn, $delete_query);
    
    header("Location: home.php");
    exit();
}
?>