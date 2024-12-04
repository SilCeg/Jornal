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
<html>
<head>
    <title>Administrador</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <h1>Painel do Administrador</h1>
    <?php foreach ($noticias as $noticia): ?>
        <h2><?php echo $noticia['titulo']; ?></h2>
        <p><?php echo $noticia['conteudo']; ?></p>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $noticia['id']; ?>">
            <button name="acao" value="aprovar">Aprovar</button>
            <button name="acao" value="rejeitar">Rejeitar</button>
        </form>
    <?php endforeach; ?>
</body>
</html>
