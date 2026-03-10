<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
<h2>Login Account</h2>
<form method="POST">
    @csrf
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>
<button>Login</button>
</form>
</div>
</body>
</html>
