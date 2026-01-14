<?php
require_once 'config.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

$current_user = get_logged_in_user();
$success = '';
$error = '';

// Handle create group
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_group'])) {
    $group_name = clean_input($_POST['group_name']);
    $description = clean_input($_POST['description']);
    
    if (empty($group_name)) {
        $error = "Group name is required.";
    } else {
        $insert_group = "INSERT INTO groups (group_name, description, creator_id) 
                        VALUES ('$group_name', '$description', {$current_user['id']})";
        
        if (mysqli_query($conn, $insert_group)) {
            $group_id = mysqli_insert_id($conn);
            
            // Add creator as member
            $add_member = "INSERT INTO group_members (group_id, user_id) 
                          VALUES ($group_id, {$current_user['id']})";
            mysqli_query($conn, $add_member);
            
            $success = "Group created successfully!";
            header("Location: group_detail.php?id=$group_id");
            exit();
        } else {
            $error = "Failed to create group.";
        }
    }
}

// Get user's groups
$my_groups_query = "SELECT g.*, u.first_name, u.last_name,
                    (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count,
                    (SELECT COUNT(*) FROM group_messages WHERE group_id = g.id) as message_count
                    FROM groups g
                    INNER JOIN users u ON g.creator_id = u.id
                    INNER JOIN group_members gm ON g.id = gm.group_id
                    WHERE gm.user_id = {$current_user['id']}
                    ORDER BY g.created_date DESC";
$my_groups_result = mysqli_query($conn, $my_groups_query);

// Get all groups (for browsing)
$all_groups_query = "SELECT g.*, u.first_name, u.last_name,
                     (SELECT COUNT(*) FROM group_members WHERE group_id = g.id) as member_count,
                     (SELECT COUNT(*) FROM group_messages WHERE group_id = g.id) as message_count,
                     (SELECT COUNT(*) FROM group_members WHERE group_id = g.id AND user_id = {$current_user['id']}) as is_member
                     FROM groups g
                     INNER JOIN users u ON g.creator_id = u.id
                     ORDER BY g.created_date DESC
                     LIMIT 20";
$all_groups_result = mysqli_query($conn, $all_groups_query);

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'my_groups';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Groups - thefacebook</title>
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
            padding: 20px;
        }
        .page-header {
            font-size: 16px;
            color: #3B5998;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        .tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ccc;
        }
        .tab {
            padding: 8px 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-bottom: none;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            font-size: 11px;
            font-weight: bold;
        }
        .tab:hover {
            background-color: #e0e0e0;
        }
        .tab.active {
            background-color: white;
            color: #3B5998;
            border-bottom: 2px solid white;
            margin-bottom: -2px;
        }
        .btn {
            background-color: #3B5998;
            color: white;
            padding: 8px 15px;
            border: 1px solid #29447e;
            cursor: pointer;
            font-size: 11px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
        }
        .btn:hover {
            background-color: #2d4373;
        }
        .btn-small {
            padding: 5px 10px;
            font-size: 10px;
        }
        .create-form {
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            display: none;
        }
        .create-form.show {
            display: block;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 6px;
            font-family: Tahoma, Arial, sans-serif;
            font-size: 11px;
            border: 1px solid #ccc;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .group-list {
            display: grid;
            gap: 15px;
        }
        .group-item {
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .group-item:hover {
            background-color: #f0f0f0;
        }
        .group-info {
            flex: 1;
        }
        .group-name {
            font-size: 14px;
            font-weight: bold;
            color: #3B5998;
            margin-bottom: 5px;
        }
        .group-name a {
            color: #3B5998;
            text-decoration: none;
        }
        .group-name a:hover {
            text-decoration: underline;
        }
        .group-meta {
            font-size: 10px;
            color: #666;
            margin-bottom: 8px;
        }
        .group-description {
            color: #333;
            line-height: 1.4;
            margin-top: 5px;
        }
        .group-actions {
            margin-left: 15px;
        }
        .error {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 10px;
            margin-bottom: 15px;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 10px;
            margin-bottom: 15px;
        }
        .no-groups {
            padding: 30px;
            text-align: center;
            color: #666;
            background-color: #f7f7f7;
            border: 1px solid #ccc;
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
    <script>
        function toggleCreateForm() {
            var form = document.getElementById('createForm');
            form.classList.toggle('show');
        }
    </script>
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
        <div class="page-header">
            Groups
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <button onclick="toggleCreateForm()" class="btn">+ Create New Group</button>
        
        <div id="createForm" class="create-form">
            <h3 style="color: #3B5998; margin-bottom: 15px;">Create New Group</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Group Name:</label>
                    <input type="text" name="group_name" required placeholder="Enter group name...">
                </div>
                
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" placeholder="What is this group about?"></textarea>
                </div>
                
                <div>
                    <input type="submit" name="create_group" value="Create Group" class="btn">
                    <button type="button" onclick="toggleCreateForm()" class="btn" style="background-color: #999;">Cancel</button>
                </div>
            </form>
        </div>
        
        <div class="tabs">
            <a href="groups.php?tab=my_groups" class="tab <?php echo $active_tab == 'my_groups' ? 'active' : ''; ?>">
                My Groups
            </a>
            <a href="groups.php?tab=browse" class="tab <?php echo $active_tab == 'browse' ? 'active' : ''; ?>">
                Browse All Groups
            </a>
        </div>
        
        <?php if ($active_tab == 'my_groups'): ?>
            <div class="group-list">
                <?php if (mysqli_num_rows($my_groups_result) > 0): ?>
                    <?php while ($group = mysqli_fetch_assoc($my_groups_result)): ?>
                        <div class="group-item">
                            <div class="group-info">
                                <div class="group-name">
                                    <a href="group_detail.php?id=<?php echo $group['id']; ?>">
                                        <?php echo htmlspecialchars($group['group_name']); ?>
                                    </a>
                                </div>
                                <div class="group-meta">
                                    Created by <?php echo htmlspecialchars($group['first_name'] . ' ' . $group['last_name']); ?> • 
                                    <?php echo $group['member_count']; ?> member<?php echo $group['member_count'] != 1 ? 's' : ''; ?> • 
                                    <?php echo $group['message_count']; ?> message<?php echo $group['message_count'] != 1 ? 's' : ''; ?>
                                </div>
                                <?php if (!empty($group['description'])): ?>
                                <div class="group-description">
                                    <?php echo htmlspecialchars(substr($group['description'], 0, 150)); ?>
                                    <?php echo strlen($group['description']) > 150 ? '...' : ''; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="group-actions">
                                <a href="group_detail.php?id=<?php echo $group['id']; ?>" class="btn btn-small">View Group</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-groups">
                        <strong>You haven't joined any groups yet</strong><br><br>
                        Create a new group or browse available groups to join!
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="group-list">
                <?php if (mysqli_num_rows($all_groups_result) > 0): ?>
                    <?php while ($group = mysqli_fetch_assoc($all_groups_result)): ?>
                        <div class="group-item">
                            <div class="group-info">
                                <div class="group-name">
                                    <a href="group_detail.php?id=<?php echo $group['id']; ?>">
                                        <?php echo htmlspecialchars($group['group_name']); ?>
                                    </a>
                                </div>
                                <div class="group-meta">
                                    Created by <?php echo htmlspecialchars($group['first_name'] . ' ' . $group['last_name']); ?> • 
                                    <?php echo $group['member_count']; ?> member<?php echo $group['member_count'] != 1 ? 's' : ''; ?> • 
                                    <?php echo $group['message_count']; ?> message<?php echo $group['message_count'] != 1 ? 's' : ''; ?>
                                </div>
                                <?php if (!empty($group['description'])): ?>
                                <div class="group-description">
                                    <?php echo htmlspecialchars(substr($group['description'], 0, 150)); ?>
                                    <?php echo strlen($group['description']) > 150 ? '...' : ''; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="group-actions">
                                <?php if ($group['is_member'] > 0): ?>
                                    <a href="group_detail.php?id=<?php echo $group['id']; ?>" class="btn btn-small">View Group</a>
                                <?php else: ?>
                                    <a href="join_group.php?id=<?php echo $group['id']; ?>" class="btn btn-small">Join Group</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-groups">
                        <strong>No groups available</strong><br><br>
                        Be the first to create a group!
                    </div>
                <?php endif; ?>
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
</body>
</html>