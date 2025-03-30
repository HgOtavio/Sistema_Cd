<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "cliente") {
    header("Location: ../php/login.php");
    exit();
}

// Conexão com o banco de dados
include "../php/conexao.php";

// Obtém o ID do usuário logado
$id_usuario = $_SESSION["id_usuario"];

// Obtém o ID do usuário logado
$id_usuario = $_SESSION["id_usuario"];
$id_carrinho = $_POST['id_carrinho'];

$sql = "DELETE FROM Carrinho WHERE id = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_carrinho, $id_usuario);
$stmt->execute();

header("Location: carrinho_view.php");
exit();
?>