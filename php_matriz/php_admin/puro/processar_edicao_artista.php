<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Receber dados do formulário
$id_artista = intval($_POST['id_artista']);
$nomeArtista = trim($_POST['nomeArtista']);
$cds = isset($_POST['cds']) ? $_POST['cds'] : [];

// Atualizar nome do artista
$sql_update = "UPDATE Artista SET nomeArtista = ? WHERE id_artista = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("si", $nomeArtista, $id_artista);
$stmt_update->execute();
$stmt_update->close(); // Fechando a declaração para liberar memória

// Remover associações antigas de CDs
$sql_delete = "DELETE FROM CD_Artista WHERE id_artista = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("i", $id_artista);
$stmt_delete->execute();
$stmt_delete->close(); // Fechando a declaração para liberar memória

// Adicionar novas associações de CDs (se houver)
if (!empty($cds)) {
    $sql_insert = "INSERT INTO CD_Artista (id_artista, id_cd) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    foreach ($cds as $id_cd) {
        $stmt_insert->bind_param("ii", $id_artista, $id_cd);
        $stmt_insert->execute();
    }
    $stmt_insert->close(); // Fechando a declaração para liberar memória
}

// Fechar conexão antes de redirecionar
$conn->close();

// Redirecionar para a página de gerenciamento
header("Location: gerenciar_artistas.php");
exit();
?>