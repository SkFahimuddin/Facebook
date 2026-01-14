<?php
require_once 'config.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

$current_user = get_logged_in_user();

if (!isset($_GET['id'])) {
    header("Location: groups.php");
    exit();
}

$group_id = intval($_GET['id']);

// Get group details
$group_query = "SELECT g.*, u.first_name, u.last_name,
                (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count
                FROM groups g
                INNER JOIN users u ON g.creator_id = u.id
                WHERE g.id = $group_id";
$group_result = mysqli_query($conn, $group_query);

if (mysqli_num_rows($group_result) == 0) {
    die("Group not found.");
}

$group = mysqli_fetch_assoc($group_result);

// Check if user is a member
$membership_query = "SELECT * FROM group_members WHERE group_id = $group_id AND user_id = {$current_user['id']}";
$membership_result = mysqli_query($conn, $membership_query);
$is_member = mysqli_num_rows($membership_result) > 0;

$is_creator = ($group['creator_id'] == $current_user['id']);

// Handle send message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message']) && $is_member) {
    $message_content = clean_input($_POST['message_content']);
    
    if (!empty($message_content)) {
        $insert_message = "INSERT INTO group_messages (group_id, sender_id, message_content) 
                          VALUES ($group_id, {$current_user['id']}, '$message_content')";
        
        if (mysqli_query($conn, $insert_message)) {
            header("Location: group_detail.php?id=$group_id");
            exit();
        }
    }
}

// Get group messages
$messages_query = "SELECT gm.*, u.first_name, u.last_name, u.profile_pic
                   FROM group_messages gm
                   INNER JOIN users u ON gm.sender_id = u.id
                   WHERE gm.group_id = $group_id
                   ORDER BY gm.sent_date DESC
                   LIMIT 50";
$messages_result = mysqli_query($conn, $messages_query);

// Get group members
$members_query = "SELECT u.id, u.first_name, u.last_name, u.profile_pic, gm.joined_date
                  FROM users u
                  INNER JOIN group_members gm ON u.id = gm.user_id
                  WHERE gm.group_id = $group_id
                  ORDER BY gm.joined_date ASC";
$members_result = mysqli_query($conn, $members_query);

// Get friends not in group (for inviting)
$available_friends_query = "SELECT u.id, u.first_name, u.last_name FROM users u 
                            INNER JOIN friends f ON (f.friend_id = u.id OR f.user_id = u.id)
                            WHERE (f.user_id = {$current_user['id']} OR f.friend_id = {$current_user['id']})
                            AND f.status = 'accepted'
                            AND u.id != {$current_user['id']}
                            AND u.id NOT IN (SELECT user_id FROM group_members WHERE group_id = $group_id)
                            ORDER BY u.first_name, u.last_name";
