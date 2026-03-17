<?php
session_start();
require_once 'PHP/dbcon.php';

// Data ophalen
$totaalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$recenteUsers = $pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 3")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <div class="header">
        <img src="IMG/Bluepeak_Technologies.png" alt="Bluepeak Technologies">
        <h1>Welkom manager</h1>
    </div>

    <div class="main">
        <div class="Statistieken">
            <table class="Stable">
                <tr>
                    <th class="table-title">Statistieken</th>
                </tr>   
                <tr>
                    <th>
                        <p>Totaal gebruikers: </p>
                    </th>
                </tr>

                <tr>
                    <td><strong><?php echo $totaalUsers; ?></strong></td>
                </tr>
            </table>
        </div>

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

    <div class="Knop">
        <button onclick="location.href='PHP/Users.php'">Alle Gebruikers</button>
    </div>
</body>

</html>