<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Register
                            <a href="login.php" class="btn btn-danger float-end">Login</a>
                        </h4>

                        <div class="mb-4">
                            <form action="Register.php" method="post">

                                <div class="mb-3">
                                    <label>Username</label>
                                    <input type="text" required class="form-control" name="naam">
                                </div>

                                <div class="mb-3">
                                    <label>Password</label>
                                    <input type="password" required class="form-control" name="wachtwoord">
                                </div>

                                <div class="mb-3">
                                    <label>Confirm Password</label>
                                    <input type="password" required class="form-control" name="wachtwoord2">
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" required class="form-control" name="email">
                                </div>

                                <div class="mb-3">
                                    <label>Phone number</label>
                                    <input type="text" required class="form-control" name="nummer">
                                </div>

                                <div class="mb-3">
                                    <label>Function</label>
                                    <select class="form-control" name="functie">
                                        <option value="">Select Function</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Employee">Employee</option>
                                    </select>
                                </div>

                                <div class="mb3">

                                    <button type="submit" name="save_employee"
                                        class="btn btn-success mb-3 mt-3">Register</button>

                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require("dbcon.php");

        $naam = $_POST["naam"];
        $wachtwoord = $_POST["wachtwoord"];
        $wachtwoord2 = $_POST["wachtwoord2"];
        $email = $_POST["email"];
        $nummer = $_POST["nummer"];
        $functie = $_POST["functie"];

        // WACHTWOORD CHECK
        if ($wachtwoord !== $wachtwoord2) {
            echo "Wachtwoorden komen niet overeen";
            exit;
        }

        // HASHEN
        $hashed = password_hash($wachtwoord, PASSWORD_DEFAULT);

        // SQL INSERT
        $sql = "INSERT INTO users (naam, wachtwoord, email, nummer, functie)
        VALUES (:naam, :wachtwoord, :email, :nummer, :functie)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':naam' => $naam,
            ':wachtwoord' => $hashed,
            ':email' => $email,
            ':nummer' => $nummer,
            ':functie' => $functie
        ]);


        echo '<div id="melding">Registratie gelukt!</div><br>';
        echo '<div id="melding">Ga naar de login pagina en probeer opnieuw </div>';


    }
    ?>
    <script>
        setTimeout(function () {
            document.getElementById('melding').style.display = 'none';
        }, 5000);


    </script>






</body>

</html>