<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o ID do CD foi passado
if (!isset($_GET['id_cd']) || !is_numeric($_GET['id_cd'])) {
    die("ID do CD inválido.");
}

$id_cd = intval($_GET['id_cd']);

// Buscar dados do CD
$sql_cd = "SELECT * FROM CD WHERE id_cd = ?";
$stmt_cd = $conn->prepare($sql_cd);
$stmt_cd->bind_param("i", $id_cd);
$stmt_cd->execute();
$result_cd = $stmt_cd->get_result();
$cd = $result_cd->fetch_assoc();

if (!$cd) {
    die("CD não encontrado.");
}

// Buscar artistas associados ao CD
$sql_artistas_cd = "SELECT id_artista FROM CD_Artista WHERE id_cd = ?";
$stmt_artistas_cd = $conn->prepare($sql_artistas_cd);
$stmt_artistas_cd->bind_param("i", $id_cd);
$stmt_artistas_cd->execute();
$result_artistas_cd = $stmt_artistas_cd->get_result();

$artistas_selecionados = [];
while ($row = $result_artistas_cd->fetch_assoc()) {
    $artistas_selecionados[] = $row['id_artista'];
}

// Buscar músicas associadas ao CD
$sql_musicas_cd = "SELECT id_musica FROM CD_Musica WHERE id_cd = ?";
$stmt_musicas_cd = $conn->prepare($sql_musicas_cd);
$stmt_musicas_cd->bind_param("i", $id_cd);
$stmt_musicas_cd->execute();
$result_musicas_cd = $stmt_musicas_cd->get_result();

$musicas_selecionadas = [];
while ($row = $result_musicas_cd->fetch_assoc()) {
    $musicas_selecionadas[] = $row['id_musica'];
}

// Buscar todos os artistas
$sql_artistas = "SELECT id_artista, nomeArtista FROM Artista";
$result_artistas = $conn->query($sql_artistas);

// Buscar todas as músicas
$sql_musicas = "SELECT id_musica, nomeMusica FROM Musica";
$result_musicas = $conn->query($sql_musicas);
?>

<h3>Editar CD</h3>
<form action="atualizar_cd.php" method="post">
    <input type="hidden" name="id_cd" value="<?= $cd['id_cd'] ?>">

    <label>Título:</label>
    <input type="text" name="titulo" value="<?= $cd['titulo'] ?>" required><br>

    <label>Capa (URL da imagem):</label>
    <input type="text" name="capa" value="<?= $cd['capa'] ?>" required><br>

    <label>Disponibilidade:</label>
    <input type="text" name="disponibilidade" value="<?= $cd['disponibilidade'] ?>" required><br>

    <label>Preço:</label>
    <input type="number" step="0.01" name="preco" value="<?= $cd['preco'] ?>" required><br>

    <label>Destaque:</label>
    <input type="text" name="destaque" value="<?= $cd['destaque'] ?>" required><br>

    <label>Ano de Lançamento:</label>
    <input type="number" name="anoLancamento" value="<?= $cd['anoLancamento'] ?>" required><br>

    <label>Gênero:</label>
    <input type="text" name="genero" value="<?= $cd['genero'] ?>" required><br>

    <label>Descrição:</label>
    <textarea name="descricao" required><?= $cd['descricao'] ?></textarea><br>

    <label>Artistas:</label><br>
    <?php while ($artista = $result_artistas->fetch_assoc()) { ?>
        <input type="checkbox" name="artistas[]" value="<?= $artista['id_artista'] ?>"
            <?= in_array($artista['id_artista'], $artistas_selecionados) ? 'checked' : '' ?>>
        <?= $artista['nomeArtista'] ?><br>
    <?php } ?>

    <label>Músicas:</label><br>
    <?php while ($musica = $result_musicas->fetch_assoc()) { ?>
        <input type="checkbox" name="musicas[]" value="<?= $musica['id_musica'] ?>"
            <?= in_array($musica['id_musica'], $musicas_selecionadas) ? 'checked' : '' ?>>
        <?= $musica['nomeMusica'] ?><br>
    <?php } ?>

    <button type="submit">Atualizar CD</button>
</form>

<?php $conn->close(); ?>