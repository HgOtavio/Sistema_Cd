<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se foi passado um ID
if (!isset($_GET['id_artista'])) {
    die("ID do artista não informado.");
}

$id_artista = intval($_GET['id_artista']);

// Buscar dados do artista
$sql_artista = "SELECT nomeArtista FROM Artista WHERE id_artista = ?";
$stmt_artista = $conn->prepare($sql_artista);
$stmt_artista->bind_param("i", $id_artista);
$stmt_artista->execute();
$result_artista = $stmt_artista->get_result();

if ($result_artista->num_rows == 0) {
    die("Artista não encontrado.");
}

$artista = $result_artista->fetch_assoc();

// Buscar CDs associados
$sql_cds_associados = "SELECT id_cd FROM CD_Artista WHERE id_artista = ?";
$stmt_cds_associados = $conn->prepare($sql_cds_associados);
$stmt_cds_associados->bind_param("i", $id_artista);
$stmt_cds_associados->execute();
$result_cds_associados = $stmt_cds_associados->get_result();

$cds_associados = [];
while ($cd = $result_cds_associados->fetch_assoc()) {
    $cds_associados[] = $cd['id_cd'];
}

// Buscar todos os CDs disponíveis
$sql_cds = "SELECT id_cd, titulo FROM CD";
$result_cds = $conn->query($sql_cds);
?>

<h3>Editar Artista</h3>
<form action="processar_edicao_artista.php" method="post">
    <input type="hidden" name="id_artista" value="<?= $id_artista ?>">

    <label>Nome do Artista:</label>
    <input type="text" name="nomeArtista" value="<?= htmlspecialchars($artista['nomeArtista']) ?>" required>

    <h4>Associar CDs:</h4>
    <?php while ($cd = $result_cds->fetch_assoc()) : ?>
        <input type="checkbox" name="cds[]" value="<?= $cd['id_cd'] ?>" 
            <?= in_array($cd['id_cd'], $cds_associados) ? 'checked' : '' ?>>
        <?= htmlspecialchars($cd['titulo']) ?><br>
    <?php endwhile; ?>

    <br>
    <button type="submit">Salvar Alterações</button>
    <a href="gerenciar_artistas.php">Cancelar</a>
</form>

<?php
$conn->close();
?>