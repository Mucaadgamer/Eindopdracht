<?php
session_start();
require_once 'dbcon.php';

$fout = '';
$user = null;

// get id from the url 
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Get the users data from the database based on the ID 
// if there is no id then the error message will appear
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user) {
        $fout = "Gebruiker met ID $id bestaat niet.";
    }
} else {
    $fout = "Geen ID opgegeven.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    // variables to store the data for the rest of the code
    $naam = trim($_POST['naam']);
    $email = trim($_POST['email']);
    $functie = $_POST['functie'];
    $nummer = trim($_POST['nummer']);

    if (empty($naam) || empty($email) || empty($nummer)) {
        $fout = "Naam, e-mail en telefoonnummer zijn verplicht.";
    } else {

        // Check if the email already exists in the database if not then proceed 
        // with the update, if it does exist and it's not the same user then show an error
        //  message that the email is already in use by another user
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check->execute([$email, $id]);

        if ($check->fetch()) {
            $fout = "Dit e-mailadres is al in gebruik door een andere gebruiker.";
        } else {

            // Wachtwoord hashed and updated if there is a new password, 
            // otherwise it just updates the other info without changing the password
            if (!empty($_POST['wachtwoord'])) {
                $hash = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
                $sql = "UPDATE users SET naam=?, email=?, functie=?, wachtwoord=?, nummer=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$naam, $email, $functie, $hash, $nummer, $id]);
            } else {
                $sql = "UPDATE users SET naam=?, email=?, functie=?, nummer=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$naam, $email, $functie, $nummer, $id]);
            }



            // Get user again to show updated info if needed
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();

            echo "<p style='color:green;'>✔ Gebruiker succesvol bijgewerkt. Je wordt over 5 seconden doorgestuurd...</p>";

            echo "<script>
                    setTimeout(function() {
                    window.location.href = 'users.php?updated=1';
                     }, 5000);
                </script>";

            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee bewerken</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="text-center w-100 m-0">Gebruiker Bewerken</h4>
    </div>

    <?php if ($fout): ?>
        <p style="color:red;"><?php echo htmlspecialchars($fout); ?></p>
    <?php endif; ?>

    <?php if ($user): ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>
                                Gebruiker Bewerken
                                <a href="users.php" class="btn btn-danger float-end">BACK</a>
                            </h4>
                        </div>

                        <div class="card-body">
                            <form method="POST">

                                <div class="mb-3">
                                    <label class="form-label">Naam</label>
                                    <input type="text" class="form-control" name="naam"
                                        value="<?php echo htmlspecialchars($user['naam']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">E-mail</label>
                                    <input type="email" class="form-control" name="email"
                                        value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Telefoonnummer</label>
                                    <input type="text" class="form-control" name="nummer"
                                        value="<?php echo htmlspecialchars($user['nummer']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Functie</label>
                                    <select name="functie" class="form-select" required>
                                        <option value="Employee" <?php echo $user['functie'] === 'Employee' ? 'selected' : ''; ?>>Employee</option>
                                        <option value="Manager" <?php echo $user['functie'] === 'Manager' ? 'selected' : ''; ?>>Manager</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nieuw wachtwoord (optioneel)</label>
                                    <input type="password" class="form-control" name="wachtwoord"
                                        placeholder="Laat leeg om niet te wijzigen">
                                </div>

                                <button type="submit" class="btn btn-success mt-3">Opslaan</button>

                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </body>

    </html>
<?php endif; ?>