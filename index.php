<!-- Start session and load database connection -->
<?php
session_start();
require_once 'PHP/dbcon.php';

//Fetch total number of users
$totaalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

//Fetch the 3 most recent users
$recenteUsers = $pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 3")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>

    <!-- Header with logo and welcome message -->
    <div class="header">
        <img src="IMG/Bluepeak_Technologies.png" alt="Bluepeak Technologies">
        <h1>Welkom manager</h1>
    </div>

    <!-- Main dashboard content -->
    <div class="main">

        <!-- This shows the Employee count how many employees there are -->
        <div class="Statistieken">
            <table class="Stable">
                <tr>
                    <th class="table-title">Statistieken</th>
                </tr>
                <tr>
                    <th>Totaal gebruikers:</th>
                </tr>
                <tr>
                    <td><strong><?php echo $totaalUsers; ?></strong></td>
                </tr>
            </table>
        </div>

        <!-- Table showing the 3 most recent users -->
        <div>
            <table>
                <tr>
                    <th colspan="4" class="table-title">Recente Gebruikers</th>
                </tr>
                <tr>
                    <th>Naam</th>
                    <th>Email</th>
                    <th>Functie</th>
                    <th>Nummer</th>
                </tr>

                <!-- Loop through recent users and display them -->
                <?php foreach ($recenteUsers as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['naam']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['functie']); ?></td>
                        <td><?php echo htmlspecialchars($user['nummer']); ?></td>
                    </tr>
                <?php endforeach; ?>

            </table>
        </div>
    </div>

    <!-- Button linked with the User edit dashboard -->
    <div class="Knop">
        <button onclick="location.href='PHP/Users.php'">Alle Gebruikers</button>
    </div>

    <!-- Logout button -->
    <div class="Knop">
        <button onclick="location.href='PHP/login.php'">Logout</button>
    </div>

</body>

</html>