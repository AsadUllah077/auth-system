<?php
require 'config/db.php';
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
$errorMessages = [];
if (isset($_GET['token'])) {

    // Check if the email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
    $stmt->bind_param('s', $_GET['token']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // $updateStmt->close();
        if (isset($_POST['update'])) {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            // sever side validation
            if (empty($password)) {
                $errorMessages['password'] = 'Password is required.';
            }
            if (empty($errorMessages)) {
                $stmt = $conn->prepare("UPDATE users SET password =?, reset_token = NULL WHERE reset_token =?");
                $stmt->bind_param('ss', $password, $_GET['token']);
                if ($stmt->execute()) {
                    echo "Password updated successfully. You can now log in.";
                    header('Location: login.php');
                } else {
                    echo "Error updating password.";
                }
            }
        }
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>New Password</title>
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
                    <h3 class="text-center mb-4">New Password</h3>
                    <form id="updateForm" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>?token=<?php echo $_GET['token'] ?>">
                        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">

                        <div class="form-group col-lg-12">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                            <div id="password_error" class="error"></div>
                            <?php if (isset($errorMessages['password'])): ?>
                                <span class="text-danger"><?= $errorMessages['password'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" class="form-control">
                            <div id="confirm_password_error" class="error"></div>
                        </div>


                        <!-- Submit Button -->
                        <div class="d-grid mt-3">
                            <button type="submit" name="update" class="btn btn-primary">Update Password</button>
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


<?php
    } else {
        echo "<div class='alert alert-danger'>Not found.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Not found.</div>";
}
?>