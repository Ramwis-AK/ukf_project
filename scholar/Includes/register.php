<?php
define('DB_ACCESS_ALLOWED', true);
session_start();
require_once __DIR__ . '/../config/db_config.php';

// Vytvor si inštanciu Database
$db = Database::getInstance();

$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Používateľské meno
    if (empty(trim($_POST["username"]))) {
        $username_err = "Prosím zadajte používateľské meno.";
    } elseif (strlen(trim($_POST["username"])) < 3) {
        $username_err = "Používateľské meno musí mať aspoň 3 znaky.";
    } else {
        $username = Database::sanitizeInput(trim($_POST["username"]));

        try {
            // Tu už voláme na inštancii, nie staticky
            $result = $db->select("SELECT user_ID FROM users WHERE username = ?", [$username]);
            if ($result && count($result) > 0) {
                $username_err = "Toto používateľské meno už existuje.";
            }
        } catch (Exception $e) {
            error_log("Username check error: " . $e->getMessage());
        }
    }

    // Email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Prosím zadajte email.";
    } else {
        $email = trim($_POST["email"]);
        if (!Database::validateEmail($email)) {
            $email_err = "Neplatný formát emailu.";
        } else {
            try {
                $result = $db->select("SELECT user_ID FROM users WHERE email = ?", [$email]);
                if ($result && count($result) > 0) {
                    $email_err = "Tento email už je registrovaný.";
                }
            } catch (Exception $e) {
                error_log("Email check error: " . $e->getMessage());
            }
        }
    }

    // Heslo
    if (empty(trim($_POST["password"]))) {
        $password_err = "Prosím zadajte heslo.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Heslo musí mať aspoň 6 znakov.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Potvrdenie hesla
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Prosím potvrďte heslo.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Heslá sa nezhodujú.";
        }
    }

    // Ak je všetko OK, vlož používateľa
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        try {
            // hashPassword pravdepodobne statická utilita
            $hashed_password = Database::hashPassword($password);

            $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
            $result = $db->insert($sql, [$username, $email, $hashed_password]);

            if ($result) {
                $_SESSION['success_message'] = "Registrácia bola úspešná. Môžete sa prihlásiť.";
                header("location: login.php");
                exit;
            } else {
                echo "Ups! Niečo sa pokazilo pri registrácii.";
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            echo "Ups! Niečo sa pokazilo. Skúste neskôr.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrácia - Scholar</title>

    <!-- Bootstrap CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        .register-form-container {
            max-width: 450px;
            margin: 0 auto;
            padding: 40px 30px;
            background: white;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }
        .register-links {
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
    <div class="register-form-container">
        <h2 class="text-center">Registrácia</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Používateľské meno</label>
                <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Heslo</label>
                <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Potvrďte heslo</label>
                <input type="password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary w-100">Registrovať sa</button>
            </div>

            <div class="register-links">
                <p>Už máte účet? <a href="login.php">Prihláste sa tu</a>.</p>
                <p><a href="../index.php">Späť na domovskú stránku</a></p>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>