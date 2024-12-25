<?php
session_start();
require 'config/db.php';

$errorMessages = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailOrName = htmlspecialchars($_POST['emailOrName']);
    $password = $_POST['password'];

    //server side validation
    if (empty($emailOrName)) {
        $errorMessages['emailOrName'] = 'emailOrName is required.';
    } elseif (!filter_var($emailOrName, FILTER_VALIDATE_EMAIL)) {
        $errorMessages['emailOrName'] = 'Invalid email format.';
    }

    if (empty($password)) {
        $errorMessages['password'] = 'Password is required.';
    }
    if (empty($errorMessages)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR name = ?");
        $stmt->bind_param('ss', $emailOrName, $emailOrName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header('Location: dashboard.php');
            } else {
                echo "<div class='alert alert-danger'>Invalid password.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>User not found.</div>";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error {
            color: red;
            font-size: 0.875em;
            margin-top: 5px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">Login</h3>
            <form method="POST" action="login.php" id="loginForm">
                <!-- Email or Name -->
                <div class="mb-3">
                    <label for="emailOrName" class="form-label">Email or Name</label>
                    <input type="text" name="emailOrName" id="emailOrName" class="form-control">
                    <div id="emailOrName_error" class="error"></div>
                    <?php if (isset($errorMessages['emailOrName'])): ?>
                        <span class="text-danger"><?= $errorMessages['emailOrName'] ?></span>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <div id="password_error" class="error"></div>
                    <?php if (isset($errorMessages['password'])): ?>
                        <span class="text-danger"><?= $errorMessages['password'] ?></span>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>

                <!-- Back Link -->
                <div class="text-center mt-3">
                    <a href="index.php" class="text-decoration-none">Back to Home</a>
                </div>
                <div class="text-center mt-3">
                    <a href="reset_password.php" class="text-decoration-none">Reset Password</a>
                </div>
            </form>
        </div>
    </div>
    <script src="js/validation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>