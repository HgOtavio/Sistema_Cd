<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se um ID de artista foi passado
if (isset($_GET['id_artista']) && is_numeric($_GET['id_artista'])) {
    $id_artista = intval($_GET['id_artista']);

    // Remover associações do artista com CDs
    $sql_delete_associacoes = "DELETE FROM CD_Artista WHERE id_artista = ?";
    $stmt_delete_associacoes = $conn->prepare($sql_delete_associacoes);
    $stmt_delete_associacoes->bind_param("i", $id_artista);
    $stmt_delete_associacoes->execute();
    $stmt_delete_associacoes->close();

    // Excluir o artista
    $sql_delete_artista = "DELETE FROM Artista WHERE id_artista = ?";
    $stmt_delete_artista = $conn->prepare($sql_delete_artista);
    $stmt_delete_artista->bind_param("i", $id_artista);
    $stmt_delete_artista->execute();
    $stmt_delete_artista->close();

    // Fechar conexão e redirecionar
    $conn->close();
    header("Location: gerenciar_artistas.php");
    exit();
} else {
    echo "ID de artista inválido.";
}
?>