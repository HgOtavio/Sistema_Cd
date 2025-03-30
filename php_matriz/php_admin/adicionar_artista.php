<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar CDs existentes
$sql_cd = "SELECT id_cd, titulo FROM CD";
$result_cd = $conn->query($sql_cd);
?>

<h3>Adicionar Novo Artista</h3>
<form action="processar_artista.php" method="post" enctype="multipart/form-data">
    <label for="nomeArtista">Nome do Artista:</label>
    <input type="text" name="nomeArtista" required>

    <label for="descricao">Descrição do Artista:</label>
    <textarea name="descricao" required></textarea>

    <label for="dataNascimento">Data de Nascimento:</label>
    <input type="date" name="dataNascimento" required>

    <label for="fotoPerfil">Foto de Perfil:</label>
    <input type="file" name="fotoPerfil" accept="image/*">

    <label for="cds">Associar a CDs:</label>
    <select name="cds[]" multiple required>
        <option value="">Selecione um ou mais CDs</option>
        <?php
        while ($cd = $result_cd->fetch_assoc()) {
            echo "<option value='{$cd['id_cd']}'>{$cd['titulo']}</option>";
        }
        ?>
    </select>

    <button type="submit">Adicionar Artista</button>
</form>

<?php $conn->close(); ?>