<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Privacy Policy - Thefacebook</title>
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
        .privacy-item {
            margin-bottom: 12px;
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
        .effective-date {
            text-align: center;
            color: #666;
            font-size: 10px;
            margin-bottom: 15px;
        }
        ul {
            margin-left: 20px;
            margin-top: 8px;
        }
        li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="page-title">Privacy Policy</div>
        
        <div class="content-wrapper">
            <div class="main-title">[ Privacy Policy ]</div>
            <div class="effective-date">Effective Date: February 4, 2004</div>
            
            <div class="section">
                <div class="section-header">Information We Collect</div>
                <div class="section-content">
                    <div class="privacy-item">
                        We collect information you provide when you register and use Thefacebook:
                        <ul>
                            <li>Name, email address, and basic profile information</li>
                            <li>Profile pictures and content you post</li>
                            <li>Messages and communications with other users</li>
                            <li>Information about your friends and connections</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">How We Use Your Information</div>
                <div class="section-content">
                    <div class="privacy-item">
                        Your information is used to:
                        <ul>
                            <li>Provide and maintain the Thefacebook service</li>
                            <li>Enable you to connect with other students at your university</li>
                            <li>Display your profile to other users at your school</li>
                            <li>Send you notifications and updates about the service</li>
                            <li>Improve and develop new features</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Information Sharing</div>
                <div class="section-content">
                    <div class="privacy-item">
                        Your profile information is visible to other students at your university who are registered on Thefacebook.
                    </div>
                    <div class="privacy-item">
                        We do not sell your personal information to third parties.
                    </div>
                    <div class="privacy-item">
                        We may share information when required by law or to protect our rights and the safety of our users.
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Your Privacy Controls</div>
                <div class="section-content">
                    <div class="privacy-item">
                        You control what information appears on your profile.
                    </div>
                    <div class="privacy-item">
                        You can choose who can see your profile and contact you.
                    </div>
                    <div class="privacy-item">
                        You can delete your account at any time through your account settings.
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Data Security</div>
                <div class="section-content">
                    We implement security measures to protect your information. However, no method of transmission over the Internet is 100% secure, and we cannot guarantee absolute security.
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Changes to This Policy</div>
                <div class="section-content">
                    We may update this Privacy Policy from time to time. We will notify users of any material changes by posting the new policy on this page.
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Contact Us</div>
                <div class="section-content">
                    If you have questions about this Privacy Policy, please contact us at privacy@thefacebook.com
                </div>
            </div>
            
            <div class="home-button">
                <a href="<?php echo is_logged_in() ? 'home.php' : 'index.php'; ?>" class="btn">Home</a>
            </div>
        </div>
    </div>
</body>
</html>