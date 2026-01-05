<?php
require_once 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

$current_user = get_logged_in_user();

// Get user's friends
$friends_query = "SELECT u.* FROM users u 
                  INNER JOIN friends f ON (f.friend_id = u.id OR f.user_id = u.id)
                  WHERE (f.user_id = {$current_user['id']} OR f.friend_id = {$current_user['id']})
                  AND f.status = 'accepted'
                  AND u.id != {$current_user['id']}";
$friends_result = mysqli_query($conn, $friends_query);

// Get friend requests
$requests_query = "SELECT u.*, f.id as request_id FROM users u 
                   INNER JOIN friends f ON f.user_id = u.id
                   WHERE f.friend_id = {$current_user['id']} AND f.status = 'pending'";
$requests_result = mysqli_query($conn, $requests_query);

// Get recent wall posts from friends
$posts_query = "SELECT w.*, u.first_name, u.last_name, o.first_name as owner_first, o.last_name as owner_last
                FROM wall_posts w
                INNER JOIN users u ON w.user_id = u.id
                INNER JOIN users o ON w.wall_owner_id = o.id
                WHERE w.wall_owner_id IN (
                    SELECT friend_id FROM friends WHERE user_id = {$current_user['id']} AND status = 'accepted'
                    UNION
                    SELECT user_id FROM friends WHERE friend_id = {$current_user['id']} AND status = 'accepted'
                )
                OR w.wall_owner_id = {$current_user['id']}
                ORDER BY w.post_date DESC
                LIMIT 20";
$posts_result = mysqli_query($conn, $posts_query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>TheFacebook - Home</title>
    <style>
        body {
            font-family: Tahoma, Arial, sans-serif;
            background-color: #3B5998;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #3B5998;
            color: white;
            padding: 10px 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .nav {
            background-color: #6d84b4;
            padding: 10px 20px;
            color: white;
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }
        .nav a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
        }
        .welcome {
            font-size: 18px;
            margin-bottom: 20px;
            color: #3B5998;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f7f7f7;
        }
        h2 {
            color: #3B5998;
            font-size: 16px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .friend-list {
            list-style: none;
            padding: 0;
        }
        .friend-list li {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .friend-list a {
            color: #3B5998;
            text-decoration: none;
        }
        .friend-list a:hover {
            text-decoration: underline;
        }
        .post {
            margin: 10px 0;
            padding: 10px;
            background-color: white;
            border: 1px solid #ddd;
        }
        .post-author {
            font-weight: bold;
            color: #3B5998;
        }
        .post-date {
            font-size: 11px;
            color: #666;
        }
        .post-content {
            margin: 10px 0;
        }
        .btn {
            background-color: #3B5998;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 2px;
        }
        .btn:hover {
            background-color: #2d4373;
        }
    </style>
</head>
<body>
    <div class="header">TheFacebook</div>
    <div class="nav">
        <a href="home.php">Home</a>
        <a href="profile.php?id=<?php echo $current_user['id']; ?>">My Profile</a>
        <a href="search.php">Search</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="container">
        <div class="welcome">
            Welcome, <?php echo htmlspecialchars($current_user['first_name'] . ' ' . $current_user['last_name']); ?>!
        </div>
        
        <?php if (mysqli_num_rows($requests_result) > 0): ?>
        <div class="section">
            <h2>Friend Requests</h2>
            <?php while ($request = mysqli_fetch_assoc($requests_result)): ?>
                <div>
                    <a href="profile.php?id=<?php echo $request['id']; ?>">
                        <?php echo htmlspecialchars($request['first_name'] . ' ' . $request['last_name']); ?>
                    </a>
                    <a href="accept_friend.php?id=<?php echo $request['request_id']; ?>" class="btn">Accept</a>
                    <a href="reject_friend.php?id=<?php echo $request['request_id']; ?>" class="btn">Reject</a>
                </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
        
        <div class="section">
            <h2>Your Friends (<?php echo mysqli_num_rows($friends_result); ?>)</h2>
            <ul class="friend-list">
                <?php 
                if (mysqli_num_rows($friends_result) > 0) {
                    while ($friend = mysqli_fetch_assoc($friends_result)): 
                ?>
                    <li>
                        <a href="profile.php?id=<?php echo $friend['id']; ?>">
                            <?php echo htmlspecialchars($friend['first_name'] . ' ' . $friend['last_name']); ?>
                        </a>
                    </li>
                <?php 
                    endwhile;
                } else {
                    echo "<li>You have no friends yet. Search for people to add!</li>";
                }
                ?>
            </ul>
        </div>
        
        <div class="section">
            <h2>Recent Updates</h2>
            <?php 
            if (mysqli_num_rows($posts_result) > 0) {
                while ($post = mysqli_fetch_assoc($posts_result)): 
            ?>
                <div class="post">
                    <div class="post-author">
                        <a href="profile.php?id=<?php echo $post['user_id']; ?>">
                            <?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?>
                        </a>
                        → 
                        <a href="profile.php?id=<?php echo $post['wall_owner_id']; ?>">
                            <?php echo htmlspecialchars($post['owner_first'] . ' ' . $post['owner_last']); ?>
                        </a>
                    </div>
                    <div class="post-date"><?php echo date('F j, Y g:i a', strtotime($post['post_date'])); ?></div>
                    <div class="post-content"><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></div>
                </div>
            <?php 
                endwhile;
            } else {
                echo "<p>No recent updates. Add some friends to see their posts!</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>