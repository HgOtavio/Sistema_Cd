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
<form action="processar_artista.php" method="post">
    <label for="nomeArtista">Nome do Artista:</label>
    <input type="text" name="nomeArtista" required>

    <label for="id_cd">Associar a um CD:</label>
    <select name="id_cd" required>
        <option value="">Selecione um CD</option>
        <?php
        while ($cd = $result_cd->fetch_assoc()) {
            echo "<option value='{$cd['id_cd']}'>{$cd['titulo']}</option>";
        }
        ?>
    </select>

    <button type="submit">Adicionar Artista</button>
</form>

<?php $conn->close(); ?>