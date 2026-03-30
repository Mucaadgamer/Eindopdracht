<?php
session_start();
require_once 'dbcon.php';


// ----------------------
// ADD USER FUNCTION
// ----------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["action"] === "add") {

    $naam = trim($_POST['naam']);
    $email = trim($_POST['email']);
    $wachtwoord = $_POST['wachtwoord'];
    $functie = $_POST['functie'];

    if (!empty($naam) && !empty($email) && !empty($wachtwoord)) {

        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if (!$check->fetch()) {

            $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (naam, email, wachtwoord, functie) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$naam, $email, $hash, $functie]);

            header("Location: users.php?toegevoegd=1");
            exit();
        }
    }
}


// ----------------------
// UPDATE USER FUNCTION
// ----------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["action"] === "update") {

    $id = $_POST["id"];
    $naam = trim($_POST['naam']);
    $email = trim($_POST['email']);
    $functie = $_POST['functie'];
    $nummer = $_POST['nummer'];

    $sql = "UPDATE users SET naam=?, email=?, functie=?, nummer=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$naam, $email, $functie, $nummer, $id]);

    header("Location: users.php?updated=1");
    exit();
}


// ----------------------
// PAGINATION + SEARCH
// ----------------------
$zoekterm = isset($_GET['zoekterm']) ? $_GET['zoekterm'] : '';

$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if ($zoekterm != '') {

    $sql = "SELECT * FROM users 
            WHERE naam LIKE :term
            OR id LIKE :term
            OR email LIKE :term
            OR functie LIKE :term
            OR nummer LIKE :term
            ORDER BY id DESC
            LIMIT $limit OFFSET $offset";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['term' => "%$zoekterm%"]);

    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM users 
                                WHERE naam LIKE :term
                                OR id LIKE :term
                                OR email LIKE :term
                                OR functie LIKE :term
                                OR nummer LIKE :term");
    $countStmt->execute(['term' => "%$zoekterm%"]);
    $totalUsers = $countStmt->fetchColumn();

} else {

    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $limit OFFSET $offset");
    $stmt->execute();

    $totalStmt = $pdo->query("SELECT COUNT(*) FROM users");
    $totalUsers = $totalStmt->fetchColumn();
}

$totalPages = ceil($totalUsers / $limit);
$Users = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
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

        <button onclick="location.href='../index.php'" class="btn btn-secondary" style="margin-right: 10px;">Back</button>

        <button data-bs-toggle="modal" data-bs-target="#addModal" class="btn btn-outline-success btn-sm">Add User</button>
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
                </tr>
            </thead>

            <tbody>
                <?php foreach ($Users as $User): ?>
                    <tr>
                        <td><?= htmlspecialchars($User['id']); ?></td>
                        <td><?= htmlspecialchars($User['naam']); ?></td>
                        <td><?= htmlspecialchars($User['email']); ?></td>
                        <td><?= htmlspecialchars($User['functie']); ?></td>
                        <td><?= htmlspecialchars($User['nummer']); ?></td>

                        <td>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal"
                                data-id="<?= $User['id']; ?>" data-naam="<?= $User['naam']; ?>"
                                data-email="<?= $User['email']; ?>" data-functie="<?= $User['functie']; ?>"
                                data-nummer="<?= $User['nummer']; ?>">
                                Edit
                            </button>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    <?php endif; ?>


    <!-- ADD USER MODAL -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="post">
                    <input type="hidden" name="action" value="add">

                    <div class="modal-header">
                        <h5 class="modal-title" style="color:green;">Voeg User toe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label>Naam</label>
                        <input type="text" name="naam" class="form-control" required>

                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>

                        <label>Wachtwoord</label>
                        <input type="password" name="wachtwoord" class="form-control" required>

                        <label>Functie</label>
                        <select name="functie" class="form-control">
                            <option value="Employee">Employee</option>
                            <option value="Manager">Manager</option>
                        </select>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Opslaan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- UPDATE USER MODAL -->
    <div class="modal fade" id="updateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="post">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" id="update-id">

                    <div class="modal-header">
                        <h5 class="modal-title" style="color:blue;">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label>Naam</label>
                        <input type="text" name="naam" id="update-naam" class="form-control" required>

                        <label>Email</label>
                        <input type="email" name="email" id="update-email" class="form-control" required>

                        <label>Nummer</label>
                        <input type="text" name="nummer" id="update-nummer" class="form-control" required>

                        <label>Functie</label>
                        <select name="functie" id="update-functie" class="form-control">
                            <option value="Employee">Employee</option>
                            <option value="Manager">Manager</option>
                        </select>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Opslaan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- PAGINATION -->
    <nav>
        <ul class="pagination justify-content-center">

            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>&zoekterm=<?= $zoekterm ?>">Vorige</a>
            </li>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&zoekterm=<?= $zoekterm ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>&zoekterm=<?= $zoekterm ?>">Volgende</a>
            </li>

        </ul>
    </nav>


    <!-- UPDATE MODAL SCRIPT -->
    <script>
        const updateModal = document.getElementById('updateModal');

        updateModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            document.getElementById('update-id').value = button.getAttribute('data-id');
            document.getElementById('update-naam').value = button.getAttribute('data-naam');
            document.getElementById('update-email').value = button.getAttribute('data-email');
            document.getElementById('update-functie').value = button.getAttribute('data-functie');
            document.getElementById('update-nummer').value = button.getAttribute('data-nummer');
        });
    </script>

</body>
</html>