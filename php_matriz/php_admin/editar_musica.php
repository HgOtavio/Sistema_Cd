<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se o ID da música foi passado
if (isset($_GET['id_musica'])) {
    $id_musica = intval($_GET['id_musica']); // Garantir que o id_musica seja um número inteiro

    // Buscar dados da música
    $sql_musica = "SELECT * FROM Musica WHERE id_musica = ?";
    $stmt = $conn->prepare($sql_musica);
    $stmt->bind_param("i", $id_musica);
    $stmt->execute();
    $result_musica = $stmt->get_result();

    if ($result_musica->num_rows > 0) {
        $musica = $result_musica->fetch_assoc();
    } else {
        die("Música não encontrada.");
    }

    // Buscar CDs já associados à música
    $sql_cds_associados = "SELECT CD.id_cd, CD.titulo FROM CD_Musica 
                           JOIN CD ON CD_Musica.id_cd = CD.id_cd 
                           WHERE CD_Musica.id_musica = ?";
    $stmt = $conn->prepare($sql_cds_associados);
    $stmt->bind_param("i", $id_musica);
    $stmt->execute();
    $result_cds_associados = $stmt->get_result();
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
<form action="salvar_edicao_musica.php" method="post" enctype="multipart/form-data">
    <!-- Passar o id_musica no formulário -->
    <input type="hidden" name="id_musica" value="<?php echo $id_musica; ?>">

    <label for="nomeMusica">Nome da Música:</label>
    <input type="text" name="nomeMusica" value="<?php echo isset($musica['nomeMusica']) ? $musica['nomeMusica'] : ''; ?>" required><br><br>

    <label for="tempo">Duração (em minutos):</label>
    <input type="text" name="tempo" value="<?php echo isset($musica['tempo']) ? $musica['tempo'] : ''; ?>" required><br><br>

    <label for="audio">Áudio:</label>
    <input type="file" name="audio" accept="audio/mp3, audio/wav"><br><br> <!-- Restringindo a tipos de áudio -->

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