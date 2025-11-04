<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    
    if (empty($login) || empty($password)) {
        $_SESSION['message'] = 'Все поля обязательны для заполнения!!!';
        $_SESSION['message_type'] = 'error';
        header('Location: index.php');
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['login'] = $user['login'];
            
            $_SESSION['message'] = 'Вы успешно вошли в систему!!!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Неверный логин или пароль';
            $_SESSION['message_type'] = 'error';
        }
        
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Ошибка при входе: ' . $e->getMessage();
        $_SESSION['message_type'] = 'error';
    }
    
    header('Location: index.php');
    exit;
}
?>