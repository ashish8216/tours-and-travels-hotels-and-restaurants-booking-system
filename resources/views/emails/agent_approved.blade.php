<!DOCTYPE html>
<html>
<head>
    <title>Agent Account Approved</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>
    <p>Your agent account has been approved!</p>
    <p>Here are your login credentials:</p>
    <ul>
        <li>Email: {{ $user->email }}</li>
        <li>Password: {{ $password }}</li>
    </ul>
    <p>Please login and change your password after your first login.</p>
</body>
</html>
