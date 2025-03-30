<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "cliente") {
    header("Location: ../login.php");
    exit();
}

include "../conexao.php";

$id_usuario = $_SESSION["id_usuario"];
$id_cd = $_POST['id_cd'];

// Verificar se o CD já está nos favoritos
$sql_verificar = "SELECT * FROM Favoritos WHERE id_usuario = ? AND id_cd = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("ii", $id_usuario, $id_cd);
$stmt_verificar->execute();
$result = $stmt_verificar->get_result();

if ($result->num_rows > 0) {
    // O CD já foi favoritado
    $_SESSION['mensagem'] = "Este CD já está nos seus favoritos.";
} else {
    // Adicionar CD aos favoritos
    $sql = "INSERT INTO Favoritos (id_usuario, id_cd) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_cd);
    $stmt->execute();
    $_SESSION['mensagem'] = "CD adicionado aos favoritos com sucesso!";
}

$stmt_verificar->close();
$conn->close();

header("Location: cds.php");
exit();