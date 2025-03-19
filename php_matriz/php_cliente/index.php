<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "cliente") {
    header("Location: ../php/login.php");
    exit();
}

// Conexão com o banco de dados
include "../php/puro/conexao.php";

// Obtém o ID do usuário logado
$id_usuario = $_SESSION["id_usuario"];

// Busca o caminho da foto de perfil do banco de dados
$sql = "SELECT foto_perfil FROM Usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$stmt->bind_result($foto_perfil);
$stmt->fetch();
$stmt->close();



// Verifica se o usuário tem uma foto de perfil definida, caso contrário, usa uma imagem padrão
$foto_exibida = $foto_perfil ? $foto_perfil : 'default-avatar.png';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Cliente</title>
</head>
<body>
    <h2>Bem-vindo ao Painel do Cliente</h2>
    <ul>
    <li><a href="editar_cliente.php">Editar Meus Dados</a></li>
    <li><a href="listar_cds.php">Comprar CDs</a></li>
    <li><a href="carrinho_view.php">carrinho</a></li>
    <li><a href="favoritos.php">favoritos</a></li>
    <li><a href="sugestao.php">Sugerir novos cds</a></li>
    <li><a href="historico_de_compra.php">Histórico de Compras</a></li> <!-- Botão adicionado -->
    <li><a href="../logout.php">Sair</a></li>
</ul>

    <!-- Exibe a foto de perfil -->
    <div>
        <h3>Foto de Perfil</h3>
        <img src="uploads/<?php echo $foto_exibida; ?>" alt="Foto de perfil" style="width: 150px; height: 150px; border-radius: 50%;">
    </div>
</body>
</html>