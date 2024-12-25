<?php
require 'config/db.php';
require __DIR__ . '/vendor/autoload.php';

$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    //Server Side  Validate inputs
    if (empty($name)) {
        $errorMessages['name'] = 'Name is required.';
    }

    if (empty($email)) {
        $errorMessages['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessages['email'] = 'Invalid email format.';
    }

    if (empty($phone)) {
        $errorMessages['phone'] = 'Phone number is required.';
    }

    if (empty($gender)) {
        $errorMessages['gender'] = 'Gender is required.';
    }

    if (empty($password)) {
        $errorMessages['password'] = 'Password is required.';
    }

    if (empty($errorMessages)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Check if email already exists
        $emailCheckStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $emailCheckStmt->bind_param('s', $email);
        $emailCheckStmt->execute();
        $emailCheckStmt->store_result();

        if ($emailCheckStmt->num_rows > 0) {
            $errorMessages['email'] = 'Email already exists. Please use a different email.';
            $emailCheckStmt->close();
        } else {
            $emailCheckStmt->close();

            // Insert new user record
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, gender, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssss', $name, $email, $phone, $gender, $passwordHash);

            if ($stmt->execute()) {
                try {
                    $mail = new PHPMailer\PHPMailer\PHPMailer();
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Port = 587;
                    $mail->SMTPSecure = 'tls';
                    $mail->Username = 'asad.developer069@gmail.com';
                    $mail->Password = 'jkpkhrjsmnrahbtl';
                    $mail->setFrom('asad.developer069@gmail.com', 'Auth System');
                    $mail->addAddress($email);
                    $mail->Subject = 'Welcome to Auth System';
                    $mail->Body = "Hello $name,\n\nWelcome to our system!";
                    $mail->send();
                } catch (\Throwable $th) {
                    echo "Mail Error: " . $th->getMessage();
                    die();
                }

                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="page-inner">
        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h3 class="mb-0 bc-title"><b>Register</b></h3>
                        <a class="btn btn-sm btn-purple" href="index.php">
                            <i class="fas fa-chevron-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card o-hidden border-0 shadow-lg">
                        <div class="card-body">
                            <form id="registrationForm" method="POST" action="register.php">
                                <div class="row">
                                    <!-- Name -->
                                    <div class="form-group col-lg-12">
                                        <label class="error" for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control">
                                        <span style="color: red;" id="name_required"></span>
                                        <?php if (isset($errorMessages['name'])): ?>
                                            <span class="text-danger"><?= $errorMessages['name'] ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group col-lg-12">
                                        <label class="error" for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control">
                                        <span style="color: red;" id="email_required"></span>
                                        <?php if (isset($errorMessages['email'])): ?>
                                            <span class="text-danger"><?= $errorMessages['email'] ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Phone -->
                                    <div class="form-group col-lg-12">
                                        <label class="error" for="phone">Phone</label>
                                        <input type="tel" name="phone" id="phone" class="form-control">
                                        <span style="color: red;" id="phone_required"></span>
                                        <?php if (isset($errorMessages['phone'])): ?>
                                            <span class="text-danger"><?= $errorMessages['phone'] ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Gender -->
                                    <div class="form-group col-lg-12">
                                        <label class="error" for="gender">Gender</label>
                                        <select name="gender" id="gender" class="form-control form-select">
                                            <option value="">Select</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        <span style="color: red;" id="gender_required"></span>
                                        <?php if (isset($errorMessages['gender'])): ?>
                                            <span class="text-danger"><?= $errorMessages['gender'] ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group col-lg-12">
                                        <label class="error" for="password">Password</label>
                                        <input type="password" name="password" id="password" class="form-control">
                                        <span style="color: red;" id="password_required"></span>
                                        <?php if (isset($errorMessages['password'])): ?>
                                            <span class="text-danger"><?= $errorMessages['password'] ?></span>
                                        <?php endif; ?>

                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group col-lg-12">
                                        <label class="error" for="confirmPassword">Confirm Password</label>
                                        <input type="password" id="confirmPassword" class="form-control">
                                        <span style="color: red;" id="confirmpassword_required"></span>

                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <!-- Submit Button -->
                                    <div class=" mt-4">
                                        <button type="submit" class="btn btn-primary">Register</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/validation.js"></script>
</body>

</html>