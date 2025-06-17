<?php
// Definuj, že prístup do DB je povolený
define('DB_ACCESS_ALLOWED', true);

// Spusti session
session_start();

// Include database configuration - opravená cesta
require_once __DIR__ . '/../config/db_config.php';

// Premenné na ukladanie vstupov a chýb
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Spracovanie formulára
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Overenie používateľského mena
    if (empty(trim($_POST["username"]))) {
        $username_err = "Prosím zadajte používateľské meno.";
    } else {
        $username = DatabaseConnection::sanitizeInput(trim($_POST["username"]));
    }

    // Overenie hesla
    if (empty(trim($_POST["password"]))) {
        $password_err = "Prosím zadajte heslo.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Ak nie sú chyby, over používateľa
    if (empty($username_err) && empty($password_err)) {
        try {
            $db = getDB();

            $sql = "SELECT user_ID, username, password, role FROM users WHERE username = ?";
            $result = $db->select($sql, [$username]);

            if ($result && count($result) > 0) {
                $user = $result[0];

                if (DatabaseConnection::verifyPassword($password, $user['password'])) {
                    // Heslo správne - nastav session premenné
                    $_SESSION["loggedin"] = true;
                    $_SESSION["user_id"] = $user['user_ID'];
                    $_SESSION["username"] = $user['username'];
                    $_SESSION["role"] = $user['role'];

                    // Presmeruj podľa role
                    if ($user['role'] === 'admin') {
                        header("location: ../admin.php");
                    } else {
                        header("location: ../index.php");
                    }
                    exit;
                } else {
                    $login_err = "Nesprávne meno alebo heslo.";
                }
            } else {
                $login_err = "Nesprávne meno alebo heslo.";
            }
        } catch (Exception $e) {
            $login_err = "Chyba pri prihlasovaní. Skúste neskôr.";
            error_log("Login error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prihlásenie - Scholar</title>

    <!-- Bootstrap CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 40px 30px;
            background: white;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }
        .login-links {
            margin-top: 20px;
            text-align: center;
        }
        .btn-primary {
            background-color: #667eea;
            border-color: #667eea;
            padding: 12px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #764ba2;
            border-color: #764ba2;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login-form-container">
        <h2 class="text-center">Prihlásenie</h2>

        <?php
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        if(isset($_SESSION['success_message'])){
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Používateľské meno</label>
                <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Heslo</label>
                <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
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
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>