<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'escritor') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $autor_id = $_SESSION['usuario_id'];

    $stmt = $pdo->prepare("INSERT INTO noticias (titulo, conteudo, autor_id) VALUES (:titulo, :conteudo, :autor_id)");
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':conteudo', $conteudo);
    $stmt->bindParam(':autor_id', $autor_id);
    $stmt->execute();
}

$query = $pdo->prepare("SELECT * FROM noticias WHERE autor_id = :autor_id AND status = 'pendente'");
$query->bindParam(':autor_id', $_SESSION['usuario_id']);
$query->execute();
$noticias = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Escritor</title>
</head>
<body>
    <h1>Bem-vindo, Escritor</h1>
    <form method="POST">
        <label>Título:</label>
        <input type="text" name="titulo" required>
        <label>Conteúdo:</label>
        <textarea name="conteudo" required></textarea>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
