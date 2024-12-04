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
    $imagem = null;

    // Processar upload da imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeImagem = uniqid() . '.' . $extensao;
        $caminhoImagem = 'uploads/' . $nomeImagem;

        // Mover a imagem para a pasta "uploads"
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem)) {
            $imagem = $caminhoImagem;
        }
    }

    // Inserir notícia no banco de dados
    $stmt = $pdo->prepare("INSERT INTO noticias (titulo, conteudo, imagem, autor_id) VALUES (:titulo, :conteudo, :imagem, :autor_id)");
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':conteudo', $conteudo);
    $stmt->bindParam(':imagem', $imagem);
    $stmt->bindParam(':autor_id', $autor_id);
    $stmt->execute();
}

$query = $pdo->prepare("SELECT * FROM noticias WHERE autor_id = :autor_id AND status = 'pendente'");
$query->bindParam(':autor_id', $_SESSION['usuario_id']);
$query->execute();
$noticias = $query->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Escritor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
            cursor: pointer;
        }
        .noticias {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .noticia {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .noticia:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, Escritor</h1>
        <form method="POST">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>
            <label for="conteudo">Conteúdo:</label>
            <textarea id="conteudo" name="conteudo" rows="5" required></textarea>
            <button type="submit">Enviar</button>
        </form>

        <div class="noticias">
            <h2>Notícias Pendentes</h2>
            <?php foreach ($noticias as $noticia): ?>
                <div class="noticia">
                    <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($noticia['conteudo'])); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
