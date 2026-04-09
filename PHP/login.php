<?php
session_start();
require("dbcon.php");

$error = "";

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $wachtwoord = trim($_POST['wachtwoord'] ?? '');

    // Check if fields are empty
    if ($email === "" || $wachtwoord === "") {
        $error = "Vul alle velden in.";
    } else {

        // Fetch user credentials by email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verify password
        if ($user && password_verify($wachtwoord, $user['wachtwoord'])) {

            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_naam']  = $user['naam'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_functie'] = $user['functie'];

            header("Location: ../index.php");
            exit();

        } else {
            $error = "Ongeldige inloggegevens.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pagina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Centered logo -->
    <img src="../IMG/Bluepeak_Technologies.png" alt="BluePeak"
        style="display:block;margin:auto;width:400px;height:400px;">

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">

                <div class="card">

                    <!-- Card header -->
                    <div class="card-header">
                        <h4>
                            Login
                            <a href="Register.php" class="btn btn-danger float-end">Register</a>
                        </h4>
                    </div>

                    <!-- Card body -->
                    <div class="card-body">

                        <!-- Error message -->
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" id="melding">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <!-- Login form -->
                        <form method="POST">

                            <div class="mb-3">
                                <label>naam</label>
                                <input type="text" name="email" required class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="wachtwoord" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success mt-3">Login</button>

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Auto-hide error message -->
    <script>

    </script>

</body>

</html>