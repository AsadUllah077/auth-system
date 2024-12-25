<?php
require 'config/db.php';
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);

    //server side validation
    if (empty($email)) {
        $errorMessages['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessages['email'] = 'Invalid email format.';
    }

    // Check if the email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generate a new password
        $reset_token = bin2hex(random_bytes(4));


        // Update the password in the database
        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $updateStmt->bind_param('ss', $reset_token, $email);
        if ($updateStmt->execute()) {
            // Send the new password to the user's email
            try {
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'asad.developer069@gmail.com';
                $mail->Password = 'jkpkhrjsmnrahbtl';
                $mail->setFrom('asad.developer069@gmail.com', 'Auth System');
                $mail->addAddress($email);
                $mail->Subject = 'Password Reset';
                $mail->Body = "Hello,\n\nYour new password is: <a href='http://localhost/TechnicalReview/reset_link.php?token=$reset_token'>$reset_token</a>\n\nPlease log in and change your password for security reasons.";
                if ($mail->send()) {
                    echo "<div class='alert alert-success'>A link for password update has been sent to your email.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Failed to send email. Please try again.</div>";
                }
            } catch (\Throwable $th) {
                echo "<div class='alert alert-danger'>Error: " . $th->getMessage() . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Failed to update password. Please try again.</div>";
        }
        $updateStmt->close();
    } else {
        echo "<div class='alert alert-danger'>Email not found.</div>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            <h3 class="text-center mb-4">Reset Password</h3>
            <form method="POST" action="reset_password.php" id="resetPasswordForm">
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Enter your email</label>
                    <input type="email" name="email" id="email" class="form-control">
                    <div id="email_error" class="error"></div>
                    <?php if (isset($errorMessages['email'])): ?>
                        <span class="text-danger"><?= $errorMessages['email'] ?></span>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>

                <!-- Back Link -->
                <div class="text-center mt-3">
                    <a href="login.php" class="text-decoration-none">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
    <script src="js/validation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>