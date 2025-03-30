<?php
session_start();
include "../conexao.php";

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "cliente") {
    header("Location: ../login.php");
    exit();
}

$id_carrinho = $_POST['id_carrinho'];
$quantidade = $_POST['quantidade'];

$sql = "UPDATE Carrinho SET quantidade = ? WHERE id = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $quantidade, $id_carrinho, $_SESSION["id_usuario"]);
$stmt->execute();

header("Location: carrinho_view.php");
exit();
?>