<?php
/* leave_group.php */
require_once 'config.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $group_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    // Check if user is the creator
    $check_creator = "SELECT creator_id FROM groups WHERE id = $group_id";
    $creator_result = mysqli_query($conn, $check_creator);
    $group = mysqli_fetch_assoc($creator_result);
    
    if ($group && $group['creator_id'] != $user_id) {
        // Not the creator, can leave
        $delete_query = "DELETE FROM group_members WHERE group_id = $group_id AND user_id = $user_id";
        mysqli_query($conn, $delete_query);
    }
    
    header("Location: groups.php");
    exit();
}

header("Location: groups.php");
exit();
?>