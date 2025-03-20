<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se o ID da música foi passado
if (isset($_GET['id_musica'])) {
    $id_musica = $_GET['id_musica'];

    // Buscar dados da música
    $sql_musica = "SELECT * FROM Musica WHERE id_musica = $id_musica";
    $result_musica = $conn->query($sql_musica);

    if ($result_musica->num_rows > 0) {
        $musica = $result_musica->fetch_assoc();
    } else {
        die("Música não encontrada.");
    }

    // Buscar CDs já associados à música
    $sql_cds_associados = "SELECT CD.id_cd, CD.titulo FROM CD_Musica 
                           JOIN CD ON CD_Musica.id_cd = CD.id_cd 
                           WHERE CD_Musica.id_musica = $id_musica";
    $result_cds_associados = $conn->query($sql_cds_associados);
    $cds_associados = [];
    while ($cd = $result_cds_associados->fetch_assoc()) {
        $cds_associados[] = $cd;
    }

    // Buscar todos os CDs disponíveis
    $sql_cds = "SELECT id_cd, titulo FROM CD";
    $result_cds = $conn->query($sql_cds);
    $cds_disponiveis = [];
    while ($cd = $result_cds->fetch_assoc()) {
        $cds_disponiveis[] = $cd;
    }
} else {
    die("ID da música não fornecido.");
}
?>

<h3>Editar Música</h3>
<form action="salvar_edicao_musica.php" method="post">
    <input type="hidden" name="id_musica" value="<?php echo $musica['id_musica']; ?>">

    <label for="nomeMusica">Nome da Música:</label>
    <input type="text" name="nomeMusica" value="<?php echo $musica['nomeMusica']; ?>" required><br><br>

    <label for="tempo">Duração (em minutos):</label>
    <input type="text" name="tempo" value="<?php echo $musica['tempo']; ?>" required><br><br>

    <label for="cds_associados">CDs Associados:</label><br>
    <?php
    if (count($cds_associados) > 0) {
        echo "<ul>";
        foreach ($cds_associados as $cd) {
            echo "<li>{$cd['titulo']} <input type='checkbox' name='cds_associados[]' value='{$cd['id_cd']}' checked></li>";
        }
        echo "</ul>";
    } else {
        echo "Nenhum CD associado.";
    }
    ?>

    <label for="adicionar_cds">Adicionar CDs:</label><br>
    <?php
    if (count($cds_disponiveis) > 0) {
        echo "<ul>";
        foreach ($cds_disponiveis as $cd) {
            echo "<li>{$cd['titulo']} <input type='checkbox' name='cds_adicionar[]' value='{$cd['id_cd']}'></li>";
        }
        echo "</ul>";
    }
    ?>

    <button type="submit">Salvar Alterações</button>
</form>

<?php
$conn->close();
?>