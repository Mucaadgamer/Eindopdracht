<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<!-- body containins the registration form -->

<body>

    <!-- Centered container for the registration card -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <!-- Card header with Register title and Login button -->
                    <div class="card-header">
                        <h4>Register
                            <a href="login.php" class="btn btn-danger float-end">Login</a>
                        </h4>

                        <!-- Registration margin bottom only -->
                        <div class="mb-4">
                            <!-- Registration form -->
                            <form action="Register.php" method="post">

                                <!-- Username input field -->
                                <div class="mb-3">
                                    <label>Username</label>
                                    <input type="text" required class="form-control" name="naam">
                                </div>

                                <!-- Password input field -->
                                <div class="mb-3">
                                    <label>Password</label>
                                    <input type="password" required class="form-control" name="wachtwoord">
                                </div>

                                <!-- Confirm password input field -->
                                <div class="mb-3">
                                    <label>Confirm Password</label>
                                    <input type="password" required class="form-control" name="wachtwoord2">
                                </div>

                                <!-- Email input field -->
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" required class="form-control" name="email">
                                </div>

                                <!-- Phone number input field -->
                                <div class="mb-3">
                                    <label>Phone number</label>
                                    <input type="text" required class="form-control" name="nummer">
                                </div>

                                <!-- Function dropdown -->
                                <div class="mb-3">
                                    <label>Function</label>
                                    <select class="form-control" name="functie">
                                        <option value="">Select Function</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Employee">Employee</option>
                                    </select>
                                </div>

                                <!-- Submit button -->
                                <div class="mb3">
                                    <button type="submit" name="save_employee"
                                        class="btn btn-success mb-3 mt-3">Register</button>
                                </div>

                            </form>
                        </div>
                        <!-- end all the divs that where used -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  -->
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // database connection with variables for the rest of the code
        require("dbcon.php");

        $naam = $_POST["naam"];
        $wachtwoord = $_POST["wachtwoord"];
        $wachtwoord2 = $_POST["wachtwoord2"];
        $email = $_POST["email"];
        $nummer = $_POST["nummer"];
        $functie = $_POST["functie"];

        // Check if passwords match
        if ($wachtwoord !== $wachtwoord2) {
            echo "Wachtwoorden komen niet overeen";
            exit;
        }

        // Hash the password
        $hashed = password_hash($wachtwoord, PASSWORD_DEFAULT);

        // SQL insert into the database query
        $sql = "INSERT INTO users (naam, wachtwoord, email, nummer, functie)
        VALUES (:naam, :wachtwoord, :email, :nummer, :functie)";

        $stmt = $pdo->prepare($sql);

        // Execute insert query
        $stmt->execute([
            ':naam' => $naam,
            ':wachtwoord' => $hashed,
            ':email' => $email,
            ':nummer' => $nummer,
            ':functie' => $functie
        ]);

        // Success messages
        echo '<div id="melding">Registratie gelukt!</div><br>';
        echo '<div id="melding">Ga naar de login pagina en probeer opnieuw </div>';
    }
    ?>

    <!-- Auto-hide success message after 5 seconds -->
    <script>
        setTimeout(function () {
            document.getElementById('melding').style.display = 'none';
        }, 5000);
    </script>

</body>

</html>