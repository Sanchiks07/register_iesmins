<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="monkey.css">
    <script src="validator.js" defer></script>
</head>
<body>
<body>
    <div class="container">
        <h2>Register</h2>

        <?php
            session_start();
            $errors = $_SESSION['errors'] ?? [];
            $form_data = $_SESSION['form_data'] ?? [];
            unset($_SESSION['errors'], $_SESSION['form_data']);
        ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?php echo $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <br>

        <form action="register.php" method="POST" onsubmit="return validateForm()">
            <div class="input-group">
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>" required>
                <label>First Name</label>
                <?php if (isset($errors['first_name'])): ?>
                    <span class="php_error"><?php echo $errors['first_name']; ?></span>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>" required>
                <label>Last Name</label>
                <?php if (isset($errors['last_name'])): ?>
                    <span class="php_error"><?php echo $errors['last_name']; ?></span>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <input type="text" name="email" value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
                <label>Email</label>
                <?php if (isset($errors['email'])): ?>
                    <span class="php_error"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
            </div>

            <div class="password-container">
                <div class="monkey">
                    <div class="ears"></div>
                    <div class="face">
                        <div class="eyes">
                            <div class="eye left"></div>
                            <div class="eye right"></div>
                            <div class="hand left-hand"></div>
                            <div class="hand right-hand"></div>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                <input type="password" name="password" id="password" required>                                                                                                                                            
                    <label>Password</label>
                    <span class="toggle-password">
                        <i class="eye-icon" id="togglePassword">👁️</i>
                        <span class="tooltip">Can I take a peek?</span>
                    </span>
                    <?php if (isset($errors['password'])): ?>
                        <span class="php_error"><?php echo $errors['password']; ?></span>
                    <?php endif; ?>
                </div>
            </div>


          <button type="submit" class="btn">Register</button>
        </form>
    </div>

    <script src="monkey.js"></script>
</body>
</html>