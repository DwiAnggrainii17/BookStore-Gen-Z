<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background: #45a049;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h2>Register Admin</h2>
    <form action="proses_register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required placeholder="Masukkan username">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required placeholder="Masukkan password">

        <button type="submit">Register</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>

</html>