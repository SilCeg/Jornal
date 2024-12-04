<?php
include 'conexao.php';

// Obter notícias aprovadas
$query = $pdo->query("SELECT * FROM noticias WHERE status = 'aprovada' ORDER BY data_publicacao DESC");
$noticias = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            justify-content: flex-end; /* Alinha o botão à direita */
            padding: 20px;
            background-color: #f8f9fa; /* Fundo claro para o cabeçalho */
            border-bottom: 1px solid #ddd;
        }

        .login-button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .login-button:hover {
            background-color: #0056b3;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .noticia {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <a href="login.php" class="login-button">Login</a>
    </header>
    <div class="container">
        <h1>Últimas Notícias</h1>
        
        <?php foreach ($noticias as $noticia): ?>
            <div class="noticia">
                <h2><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($noticia['conteudo'])); ?></p>
                <small>Publicado em: <?php echo date('d/m/Y H:i', strtotime($noticia['data_publicacao'])); ?></small>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
