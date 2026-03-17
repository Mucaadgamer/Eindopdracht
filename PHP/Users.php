<?php
session_start();
require_once 'dbcon.php';

$zoekterm = isset($_GET['zoekterm']) ? $_GET['zoekterm'] : '';

if ($zoekterm != '') {

    $sql = "SELECT * FROM users 
            WHERE naam LIKE '%$zoekterm%' 
            OR id LIKE '%$zoekterm%'
            OR email LIKE '%$zoekterm%' 
            OR functie LIKE '%$zoekterm%' 
            OR nummer LIKE '%$zoekterm%'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute( );
} else {
    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT 10");
    $stmt->execute();
}

$Users = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/Users.css">
</head>

<body>
    <img src="../IMG/Bluepeak_Technologies.png" alt="Bluepeak Technologies">

    <h2>Alle gebruikers</h2>

    <div class="test">
        <form action="" method="get">
            <input type="text" name="zoekterm" placeholder="Zoeken">
            <button type="submit">Zoek</button>
        </form>

        <button class="Add">Add User</button>
    </div>


    <?php if (empty($Users)): ?>
        <p>Er zijn geen Users</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr class="theads">
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Email</th>
                    <th>Functie</th>
                    <th>Nummer</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($Users as $User): ?>
                    <tr>
                        <td><?php echo $User['id']; ?></td>
                        <td><?php echo htmlspecialchars($User['naam']); ?></td>
                        <td><?php echo htmlspecialchars($User['email']); ?></td>
                        <td><?php echo htmlspecialchars($User['functie']); ?></td>
                        <td><?php echo htmlspecialchars($User['nummer']); ?></td>
                        <td><button class="btn btn-primary">Edit</button></td>
                        <td><button class="btn btn-danger">Delete</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    <?php endif; ?>
</body>

</html>