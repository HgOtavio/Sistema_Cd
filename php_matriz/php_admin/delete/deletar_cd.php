<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Se for um pedido de exclusão (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cd']) && is_numeric($_POST['id_cd'])) {
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
        echo "<script>alert('CD excluído com sucesso!'); window.location.href='gerenciar_cds.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir o CD.'); window.location.href='gerenciar_cds.php';</script>";
    }

    $conn->close();
    exit();
}

// Se for um pedido de exibição (GET)
if (isset($_GET['id_cd']) && is_numeric($_GET['id_cd'])) {
    $id_cd = intval($_GET['id_cd']);

    // Buscar dados do CD para exibição
    $sql_cd = "SELECT titulo, capa FROM CD WHERE id_cd = ?";
    $stmt_cd = $conn->prepare($sql_cd);
    $stmt_cd->bind_param("i", $id_cd);
    $stmt_cd->execute();
    $result_cd = $stmt_cd->get_result();
    $cd = $result_cd->fetch_assoc();

    if (!$cd) {
        die("CD não encontrado.");
    }
} else {
    die("ID do CD inválido.");
}
?>

<h3>Excluir CD</h3>
<p>Tem certeza de que deseja excluir o CD <strong><?= htmlspecialchars($cd['titulo']) ?></strong>?</p>
<img src="<?= htmlspecialchars($cd['capa']) ?>" alt="Capa do CD" width="100"><br><br>

<form action="deletar_cd.php" method="post">
    <input type="hidden" name="id_cd" value="<?= $id_cd ?>">
    <button type="submit">Confirmar Exclusão</button>
    <a href="gerenciar_cds.php">Cancelar</a>
</form>

<?php $conn->close(); ?>