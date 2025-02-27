<?php
$dsn = "mysql:host=localhost;dbname=register;charset=utf8mb4";
$username = "root";  
$password = "root";  

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        // $hash_password = password_hash($password, PASSWORD_DEFAULT);

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            echo "<script>alert('All fields are required!'); window.location.href='index.php';</script>";
            exit();
        }

        // validation
        function validateForm($first_name, $last_name, $email, $password) {
    
        if (!preg_match("/^[A-Za-zĀ-ž\s]+$/", $first_name) || !preg_match("/^[A-Za-zĀ-ž\s]+$/", $last_name)) {
            return "First name and Last name should contain only letters!";
        }

        if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
            return "Invalid email address!";
        }

        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*-_]).{9,}$/", $password)) {
            return "Password must be at least 9 characters long, contain an uppercase and a lowercase letter, a number, and a special symbol!";
        }
    
        return true;
        }

        $validationResult = validateForm($first_name, $last_name, $email, $password);
        if ($validationResult !== true) {
            echo "<script>alert('$validationResult'); window.location.href='index.php';</script>";
            exit();
        }

        $checkEmail = $pdo->prepare("SELECT email FROM users WHERE email = :email");
        $checkEmail->execute([':email' => $email]);
        if ($checkEmail->fetch()) {
            echo "<script>alert('This email is already registered!'); window.location.href='index.php';</script>";
            exit();
        }

        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT) // $hash_passwordb
        ]);

        echo "<script>alert('Registration Successful!'); window.location.href='index.php';</script>";
    }

} catch (PDOException $e) {
    echo "<script>alert('Database Error: " . $e->getMessage() . "'); window.location.href='index.php';</script>";
}
?>
