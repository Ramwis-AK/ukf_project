<?php
// Definuj, že prístup do DB je povolený (ak to vyžaduje tvoj db_config)
define('DB_ACCESS_ALLOWED', true);

// Spusti session
session_start();

// Include database configuration
require_once '../config/db_config.php';

// Premenné na ukladanie vstupov a chýb
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Spracovanie formulára
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Overenie používateľského mena
    if (empty(trim($_POST["username"]))) {
        $username_err = "Prosím zadajte používateľské meno.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Overenie hesla
    if (empty(trim($_POST["password"]))) {
        $password_err = "Prosím zadajte heslo.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Ak nie sú chyby, over používateľa
    if (empty($username_err) && empty($password_err)) {
        $conn = get_db_connection();

        if (!$conn) {
            die("Nepodarilo sa pripojiť k databáze.");
        }

        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $username_db, $hashed_password, $role);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Heslo správne - nastav session premenné
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $username_db;
                            $_SESSION["role"] = $role;

                            // Presmeruj podľa role (napr. admin na admin panel)
                            if ($role === 'admin') {
                                header("location: admin/index.php");
                            } else {
                                header("location: index.php");
                            }
                            exit;
                        } else {
                            $login_err = "Nesprávne meno alebo heslo.";
                        }
                    }
                } else {
                    $login_err = "Nesprávne meno alebo heslo.";
                }
            } else {
                echo "Ups! Niečo sa pokazilo. Skúste neskôr.";
            }

            $stmt->close();
        }

        $conn->close();
    }
}
?>



<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prihlásenie - Gorm</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-form-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .login-links {
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login-form-container">
        <h2 class="text-center mb-4">Prihlásenie</h2>

        <?php
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Používateľské meno</label>
                <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Heslo</label>
                <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" name="password">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary w-100">Prihlásiť sa</button>
            </div>

            <div class="login-links">
                <p>Nemáte účet? <a href="register.php">Registrujte sa tu</a>.</p>
                <p><a href="../index.php">Späť na domovskú stránku</a></p>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>