<?php
session_start();
require("dbcon.php");

// Als je al ingelogd bent → naar index
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$error = ""; // lege foutmelding

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Gebruiker ophalen
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Wachtwoord controleren
    if ($user && isset($user['password']) && password_verify($password, $user['password'])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_naam"] = $user["naam"];
        $_SESSION["user_email"] = $user["email"];   

        header("Location: ../index.php");
        exit();

    } else {
        $error = "Ongeldige inloggegevens. Probeer opnieuw.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<img src="../IMG/Bluepeak_Technologies.png" alt="BluePeak" style="display:block;margin:auto;width:400px;height:400px;">

<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Login
                        <a href="Register.php" class="btn btn-danger float-end">Register</a>
                    </h4>
                </div>

                <div class="card-body">

                    <!-- FIX: action verwijst nu naar login.php -->
                    <form action="../index.php" method="post">

                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" required class="form-control" name="naam">
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" required class="form-control" name="email">
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" required class="form-control" name="password">
                        </div>

                        <button type="submit" class="btn btn-success mt-3">Login</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
setTimeout(() => {
    let m = document.getElementById('melding');
    if (m) m.style.display = 'none';
}, 5000);
</script>

</body>
</html>