<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se foi passado um ID
if (!isset($_GET['id_artista']) || !is_numeric($_GET['id_artista'])) {
    die("ID do artista não informado ou inválido.");
}

$id_artista = intval($_GET['id_artista']);

// Buscar dados do artista
$sql_artista = "SELECT * FROM Artista WHERE id_artista = ?";
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artista</title>
</head>
<body>

<h3>Editar Artista</h3>

<form action="processar_edicao_artista.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_artista" value="<?= $id_artista ?>">

    <label>Nome do Artista:</label>
    <input type="text" name="nomeArtista" value="<?= htmlspecialchars($artista['nomeArtista']) ?>" required><br><br>

    <label>Data de Nascimento:</label>
    <input type="date" name="dataNascimento" value="<?= htmlspecialchars($artista['dataNascimento']) ?>" required><br><br>

    <label>Foto de Perfil:</label>
    <input type="file" name="fotoPerfil"><br><br>
    <?php if ($artista['fotoPerfil']) : ?>
        <p>Foto atual: <img src="../Artista/<?= htmlspecialchars($artista['fotoPerfil']) ?>" alt="Foto do Artista" width="100"></p>
    <?php endif; ?>

    <label>Descrição:</label><br>
    <textarea name="descricao" rows="4" cols="50"><?= htmlspecialchars($artista['descricao']) ?></textarea><br><br>

    <h4>Associar CDs:</h4>
    <?php while ($cd = $result_cds->fetch_assoc()) : ?>
        <input type="checkbox" name="cds[]" value="<?= $cd['id_cd'] ?>" 
            <?= in_array($cd['id_cd'], $cds_associados) ? 'checked' : '' ?>>
        <?= htmlspecialchars($cd['titulo']) ?><br>
    <?php endwhile; ?>

    <br>
    <button type="submit">Salvar Alterações</button>
    <a href="gerenciar_artista.php">Cancelar</a>
</form>

</body>
</html>

<?php
$conn->close();
?>