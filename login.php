<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = md5($_POST['senha']); // Use o mesmo método de hash usado no banco de dados

    // Verificar o usuário no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['tipo'] = $usuario['tipo'];

        // Redirecionar com base no tipo de usuário
        if ($usuario['tipo'] == 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: escritor.php');
        }
        exit;
    } else {
        $erro = "Credenciais inválidas. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            width: 300px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($erro)) echo "<p class='error'>$erro</p>"; ?>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
