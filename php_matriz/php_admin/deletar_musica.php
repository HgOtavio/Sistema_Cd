<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se o ID da música foi passado por POST ou GET
if (isset($_GET['id_musica'])) {
    $id_musica = $_GET['id_musica'];

    // Remover as associações com os CDs da tabela CD_Musica
    $sql_associacao = "DELETE FROM CD_Musica WHERE id_musica = ?";
    $stmt_associacao = $conn->prepare($sql_associacao);
    $stmt_associacao->bind_param("i", $id_musica);
    if (!$stmt_associacao->execute()) {
        die("Erro ao remover associações com os CDs: " . $conn->error);
    }
    $stmt_associacao->close();

    // Remover a música da tabela Musica
    $sql_musica = "DELETE FROM Musica WHERE id_musica = ?";
    $stmt_musica = $conn->prepare($sql_musica);
    $stmt_musica->bind_param("i", $id_musica);
    if ($stmt_musica->execute()) {
        echo "Música e suas associações com CDs removidas com sucesso!";
    } else {
        die("Erro ao excluir música: " . $conn->error);
    }
    $stmt_musica->close();
} else {
    echo "ID da música não fornecido!";
}

$conn->close();
?>