$available_friends_result = mysqli_query($conn, $available_friends_query);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($group['group_name']); ?> - thefacebook</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Tahoma, Verdana, Arial, sans-serif;
            font-size: 11px;
            background-color: #3B5998;
        }
        .header {
            background-color: #3B5998;
            padding: 8px 15px;
            color: white;
            border-bottom: 1px solid #29447e;
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 22px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        .header-nav a {
            color: white;
            text-decoration: none;
            margin: 0 12px;
            font-size: 11px;
        }
        .header-nav a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            min-height: calc(100vh - 40px);
            display: flex;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .sidebar {
            width: 280px;
            background-color: #d8dfea;
            padding: 15px;
            border-left: 1px solid #ccc;
        }
        .page-header {
            font-size: 16px;
            color: #3B5998;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .group-info {
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
        }
        .group-meta {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        .group-description {
            margin-top: 10px;
            line-height: 1.5;
            color: #333;
        }
        .btn {
            background-color: #3B5998;
            color: white;
            padding: 6px 12px;
            border: 1px solid #29447e;
            cursor: pointer;
            font-size: 11px;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
        }
        .btn:hover {
            background-color: #2d4373;
        }
        .btn-danger {
            background-color: #d9534f;
            border: 1px solid #c9302c;
        }
        .btn-danger:hover {
            background-color: #c9302c;
        }
        .message-form {
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
        }
        .message-form textarea {
            width: 100%;
            padding: 8px;
            font-family: Tahoma, Arial, sans-serif;
            font-size: 11px;
            border: 1px solid #ccc;
            resize: vertical;
            min-height: 80px;
        }
        .messages-section {
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            padding: 15px;
        }
        .section-header {
            background-color: #6d84b4;
            color: white;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11px;
            margin: -15px -15px 15px -15px;
        }
        .message-item {
            background-color: white;
            border: 1px solid #ccc;
            padding: 12px;
            margin-bottom: 10px;
        }
        .message-header {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
        }
        .message-pic {
            width: 40px;
            height: 40px;
            border: 1px solid #ccc;
            object-fit: cover;
        }
        .message-info {
            flex: 1;
        }
        .message-author {
            font-weight: bold;
            color: #3B5998;
        }
        .message-author a {
            color: #3B5998;
            text-decoration: none;
        }
        .message-author a:hover {
            text-decoration: underline;
        }
        .message-date {
            font-size: 10px;
            color: #999;
        }
        .message-content {
            margin-top: 5px;
            line-height: 1.5;
            color: #333;
            white-space: pre-wrap;
        }
        .sidebar-section {
            background-color: white;
            border: 1px solid #999;
            margin-bottom: 15px;
        }
        .sidebar-header {
            background-color: #6d84b4;
            color: white;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11px;
        }
        .sidebar-content {
            padding: 10px;
        }
        .member-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .member-item:last-child {
            border-bottom: none;
        }
        .member-pic {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            object-fit: cover;
        }
        .member-name {
            color: #3B5998;
            text-decoration: none;
            font-size: 11px;
        }
        .member-name:hover {
            text-decoration: underline;
        }
        .no-messages {
            padding: 20px;
            text-align: center;
            color: #666;
        }
        .invite-form {
            margin-top: 10px;
        }
        .invite-form select {
            width: 100%;
            padding: 5px;
            font-size: 11px;
            border: 1px solid #ccc;
            margin-bottom: 8px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .footer a {
            color: #3B5998;
            text-decoration: none;
            margin: 0 8px;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <a href="home.php" class="logo">thefacebook</a>
            <div class="header-nav">
                <a href="profile.php?id=<?php echo $current_user['id']; ?>">My Profile</a>
                <a href="search.php">My Friends</a>
                <a href="search.php">Search</a>
                <a href="messages.php">Messages</a>
                <a href="groups.php">Groups</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="main-content">
            <div class="page-header">
                <?php echo htmlspecialchars($group['group_name']); ?>
            </div>
            
            <div class="group-info">
                <div class="group-meta">
                    Created by 
                    <a href="profile.php?id=<?php echo $group['creator_id']; ?>" style="color: #3B5998; text-decoration: none;">
                        <?php echo htmlspecialchars($group['first_name'] . ' ' . $group['last_name']); ?>
                    </a>
                    on <?php echo date('F j, Y', strtotime($group['created_date'])); ?> • 
                    <?php echo $group['member_count']; ?> member<?php echo $group['member_count'] != 1 ? 's' : ''; ?>
                </div>
                <?php if (!empty($group['description'])): ?>
                <div class="group-description">
                    <?php echo nl2br(htmlspecialchars($group['description'])); ?>
                </div>
                <?php endif; ?>
                <div style="margin-top: 10px;">
                    <a href="groups.php" class="btn">← Back to Groups</a>
                    <?php if (!$is_member): ?>
                        <a href="join_group.php?id=<?php echo $group_id; ?>" class="btn">Join Group</a>
                    <?php elseif (!$is_creator): ?>
                        <a href="leave_group.php?id=<?php echo $group_id; ?>" class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to leave this group?')">Leave Group</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($is_member): ?>
            <div class="message-form">
                <div class="section-header">Post Message to Group</div>
                <form method="POST" action="">
                    <textarea name="message_content" placeholder="Write a message to the group..." required></textarea>
                    <input type="submit" name="send_message" value="Send Message" class="btn" style="margin-top: 10px;">
                </form>
            </div>
            
            <div class="messages-section">
                <div class="section-header">Group Messages</div>
                
                <?php if (mysqli_num_rows($messages_result) > 0): ?>
                    <?php while ($message = mysqli_fetch_assoc($messages_result)): ?>
                        <div class="message-item">
                            <div class="message-header">
                                <img src="uploads/<?php echo htmlspecialchars($message['profile_pic']); ?>" 
                                     alt="Profile" 
                                     class="message-pic"
                                     onerror="this.src='https://via.placeholder.com/40x40/cccccc/666666?text=?'">
                                <div class="message-info">
                                    <div class="message-author">
                                        <a href="profile.php?id=<?php echo $message['sender_id']; ?>">
                                            <?php echo htmlspecialchars($message['first_name'] . ' ' . $message['last_name']); ?>
                                        </a>
                                    </div>
                                    <div class="message-date">
                                        <?php echo date('F j, Y \a\t g:i a', strtotime($message['sent_date'])); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="message-content">
                                <?php echo nl2br(htmlspecialchars($message['message_content'])); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-messages">
                        No messages yet. Be the first to post!
                    </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div style="padding: 30px; text-align: center; background-color: #f7f7f7; border: 1px solid #ccc;">
                <strong>You must be a member to view and post messages</strong><br><br>
                <a href="join_group.php?id=<?php echo $group_id; ?>" class="btn">Join This Group</a>
            </div>
            <?php endif; ?>
            
            <div class="footer">
                <a href="#">about</a>
                <a href="#">contact</a>
                <a href="#">faq</a>
                <a href="#">terms</a>
                <a href="#">privacy</a>
                <br>
                <div style="margin-top: 8px;">a Sk Fahimuddin production</div>
                <div style="margin-top: 3px;">Thefacebook © 2004</div>
            </div>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-section">
                <div class="sidebar-header">Group Members (<?php echo $group['member_count']; ?>)</div>
                <div class="sidebar-content">
                    <?php while ($member = mysqli_fetch_assoc($members_result)): ?>
                        <div class="member-item">
                            <img src="uploads/<?php echo htmlspecialchars($member['profile_pic']); ?>" 
                                 alt="<?php echo htmlspecialchars($member['first_name']); ?>" 
                                 class="member-pic"
                                 onerror="this.src='https://via.placeholder.com/30x30/cccccc/666666?text=?';">
                            <a href="profile.php?id=<?php echo $member['id']; ?>" class="member-name">
                                <?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?>
                                <?php if ($member['id'] == $group['creator_id']): ?>
                                    <span style="color: #666; font-size: 9px;">(Creator)</span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <?php if ($is_member && mysqli_num_rows($available_friends_result) > 0): ?>
            <div class="sidebar-section">
                <div class="sidebar-header">Invite Friends</div>
                <div class="sidebar-content">
                    <form method="GET" action="invite_to_group.php" class="invite-form">
                        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                        <select name="user_id" required>
                            <option value="">Select a friend...</option>
                            <?php while ($friend = mysqli_fetch_assoc($available_friends_result)): ?>
                                <option value="<?php echo $friend['id']; ?>">
                                    <?php echo htmlspecialchars($friend['first_name'] . ' ' . $friend['last_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <input type="submit" value="Send Invite" class="btn" style="width: 100%; padding: 5px;">
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>