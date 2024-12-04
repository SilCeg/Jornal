<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $acao = $_POST['acao'];

    $status = $acao === 'aprovar' ? 'aprovada' : 'rejeitada';

    $stmt = $pdo->prepare("UPDATE noticias SET status = :status, data_publicacao = NOW() WHERE id = :id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

$query = $pdo->query("SELECT * FROM noticias WHERE status = 'pendente'");
$noticias = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador</title>
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
        .noticia {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .noticia h2 {
            margin-top: 0;
            color: #007BFF;
        }
        .noticia p {
            margin-bottom: 15px;
        }
        form {
            display: flex;
            gap: 10px;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        button[name="acao"][value="aprovar"] {
            background-color: #28a745;
            color: white;
        }
        button[name="acao"][value="aprovar"]:hover {
            background-color: #218838;
        }
        button[name="acao"][value="rejeitar"] {
            background-color: #dc3545;
            color: white;
        }
        button[name="acao"][value="rejeitar"]:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Painel do Administrador</h1>
        <?php foreach ($noticias as $noticia): ?>
            <div class="noticia">
                <h2><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($noticia['conteudo'])); ?></p>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $noticia['id']; ?>">
                    <button name="acao" value="aprovar">Aprovar</button>
                    <button name="acao" value="rejeitar">Rejeitar</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
