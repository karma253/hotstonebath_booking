<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Login - Hot Stone Bath</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #48dbfb 0%, #0abde3 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .login-header h1 {
            color: #0abde3;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #666;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #0abde3;
            box-shadow: 0 0 5px rgba(10, 189, 227, 0.3);
        }

        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background: #0abde3;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background: #048ba8;
        }

        .links-section {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }

        .links-section p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .links-section a {
            color: #0abde3;
            text-decoration: none;
            font-weight: bold;
        }

        .links-section a:hover {
            text-decoration: underline;
        }

        .demo-box {
            background: #fffbea;
            border: 1px solid #ffe8a8;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            color: #666;
        }

        .demo-box strong {
            color: #0abde3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">🛁</div>
            <h1>Guest Login</h1>
            <p>Search and book hot stone baths</p>
        </div>

        <div class="demo-box">
            <strong>Demo Login:</strong><br>
            Email: guest@example.com<br>
            Password: password
        </div>

        <form method="POST" action="/guest/authenticate">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="guest@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="links-section">
            <p>Don't have an account? <a href="#register">Sign up here</a></p>
            <p><a href="/">← Back to Home</a></p>
        </div>
    </div>
</body>
</html>
