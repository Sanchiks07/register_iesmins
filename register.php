<?php
session_start();

$dsn = "mysql:host=localhost;dbname=register;charset=utf8mb4";
$username = "root";
$password = "root";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $errors = [];
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($first_name)) {
            $errors['first_name'] = "First name is required!";
        } elseif (!preg_match("/^[A-Za-zĀ-ž\s]+$/", $first_name)) {
            $errors['first_name'] = "First name should contain only letters!";
        }

        if (empty($last_name)) {
            $errors['last_name'] = "Last name is required!";
        } elseif (!preg_match("/^[A-Za-zĀ-ž\s]+$/", $last_name)) {
            $errors['last_name'] = "Last name should contain only letters!";
        }

        if (empty($email)) {
            $errors['email'] = "Email is required!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format!";
        } else {
            $checkEmail = $pdo->prepare("SELECT email FROM users WHERE email = :email");
            $checkEmail->execute([':email' => $email]);
            if ($checkEmail->fetch()) {
                $errors['email'] = "This email is already registered!";
            }
        }

        if (empty($password)) {
            $errors['password'] = "Password is required!";
        } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*-_]).{9,}$/", $password)) {
            $errors['password'] = "Password must be at least 9 characters, contain uppercase & lowercase letters, a number, and a special character!";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST; // Store input values to repopulate form
            header("Location: index.php");
            exit();
        }

        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        $_SESSION['success'] = "Registration Successful!";
        header("Location: index.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database Error: " . $e->getMessage();
    header("Location: index.php");
    exit();
}
?>