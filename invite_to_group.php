<?php
/* invite_to_group.php */
require_once 'config.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['group_id']) && isset($_GET['user_id'])) {
    $group_id = intval($_GET['group_id']);
    $invited_user_id = intval($_GET['user_id']);
    $current_user_id = $_SESSION['user_id'];
    
    // Verify current user is a member of the group
    $check_membership = "SELECT * FROM group_members WHERE group_id = $group_id AND user_id = $current_user_id";
    $membership_result = mysqli_query($conn, $check_membership);
    
    if (mysqli_num_rows($membership_result) > 0) {
        // Check if invited user is already a member
        $check_invited = "SELECT * FROM group_members WHERE group_id = $group_id AND user_id = $invited_user_id";
        $invited_result = mysqli_query($conn, $check_invited);
        
        if (mysqli_num_rows($invited_result) == 0) {
            // Add them to the group
            $insert_query = "INSERT INTO group_members (group_id, user_id) VALUES ($group_id, $invited_user_id)";
            mysqli_query($conn, $insert_query);
            
            // In a real app, you might send a notification or message here
        }
    }
    
    header("Location: group_detail.php?id=$group_id");
    exit();
}

header("Location: groups.php");
exit();
?>