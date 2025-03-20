<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar artistas existentes
$sql_artistas = "SELECT id_artista, nomeArtista FROM Artista";
$result_artistas = $conn->query($sql_artistas);

// Buscar músicas existentes
$sql_musicas = "SELECT id_musica, nomeMusica FROM Musica";
$result_musicas = $conn->query($sql_musicas);
?>

<h3>Adicionar Novo CD</h3>
<form action="processar_cd.php" method="post" enctype="multipart/form-data">
    <label for="titulo">Título do CD:</label>
    <input type="text" name="titulo" required>

    <label for="capa">Capa do CD (imagem):</label>
    <input type="file" name="capa" accept="image/*" required>

    <label for="disponibilidade">Disponibilidade:</label>
    <select name="disponibilidade" required>
        <option value="Disponível">Disponível</option>
        <option value="Indisponível">Indisponível</option>
    </select>

    <label for="preco">Preço:</label>
    <input type="number" name="preco" step="0.01" min="0" required>

    <label for="destaque">Destaque:</label>
    <select name="destaque" required>
        <option value="Sim">Sim</option>
        <option value="Não">Não</option>
    </select>

    <label for="anoLancamento">Ano de Lançamento:</label>
    <input type="number" name="anoLancamento" min="1900" max="2100" required>

    <label for="genero">Gênero:</label>
    <input type="text" name="genero" required>

    <label for="descricao">Descrição:</label>
    <textarea name="descricao" rows="4" required></textarea>

    <label for="artistas">Artistas Associados:</label>
    <select name="artistas[]" multiple required>
        <?php
        while ($artista = $result_artistas->fetch_assoc()) {
            echo "<option value='{$artista['id_artista']}'>{$artista['nomeArtista']}</option>";
        }
        ?>
    </select>

    <label for="musicas">Músicas Associadas:</label>
    <select name="musicas[]" multiple required>
        <?php
        while ($musica = $result_musicas->fetch_assoc()) {
            echo "<option value='{$musica['id_musica']}'>{$musica['nomeMusica']}</option>";
        }
        ?>
    </select>

    <button type="submit">Adicionar CD</button>
</form>

<?php
$conn->close();
?>