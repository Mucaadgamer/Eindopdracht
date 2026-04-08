<!-- Start PHP session and include database connection  -->
<?php
session_start();
require_once 'dbcon.php';

// Handle deleting a user when user_id is posted 
if (isset($_POST['user_id'])) {
    $id_to_delete = $_POST['user_id'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id_to_delete]);

    header("Location: Users.php");
    exit();
}

// Pagination setup: limit, current page, offset 
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalStmt = $pdo->query("SELECT COUNT(*) FROM users");
$totalUsers = $totalStmt->fetchColumn();
$totalPages = ceil($totalUsers / $limit);

$stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $limit OFFSET $offset");
$stmt->execute();
$Users = $stmt->fetchAll();

// Handle search terms if there are any
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

<!-- html setup with Bootstrap and css links -->
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

    <!-- Company logo -->
    <img src="../IMG/Bluepeak_Technologies.png" alt="Bluepeak Technologies">

    <h2>Alle gebruikers</h2>

    <!-- Search bar, back button, and add user button -->
    <div class="test">
        <form action="" method="get">
            <input type="text" name="zoekterm" placeholder="Zoeken">
            <button type="submit">Zoek</button>
        </form>

        <button onclick="location.href='../index.php'" class="btn btn-secondary"
            style="margin-right: 10px;">Back</button>

        <button class="btn btn-success" onclick="location.href='employeeCreate.php'">
            Add User
        </button>
    </div>

    <!-- Display message when no users exist -->
    <?php if (empty($Users)): ?>
        <p>Er zijn geen Users</p>
    <?php else: ?>

        <!-- Table displaying all users -->
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
                        <td><?= htmlspecialchars($User['id']); ?></td>
                        <td><?= htmlspecialchars($User['naam']); ?></td>
                        <td><?= htmlspecialchars($User['email']); ?></td>
                        <td><?= htmlspecialchars($User['functie']); ?></td>
                        <td><?= htmlspecialchars($User['nummer']); ?></td>

                        <!-- Edit button that opens the update modal -->
                        <td>
                            <button class="btn btn-primary" onclick="location.href='edit.php?id=<?= $User['id']; ?>'">
                                Edit
                            </button>
                        </td>

                        <!-- Delete button with confirmation -->
                        <td>
                            <form class="form2" action="Users.php" method="POST">
                                <input type="hidden" name="user_id" value="<?= $User['id']; ?>">
                                <button type="submit" name="delete_btn"
                                    onclick="return confirm('Weet je zeker dat je deze user wilt verwijderen?')"
                                    class="btn btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>



    <!-- Pagination navigation -->
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

    <!-- JavaScript to populate update modal with selected user's data this is for the bootstrap that where using -->
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