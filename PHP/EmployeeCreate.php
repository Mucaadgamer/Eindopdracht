<?php

// Include database connection
require_once "dbcon.php";


// here is the code for creating a new employee 
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // some variables to store data in for the rest of the code
    $naam = trim($_POST["naam"]);
    $wachtwoord = password_hash($_POST["wachtwoord"], PASSWORD_DEFAULT);
    $email = trim($_POST["email"]);
    $nummer = trim($_POST["nummer"]);
    $functie = trim($_POST["functie"]);

    // sql instert query where it gets and inserts data from the form to the actual database
    $sql = "INSERT INTO users (naam, wachtwoord, email, nummer, functie)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$naam, $wachtwoord, $email, $nummer, $functie]);

    // after employee is created it shows this message with a 5 
// second cooldown timer before it dissapears and sends you back to the main page    
    echo "<p style='color:green;'> Gebruiker succesvol toegevoegd. Je wordt over 5 seconden doorgestuurd...</p>";

    echo "
    <script> 
        setTimeout(function() {
            window.location.href = 'users.php?created=1';
        }, 2000);
    </script>";

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- start of the form for creating a new employee -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="m-0">Employee Create</h4>
                        <a href="users.php" class="btn btn-danger">BACK</a>
                    </div>

                    <div class="card-body">
                        <!-- POST means that it posts to the php code and eventually to the database -->
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Naam</label>
                                <input type="text" name="naam" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">wachtwoord</label>
                                <input type="password" name="wachtwoord" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">nummer</label>
                                <input type="text" name="nummer" class="form-control" required>
                            </div>
                            <!-- Options here are manager and employee  -->
                            <div class="mb-3">
                                <label class="form-label">Function</label>
                                <select name="functie" class="form-select" required>
                                    <option value="">Select Function</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Employee">Employee</option>
                                </select>
                            </div>
                            <!-- Submit where if you press the button the code starts to execute -->
                            <button type="submit" class="btn btn-success mt-3">Save Employee</button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>