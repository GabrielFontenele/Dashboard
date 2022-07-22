
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="css/layout.css">
        <title>Dashboard</title>
    </head>
    <body>
        <div class="sidebar">
            <h3>Dashboard Test</h3>
            <button
                id="sidebar-button">
                test
                <span>></span>
            </button>
        </div>

        <div class="main-content">
            <div class="footer">Calendar Test</div>
            {{$slot}}
        </div>
    </body>
    
</html>
