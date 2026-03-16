<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
        <img src="../IMG/Bluepeak_Technologies.png" alt="BluePeak">

    <style>
        img{
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 400px;
            height: 400px;
        }
    </style>

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Login
                            <a href="Register.php" class="btn btn-danger float-end">Register</a>
                        </h4>

                        <div class="mb-4">
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

                                <div class="mb-3">
                                    <button type="submit" name="login" class="btn btn-success mt-3">
                                        Login
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>`





<?php
require("dbcon.php");
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($email);
    $user = $stmt->fetch();


    if ($user && password_verify($wachtwoord, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_naam"] = $user["naam"];
        $_SESSION["user_email"] = $user["email"];

        header('location: ../index.php');
        exit();

    } else {
        echo '<div class"Time"> Ongeldige inloggegevens. probeer opnieuw!</div>';
    }
}
?>

<script>
    setTimeout(function () {
        document.getElementById('melding').style.display = 'none';
    }, 5000);
</script>


</html>