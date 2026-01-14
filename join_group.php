<?php
/* join_group.php */
require_once 'config.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $group_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    // Check if already a member
    $check_query = "SELECT * FROM group_members WHERE group_id = $group_id AND user_id = $user_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) == 0) {
        $insert_query = "INSERT INTO group_members (group_id, user_id) VALUES ($group_id, $user_id)";
        mysqli_query($conn, $insert_query);
    }
    
    header("Location: group_detail.php?id=$group_id");
    exit();
}

header("Location: groups.php");
exit();
?>