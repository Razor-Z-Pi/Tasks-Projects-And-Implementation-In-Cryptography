<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $username = trim($_POST['username']);
    
    if (empty($login) || empty($password) || empty($username)) {
        $_SESSION['message'] = 'Все поля обязательны для заполнения!!!';
        $_SESSION['message_type'] = 'error';
        header('Location: index.php');
        exit;
    }
    
    if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'Некорректный формат email!!!';
        $_SESSION['message_type'] = 'error';
        header('Location: index.php');
        exit;
    }
    

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->execute([$login]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = 'Пользователь с таким логином уже существует!!!';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php');
            exit;
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (login, password, username) VALUES (?, ?, ?)");
        $stmt->execute([$login, $hashedPassword, $username]);
        
        $_SESSION['message'] = 'Регистрация прошла успешно!!! Теперь вы можете войти.';
        $_SESSION['message_type'] = 'success';
        
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Ошибка при регистрации: ' . $e->getMessage();
        $_SESSION['message_type'] = 'error';
    }
    
    header('Location: index.php');
    exit;
}
?>