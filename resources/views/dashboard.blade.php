
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <link rel="icon" type="image/svg+xml" href="/src/favicon.svg" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Vite App</title>
    </head>
    <body>
        <div className="sidebar">
            <h3>Medical Test</h3>
            <button
                id="sidebar-button"
                onClick={() => {
                    setStateData();
                }}
                >
                {buttonText}
                <span>{">"}</span>
            </button>
        </div>

        <div className="main-content">
            {{$slot}}
            <div className="footer">Dashboard Test</div>
        </div>
    </body>
</html>
