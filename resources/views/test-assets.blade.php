<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Test - Dunco School Management System</title>
    
    <!-- Test all asset types -->
    <link href="{{ asset('css/font-fallback.css') }}" rel="stylesheet">
    <link href="{{ asset('css/essential.css') }}" rel="stylesheet">
    <script src="{{ asset('js/font-loader.js') }}"></script>
    <script src="{{ asset('js/essential.js') }}"></script>
    
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        
        .test-section {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .warning { background-color: #fff3cd; color: #856404; }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Asset Loading Test</h1>
    <p>This page tests whether all assets are loading correctly.</p>
    
    <div class="test-section">
        <h2>Server Information</h2>
        <p><strong>Server Time:</strong> {{ now() }}</p>
        <p><strong>Laravel Version:</strong> {{ app()->version() }}</p>
        <p><strong>Environment:</strong> {{ app()->environment() }}</p>
    </div>
    
    <div class="test-section">
        <h2>Asset Tests</h2>
        <div id="asset-status">Checking assets...</div>
    </div>
    
    <div class="test-section">
        <h2>Font Loading Test</h2>
        <div id="font-status">Checking fonts...</div>
        <div style="font-size: 24px; margin: 10px 0;">
            <div style="font-family: 'Inter', sans-serif;">Inter Font: School Management System</div>
            <div style="font-family: 'Poppins', sans-serif;">Poppins Font: School Management System</div>
            <div style="font-family: system-ui, sans-serif;">System Font: School Management System</div>
        </div>
    </div>
    
    <div class="test-section">
        <h2>Navigation</h2>
        <a href="/" class="btn">Home Page</a>
        <a href="/test" class="btn">Server Test</a>
        <a href="/test-fonts.html" class="btn">Font Test</a>
        <a href="/welcome-original" class="btn">Original Welcome</a>
    </div>
    
    <script>
        // Test asset loading
        function testAssets() {
            const assets = [
                { name: 'Font Fallback CSS', url: '{{ asset("css/font-fallback.css") }}' },
                { name: 'Essential CSS', url: '{{ asset("css/essential.css") }}' },
                { name: 'Font Loader JS', url: '{{ asset("js/font-loader.js") }}' },
                { name: 'Essential JS', url: '{{ asset("js/essential.js") }}' }
            ];
            
            const statusDiv = document.getElementById('asset-status');
            let results = '';
            
            assets.forEach(asset => {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = asset.url;
                
                link.onload = () => {
                    results += `<div class="success">✅ ${asset.name} loaded successfully</div>`;
                    statusDiv.innerHTML = results;
                };
                
                link.onerror = () => {
                    results += `<div class="error">❌ ${asset.name} failed to load</div>`;
                    statusDiv.innerHTML = results;
                };
                
                document.head.appendChild(link);
            });
        }
        
        // Test font loading
        function testFonts() {
            const fontStatusDiv = document.getElementById('font-status');
            
            if (window.FontLoader) {
                if (window.FontLoader.isLoaded()) {
                    fontStatusDiv.innerHTML = '<div class="success">✅ Custom fonts loaded successfully</div>';
                } else if (window.FontLoader.isLoading()) {
                    fontStatusDiv.innerHTML = '<div class="warning">⏳ Fonts are still loading...</div>';
                } else {
                    fontStatusDiv.innerHTML = '<div class="error">❌ Custom fonts failed to load, using system fonts</div>';
                }
            } else {
                fontStatusDiv.innerHTML = '<div class="error">❌ Font loader not available</div>';
            }
        }
        
        // Run tests when page loads
        document.addEventListener('DOMContentLoaded', () => {
            testAssets();
            setTimeout(testFonts, 1000);
        });
    </script>
</body>
</html>


