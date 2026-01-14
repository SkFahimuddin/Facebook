<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>About Thefacebook</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Verdana, Arial, sans-serif;
            font-size: 11px;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .page-container {
            max-width: 550px;
            margin: 0 auto;
            background-color: white;
            border: 2px solid #3B5998;
        }
        .page-title {
            background-color: #6d84b4;
            color: white;
            padding: 8px 15px;
            font-size: 13px;
            font-weight: bold;
        }
        .content-wrapper {
            padding: 20px;
        }
        .main-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .section {
            background-color: white;
            border: 1px solid #3B5998;
            margin-bottom: 15px;
        }
        .section-header {
            background-color: #6d84b4;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .section-content {
            padding: 15px;
            line-height: 1.6;
            color: #333;
        }
        .person-link {
            color: #3B5998;
            text-decoration: none;
            font-weight: bold;
        }
        .person-link:hover {
            text-decoration: underline;
        }
        .person-item {
            margin-bottom: 8px;
        }
        .home-button {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            background-color: #6d84b4;
            color: white;
            padding: 6px 20px;
            border: 1px solid #29447e;
            text-decoration: none;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }
        .btn:hover {
            background-color: #5975ba;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="page-title">About Thefacebook</div>
        
        <div class="content-wrapper">
            <div class="main-title">[ About ]</div>
            
            <div class="section">
                <div class="section-header">The Project</div>
                <div class="section-content">
                    Thefacebook is an online directory that connects people through social networks at colleges and universities.
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">The People</div>
                <div class="section-content">
                    <div class="person-item">
                        <a href="#" class="person-link">Mark Zuckerberg</a> - Founder, Master and Commander, Enemy of the State.
                    </div>
                    <div class="person-item">
                        <a href="#" class="person-link">Eduardo Saverin</a> - Business Stuff, Corporate Stuff, Brazilian Affairs.
                    </div>
                    <div class="person-item">
                        <a href="#" class="person-link">Andrew McCollum</a> - Graphic Art, General Rockstar.
                    </div>
                    <div style="margin-top: 15px;">
                        <a href="contact.php" class="person-link">Contact us.</a>
                    </div>
                </div>
            </div>
            
            <div class="home-button">
                <a href="<?php echo is_logged_in() ? 'home.php' : 'index.php'; ?>" class="btn">Home</a>
            </div>
        </div>
    </div>
</body>
</html>