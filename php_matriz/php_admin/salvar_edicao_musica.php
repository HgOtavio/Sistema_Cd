<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se os dados foram enviados
if (isset($_POST['id_musica']) && isset($_POST['nomeMusica']) && isset($_POST['tempo'])) {
    $id_musica = $_POST['id_musica'];
    $nomeMusica = $_POST['nomeMusica'];
    $tempo = $_POST['tempo'];

    // Atualizar dados da música
    $sql_update = "UPDATE Musica SET nomeMusica = ?, tempo = ? WHERE id_musica = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssi", $nomeMusica, $tempo, $id_musica);

    if ($stmt->execute()) {
        // Remover CDs não selecionados
        if (isset($_POST['cds_associados'])) {
            $cds_associados = $_POST['cds_associados'];
            $sql_remove_cds = "DELETE FROM CD_Musica WHERE id_musica = ? AND id_cd NOT IN (" . implode(",", array_map('intval', $cds_associados)) . ")";
            $stmt_remove = $conn->prepare($sql_remove_cds);
            $stmt_remove->bind_param("i", $id_musica);
            $stmt_remove->execute();
        }

        // Adicionar novos CDs
        if (isset($_POST['cds_adicionar'])) {
            $cds_adicionar = $_POST['cds_adicionar'];
            foreach ($cds_adicionar as $id_cd) {
                $sql_add_cd = "INSERT INTO CD_Musica (id_musica, id_cd) VALUES (?, ?)";
                $stmt_add = $conn->prepare($sql_add_cd);
                $stmt_add->bind_param("ii", $id_musica, $id_cd);
                $stmt_add->execute();
            }
        }

        echo "Música atualizada com sucesso.";
        header("Location: gerenciar_musicas.php"); // Redireciona para a página de gerenciamento de músicas
    } else {
        echo "Erro ao atualizar a música: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Dados incompletos.";
}

$conn->close();
?>