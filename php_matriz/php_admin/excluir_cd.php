<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o ID do CD foi recebido
if (!isset($_POST['id_cd']) || !is_numeric($_POST['id_cd'])) {
    die("ID do CD inválido.");
}

$id_cd = intval($_POST['id_cd']);

// Remover associações do CD com artistas
$sql_delete_artistas = "DELETE FROM CD_Artista WHERE id_cd = ?";
$stmt_delete_artistas = $conn->prepare($sql_delete_artistas);
$stmt_delete_artistas->bind_param("i", $id_cd);
$stmt_delete_artistas->execute();

// Remover associações do CD com músicas
$sql_delete_musicas = "DELETE FROM CD_Musica WHERE id_cd = ?";
$stmt_delete_musicas = $conn->prepare($sql_delete_musicas);
$stmt_delete_musicas->bind_param("i", $id_cd);
$stmt_delete_musicas->execute();

// Remover o CD
$sql_delete_cd = "DELETE FROM CD WHERE id_cd = ?";
$stmt_delete_cd = $conn->prepare($sql_delete_cd);
$stmt_delete_cd->bind_param("i", $id_cd);

if ($stmt_delete_cd->execute()) {
    echo "CD excluído com sucesso!";
} else {
    echo "Erro ao excluir o CD.";
}

// Fechar conexão e redirecionar
$conn->close();
header("Location: gerenciar_cds.php");
exit();
?>