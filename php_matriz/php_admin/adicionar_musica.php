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

<h3>Adicionar Nova Música</h3>
<form action="processar_musica.php" method="post">
    <label for="nomeMusica">Nome da Música:</label>
    <input type="text" name="nomeMusica" required>

    <label for="tempo">Duração (em minutos):</label>
    <input type="number" name="tempo" step="0.01" min="0" required>

    <label for="id_cd">Associar a um CD:</label>
    <select name="id_cd" required>
        <option value="">Selecione um CD</option>
        <?php
        while ($cd = $result_cd->fetch_assoc()) {
            echo "<option value='{$cd['id_cd']}'>{$cd['titulo']}</option>";
        }
        ?>
    </select>

    <button type="submit">Adicionar Música</button>
</form>

<?php $conn->close(); ?>