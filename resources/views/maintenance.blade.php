<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - GlobalSkyFleet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .maintenance-container {
            text-align: center;
            color: white;
            max-width: 500px;
            padding: 20px;
        }
        
        .maintenance-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.9;
        }
        
        h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        p {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.95;
        }
        
        .maintenance-details {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .maintenance-details p {
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .support-link {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.3s;
        }
        
        .support-link:hover {
            opacity: 0.8;
        }
        
        .loading-dots {
            display: inline-block;
        }
        
        .loading-dots span {
            animation: bounce 1.4s infinite;
        }
        
        .loading-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .loading-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes bounce {
            0%, 80%, 100% {
                transform: translateY(0);
                opacity: 0.8;
            }
            40% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <i class="ri-tools-line"></i>
        </div>
        
        <h1>Under Maintenance</h1>
        
        <p>
            We're currently performing scheduled maintenance on our website.
            We'll be back online shortly!
        </p>
        
        <div class="maintenance-details">
            <p>
                <i class="ri-time-line me-2"></i>
                <strong>Expected Duration:</strong> A few minutes to a few hours
            </p>
            <p>
                <i class="ri-mail-line me-2"></i>
                For urgent inquiries, contact us at 
                <a href="mailto:support@globalskyfleet.com" class="support-link">support@globalskyfleet.com</a>
            </p>
        </div>
        
        <p style="font-size: 14px;">
            <div class="loading-dots">
                <span>.</span><span>.</span><span>.</span>
            </div>
        </p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
