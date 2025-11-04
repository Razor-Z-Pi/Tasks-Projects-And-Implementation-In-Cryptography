<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация и вход</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./normalize.css">
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать!!!</h1>
        
        <?php if (isset($_SESSION['username'])): ?>
            <div class="welcome-message">
                <h2>Привет, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <a href="logout.php" class="btn">Выйти</a>
            </div>
        <?php else: ?>
            <div class="forms-container">
                <!-- Форма регистрации -->
                <div class="form-container">
                    <h2>Регистрация</h2>
                    <form id="registerForm" action="register.php" method="POST">
                        <div class="form-group">
                            <label for="reg-login">Логин (email):</label>
                            <input type="email" id="reg-login" name="login" required>
                        </div>
                        <div class="form-group">
                            <label for="reg-password">Пароль:</label>
                            <input type="password" id="reg-password" name="password" required>
                            <input class="btn btn-pass" type="button" class="password-control" onclick="return show_hide_register(this);" value="Показать пароль"/>
                        </div>
                        <div class="form-group">
                            <label for="reg-username">Имя пользователя:</label>
                            <input type="text" id="reg-username" name="username" required>
                        </div>
                        <button type="submit" class="btn">Зарегистрироваться</button>
                    </form>
                </div>

                <!-- Форма входа -->
                <div class="form-container">
                    <h2>Вход</h2>
                    <form id="loginForm" action="login.php" method="POST">
                        <div class="form-group">
                            <label for="login-login">Логин (email):</label>
                            <input type="email" id="login-login" name="login" required>
                        </div>
                        <div class="form-group">
                            <label for="login-password">Пароль:</label>
                            <input type="password" id="login-password" name="password" required>
                            <input class="btn btn-pass" type="button" class="password-control" onclick="return show_hide_password(this);" value="Показать пароль"/>
                        </div>
                        <button type="submit" class="btn">Войти</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['message_type']; ?>">
                <?php 
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="./app.js"></script>
</body>
</html>