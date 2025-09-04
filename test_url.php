<!DOCTYPE html>
<html>
<head>
    <title>Test URL</title>
</head>
<body>
    <h2>Test Base URL</h2>
    <p>Current URL: <span id="current-url"></span></p>
    <p>Base URL: <span id="base-url"></span></p>
    <p>Redirect URL: <span id="redirect-url"></span></p>
    
    <script>
        function getBaseUrl() {
            const currentUrl = window.location.href;
            const baseUrl = currentUrl.substring(0, currentUrl.lastIndexOf('/loginlogout/'));
            return baseUrl;
        }
        
        document.getElementById('current-url').textContent = window.location.href;
        document.getElementById('base-url').textContent = getBaseUrl();
        document.getElementById('redirect-url').textContent = getBaseUrl() + '/index.php?login';
        
        console.log('Current URL:', window.location.href);
        console.log('Base URL:', getBaseUrl());
        console.log('Redirect URL:', getBaseUrl() + '/index.php?login');
    </script>
</body>
</html>
