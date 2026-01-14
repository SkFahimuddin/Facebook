<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Thefacebook</title>
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
        .contact-method {
            margin-bottom: 12px;
        }
        .contact-label {
            font-weight: bold;
            color: #3B5998;
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
        a {
            color: #3B5998;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="page-title">Contact Thefacebook</div>
        
        <div class="content-wrapper">
            <div class="main-title">[ Contact ]</div>
            
            <div class="section">
                <div class="section-header">Get In Touch</div>
                <div class="section-content">
                    <div class="contact-method">
                        <span class="contact-label">General Inquiries:</span><br>
                        info@thefacebook.com
                    </div>
                    <div class="contact-method">
                        <span class="contact-label">Technical Support:</span><br>
                        support@thefacebook.com
                    </div>
                    <div class="contact-method">
                        <span class="contact-label">Business Development:</span><br>
                        business@thefacebook.com
                    </div>
                    <div class="contact-method">
                        <span class="contact-label">Press Inquiries:</span><br>
                        press@thefacebook.com
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Office Address</div>
                <div class="section-content">
                    Thefacebook<br>
                    Harvard University<br>
                    Cambridge, MA 02138<br>
                    United States
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Feedback</div>
                <div class="section-content">
                    We value your feedback and suggestions. If you have ideas on how to improve Thefacebook or encounter any issues, please don't hesitate to reach out to us.
                </div>
            </div>
            
            <div class="home-button">
                <a href="<?php echo is_logged_in() ? 'home.php' : 'index.php'; ?>" class="btn">Home</a>
            </div>
        </div>
    </div>
</body>
</html>