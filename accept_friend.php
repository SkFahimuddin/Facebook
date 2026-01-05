<?php
/* accept_friend.php */
require_once 'config.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $request_id = intval($_GET['id']);
    
    $update_query = "UPDATE friends SET status = 'accepted' WHERE id = $request_id";
    mysqli_query($conn, $update_query);
    
    header("Location: home.php");
    exit();
}
?>