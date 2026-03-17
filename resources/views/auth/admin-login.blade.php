<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hot Stone Bath</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            color: #e74c3c;
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
            border-color: #e74c3c;
            box-shadow: 0 0 5px rgba(231, 76, 60, 0.3);
        }

        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background: #c0392b;
        }

        .guest-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }

        .guest-link p {
            color: #666;
            font-size: 0.9rem;
        }

        .guest-link a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }

        .guest-link a:hover {
            text-decoration: underline;
        }

        .home-link {
            display: inline-block;
            margin-top: 1rem;
            color: #f5576c;
            text-decoration: none;
        }

        .home-link:hover {
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
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">🔐</div>
            <h1>Admin Login</h1>
            <p>Platform management and verification</p>
        </div>

        <div class="demo-box">
            <strong>Demo Login:</strong><br>
            Email: admin@example.com<br>
            Password: password
        </div>

        <form method="POST" action="/admin/authenticate">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="admin@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="guest-link">
            <p>Back to <a href="/">Home</a></p>
        </div>
    </div>
</body>
</html>
