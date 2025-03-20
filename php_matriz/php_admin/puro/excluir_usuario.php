<?php
session_start();
include "../conexao.php";

// Verifica se o usuário está logado e é do tipo administrador
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "admin") {
    header("Location: ../login.php");
    exit();
}

// Verifica se o ID do usuário foi passado
if (!isset($_GET['id'])) {
    echo "Usuário não encontrado!";
    exit();
}

$id_usuario = $_GET['id'];

// Obtém as informações do usuário para excluir a foto, se existir
$result = $conn->query("SELECT foto_perfil FROM Usuario WHERE id_usuario = $id_usuario AND tipo = 'cliente'");
$usuario = $result->fetch_assoc();

if (!$usuario) {
    echo "Usuário não encontrado!";
    exit();
}

// Remove a foto de perfil do diretório, se existir
if (!empty($usuario['foto_perfil'])) {
    $caminho_foto = "../" . $usuario['foto_perfil'];
    if (file_exists($caminho_foto)) {
        unlink($caminho_foto); // Exclui o arquivo da pasta
    }
}

// Exclui o usuário do banco de dados
$sql = "DELETE FROM Usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);

if ($stmt->execute()) {
    header("Location: gerenciar_usuarios.php");
    exit();
} else {
    echo "Erro ao excluir o usuário: " . $conn->error;
}