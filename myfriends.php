<?php
require_once 'config.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

$current_user = get_logged_in_user();

// Check if viewing someone else's friends or own friends
$user_id = isset($_GET['id']) ? intval($_GET['id']) : $current_user['id'];

// Get user info
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);

if (mysqli_num_rows($user_result) == 0) {
    die("User not found.");
}

$profile_user = mysqli_fetch_assoc($user_result);
$is_own_profile = ($user_id == $current_user['id']);

// Get all friends with their info
$friends_query = "SELECT u.id, u.first_name, u.last_name, u.profile_pic, u.email, 
                  u.relationship_status, u.interests, f.requested_date
                  FROM users u 
                  INNER JOIN friends f ON (f.friend_id = u.id OR f.user_id = u.id)
                  WHERE (f.user_id = $user_id OR f.friend_id = $user_id)
                  AND f.status = 'accepted'
                  AND u.id != $user_id
                  ORDER BY u.first_name, u.last_name";
$friends_result = mysqli_query($conn, $friends_query);

$total_friends = mysqli_num_rows($friends_result);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($profile_user['first_name']); ?>'s Friends - thefacebook</title>
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
            margin-bottom: 10px;
        }
        .page-subheader {
            color: #666;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        .stats-bar {
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            padding: 12px 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stats-info {
            font-size: 11px;
            color: #333;
        }
        .stats-info strong {
            color: #3B5998;
            font-size: 14px;
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
        }
        .btn:hover {
            background-color: #2d4373;
        }
        .friends-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .friend-card {
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
            transition: background-color 0.2s;
        }
        .friend-card:hover {
            background-color: #e8f0fe;
        }
        .friend-card-pic {
            width: 100px;
            height: 100px;
            border: 1px solid #ccc;
            object-fit: cover;
            margin: 0 auto 10px auto;
            display: block;
        }
        .friend-card-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .friend-card-name a {
            color: #3B5998;
            text-decoration: none;
        }
        .friend-card-name a:hover {
            text-decoration: underline;
        }
        .friend-card-info {
            font-size: 10px;
            color: #666;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        .friend-card-actions {
            margin-top: 8px;
        }
        .btn-small {
            padding: 4px 8px;
            font-size: 10px;
            margin: 2px;
        }
        .no-friends {
            padding: 40px;
            text-align: center;
            color: #666;
            background-color: #f7f7f7;
            border: 1px solid #ccc;
        }
        .no-friends strong {
            font-size: 13px;
            color: #333;
        }
        .back-link {
            display: inline-block;
            color: #3B5998;
            text-decoration: none;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .back-link::before {
            content: "← ";
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
        .filter-bar {
            background-color: #e8f0fe;
            border: 1px solid #b3d4fc;
            padding: 10px 15px;
            margin-bottom: 20px;
        }
        .filter-bar input[type="text"] {
            padding: 5px 8px;
            border: 1px solid #ccc;
            font-size: 11px;
            width: 300px;
            font-family: Tahoma, Arial, sans-serif;
        }
        .filter-bar .btn {
            padding: 5px 12px;
            margin-left: 5px;
        }
    </style>
    <script>
        function filterFriends() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const friendCards = document.querySelectorAll('.friend-card');
            let visibleCount = 0;
            
            friendCards.forEach(card => {
                const name = card.querySelector('.friend-card-name').textContent.toLowerCase();
                const info = card.querySelector('.friend-card-info').textContent.toLowerCase();
                
                if (name.includes(searchText) || info.includes(searchText)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update count
            document.getElementById('visibleCount').textContent = visibleCount;
        }
        
        function clearFilter() {
            document.getElementById('searchInput').value = '';
            filterFriends();
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <a href="home.php" class="logo">thefacebook</a>
            <div class="header-nav">
                <a href="home.php">Home</a>
                <a href="profile.php?id=<?php echo $current_user['id']; ?>">My Profile</a>
                <a href="myfriends.php">My Friends</a>
                <a href="search.php">Search</a>
                <a href="messages.php">Messages</a>
                <a href="groups.php">Groups</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <a href="profile.php?id=<?php echo $user_id; ?>" class="back-link">
            Back to <?php echo $is_own_profile ? 'My' : htmlspecialchars($profile_user['first_name']) . "'s"; ?> Profile
        </a>
        
        <div class="page-header">
            <?php echo $is_own_profile ? 'My Friends' : htmlspecialchars($profile_user['first_name'] . ' ' . $profile_user['last_name']) . "'s Friends"; ?>
        </div>
        
        <div class="page-subheader">
            View all friends and their information
        </div>
        
        <div class="stats-bar">
            <div class="stats-info">
                <strong id="visibleCount"><?php echo $total_friends; ?></strong> 
                <?php echo $total_friends == 1 ? 'Friend' : 'Friends'; ?>
            </div>
            <?php if ($is_own_profile): ?>
            <div>
                <a href="search.php" class="btn">Find More Friends</a>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if ($total_friends > 0): ?>
        <div class="filter-bar">
            <input type="text" id="searchInput" placeholder="Search friends by name, email, or interests..." onkeyup="filterFriends()">
            <button onclick="clearFilter()" class="btn">Clear</button>
        </div>
        
        <div class="friends-grid">
            <?php while ($friend = mysqli_fetch_assoc($friends_result)): ?>
                <div class="friend-card">
                    <a href="profile.php?id=<?php echo $friend['id']; ?>">
                        <img src="uploads/<?php echo htmlspecialchars($friend['profile_pic']); ?>" 
                             alt="<?php echo htmlspecialchars($friend['first_name']); ?>" 
                             class="friend-card-pic"
                             onerror="this.src='https://via.placeholder.com/100x100/cccccc/666666?text=No+Photo'">
                    </a>
                    <div class="friend-card-name">
                        <a href="profile.php?id=<?php echo $friend['id']; ?>">
                            <?php echo htmlspecialchars($friend['first_name'] . ' ' . $friend['last_name']); ?>
                        </a>
                    </div>
                    <div class="friend-card-info">
                        <?php if (!empty($friend['relationship_status'])): ?>
                            <?php echo htmlspecialchars($friend['relationship_status']); ?><br>
                        <?php endif; ?>
                        <?php if (!empty($friend['interests'])): ?>
                            <?php echo htmlspecialchars(substr($friend['interests'], 0, 50)); ?>
                            <?php echo strlen($friend['interests']) > 50 ? '...' : ''; ?>
                        <?php endif; ?>
                    </div>
                    <div class="friend-card-actions">
                        <a href="profile.php?id=<?php echo $friend['id']; ?>" class="btn btn-small">View Profile</a>
                        <a href="compose_message.php?to=<?php echo $friend['id']; ?>" class="btn btn-small">Message</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <?php else: ?>
        <div class="no-friends">
            <strong>
                <?php if ($is_own_profile): ?>
                    You don't have any friends yet
                <?php else: ?>
                    <?php echo htmlspecialchars($profile_user['first_name']); ?> doesn't have any friends yet
                <?php endif; ?>
            </strong>
            <br><br>
            <?php if ($is_own_profile): ?>
                Start connecting with people! 
                <a href="search.php" style="color: #3B5998; text-decoration: none; font-weight: bold;">Search for friends</a>
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