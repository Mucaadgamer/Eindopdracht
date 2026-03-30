<!-- Start session and load database connection -->
<?php
session_start();
require("dbcon.php");

// If user is already logged in, redirect to homepage
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Prepare an empty error message
$error = "";

//Handle login form submission 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verify password
    if ($user && isset($user['password']) && password_verify($password, $user['password'])) {

        // Store user info in session
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_naam"] = $user["naam"];
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["user_functie"] = $user["functie"];

        // Redirect based on user role
        if ($user["functie"] === "Manager") {
            header("Location: ../index.php");
            exit();
        } else {
            header("Location: employee.php");
            exit();
        }

    } else {
        // Show error if login fails
        $error = "Ongeldige inloggegevens. Probeer opnieuw.";
    }
}
?>

<!-- Basic HTML structure and Bootstrap import -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<!-- Logo centered on the page -->

<body>

    <img src="../IMG/Bluepeak_Technologies.png" alt="BluePeak"
        style="display:block;margin:auto;width:400px;height:400px;">

    <!-- Login card container -->
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card"></div>
                <!-- Card header with Register button -->
                <div class="card-header">
                    <h4>Login
                        <a href="Register.php" class="btn btn-danger float-end">Register</a>
                    </h4>
                </div>
                <!-- Card body containing the login form -->
                <div class="card-body">

                    <!-- Login form -->
                    <form action="../index.php" method="post"></form>
                    <!-- Username input field -->
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" required class="form-control" name="naam">
                    </div>
                    <!-- Email input field -->
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" required class="form-control" name="email">
                    </div>
                    <!-- Password input field -->
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" required class="form-control" name="password">
                    </div>
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-success mt-3">Login</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Auto-hide message script (if used) -->
    <script>
        setTimeout(() => {
            let m = document.getElementById('melding');
            if (m) m.style.display = 'none';
        }, 5000);
    </script>

</body>

</html>