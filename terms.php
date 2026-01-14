<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Terms of Service - Thefacebook</title>
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
        .term-item {
            margin-bottom: 12px;
        }
        .term-number {
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
        .effective-date {
            text-align: center;
            color: #666;
            font-size: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="page-title">Terms of Service</div>
        
        <div class="content-wrapper">
            <div class="main-title">[ Terms of Service ]</div>
            <div class="effective-date">Effective Date: February 4, 2004</div>
            
            <div class="section">
                <div class="section-header">Acceptance of Terms</div>
                <div class="section-content">
                    By accessing or using Thefacebook, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use this service.
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Eligibility</div>
                <div class="section-content">
                    <div class="term-item">
                        <span class="term-number">1.</span> You must have a valid .edu email address from a participating university.
                    </div>
                    <div class="term-item">
                        <span class="term-number">2.</span> You must provide accurate and truthful information during registration.
                    </div>
                    <div class="term-item">
                        <span class="term-number">3.</span> You may not create multiple accounts or impersonate others.
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">User Conduct</div>
                <div class="section-content">
                    <div class="term-item">
                        You agree not to:
                    </div>
                    <div class="term-item">
                        • Post content that is unlawful, harmful, threatening, abusive, harassing, defamatory, vulgar, obscene, or otherwise objectionable.
                    </div>
                    <div class="term-item">
                        • Harass, stalk, or harm other users.
                    </div>
                    <div class="term-item">
                        • Upload viruses or malicious code.
                    </div>
                    <div class="term-item">
                        • Attempt to gain unauthorized access to the service or other user accounts.
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Content Ownership</div>
                <div class="section-content">
                    You retain ownership of all content you post on Thefacebook. However, by posting content, you grant Thefacebook a non-exclusive license to use, copy, and display such content on the service.
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Account Termination</div>
                <div class="section-content">
                    Thefacebook reserves the right to suspend or terminate accounts that violate these Terms of Service without prior notice.
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">Disclaimer</div>
                <div class="section-content">
                    Thefacebook is provided "as is" without warranties of any kind. We do not guarantee uninterrupted or error-free service.
                </div>
            </div>
            
            <div class="home-button">
                <a href="<?php echo is_logged_in() ? 'home.php' : 'index.php'; ?>" class="btn">Home</a>
            </div>
        </div>
    </div>
</body>
</html>