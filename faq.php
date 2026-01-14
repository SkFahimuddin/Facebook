<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>FAQ - Thefacebook</title>
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
        .faq-item {
            margin-bottom: 15px;
        }
        .question {
            font-weight: bold;
            color: #3B5998;
            margin-bottom: 5px;
        }
        .answer {
            color: #333;
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
        <div class="page-title">Frequently Asked Questions</div>
        
        <div class="content-wrapper">
            <div class="main-title">[ FAQ ]</div>
            
            <div class="section">
                <div class="section-header">General Questions</div>
                <div class="section-content">
                    <div class="faq-item">
                        <div class="question">What is Thefacebook?</div>
                        <div class="answer">
                            Thefacebook is an online directory that connects people through social networks at colleges and universities.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="question">Who can join Thefacebook?</div>
                        <div class="answer">
                            Currently, Thefacebook is only available to students with a valid .edu email address from participating universities.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="question">Is Thefacebook free?</div>
                        <div class="answer">
                            Yes! Thefacebook is completely free to use for all students.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Account & Privacy</div>
                <div class="section-content">
                    <div class="faq-item">
                        <div class="question">How do I register?</div>
                        <div class="answer">
                            Click the "register" button on the homepage and fill out the form with your .edu email address.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="question">Is my information private?</div>
                        <div class="answer">
                            Your information is only visible to other users at your school. You control what information appears on your profile.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="question">How do I delete my account?</div>
                        <div class="answer">
                            Go to My Account settings and select the option to deactivate your account.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Using Thefacebook</div>
                <div class="section-content">
                    <div class="faq-item">
                        <div class="question">How do I add friends?</div>
                        <div class="answer">
                            Search for people using the search function, visit their profile, and click "Add as Friend".
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="question">How do I send messages?</div>
                        <div class="answer">
                            Go to the Messages section and click "Compose New Message" to send a message to any of your friends.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="question">What are groups?</div>
                        <div class="answer">
                            Groups allow you to create communities around shared interests, classes, or activities. You can join existing groups or create your own.
                        </div>
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