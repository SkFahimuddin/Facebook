<?php
require_once 'config.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

$current_user = get_logged_in_user();
$search_results = [];

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search_term = clean_input($_GET['q']);
    
    $search_query = "SELECT * FROM users 
                     WHERE (first_name LIKE '%$search_term%' 
                     OR last_name LIKE '%$search_term%'
                     OR email LIKE '%$search_term%')
                     AND id != {$current_user['id']}
                     LIMIT 50";
    
    $search_result = mysqli_query($conn, $search_query);
    
    while ($user = mysqli_fetch_assoc($search_result)) {
        $search_results[] = $user;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search - TheFacebook</title>
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
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
        }
        .search-box {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ccc;
        }
        .btn {
            background-color: #3B5998;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
        }
        .result {
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            background-color: #f7f7f7;
        }
        .result-name {
            font-size: 16px;
            font-weight: bold;
        }
        .result-name a {
            color: #3B5998;
            text-decoration: none;
        }
        .result-name a:hover {
            text-decoration: underline;
        }
        .result-info {
            color: #666;
            margin-top: 5px;
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
        <h1 style="color: #3B5998;">Search for People</h1>
        
        <div class="search-box">
            <form method="GET" action="">
                <input type="text" name="q" placeholder="Search by name or email..." 
                       value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                <input type="submit" value="Search" class="btn">
            </form>
        </div>
        
        <?php if (isset($_GET['q'])): ?>
            <h2 style="color: #3B5998;">Search Results</h2>
            
            <?php if (count($search_results) > 0): ?>
                <?php foreach ($search_results as $user): ?>
                    <div class="result">
                        <div class="result-name">
                            <a href="profile.php?id=<?php echo $user['id']; ?>">
                                <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                            </a>
                        </div>
                        <div class="result-info">
                            <?php echo htmlspecialchars($user['email']); ?>
                        </div>
                        <?php if ($user['relationship_status']): ?>
                        <div class="result-info">
                            <?php echo htmlspecialchars($user['relationship_status']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No results found for "<?php echo htmlspecialchars($_GET['q']); ?>"</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>