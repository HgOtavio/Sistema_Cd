<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_musica'])) {
    // Receber dados do formulário
    $id_musica = $_POST['id_musica'];
    $nomeMusica = $_POST['nomeMusica'];
    $tempo = $_POST['tempo'];
    $cds_associados = isset($_POST['cds_associados']) ? $_POST['cds_associados'] : [];
    $cds_adicionar = isset($_POST['cds_adicionar']) ? $_POST['cds_adicionar'] : [];

    // Lidar com o upload do arquivo de áudio
    $audio_path = "";
    if (isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
        $audio_tmp = $_FILES['audio']['tmp_name'];
        $audio_name = $_FILES['audio']['name'];
        $audio_path = "../audio/" . basename($audio_name);

        // Mover o arquivo para o diretório 'audio/'
        if (!move_uploaded_file($audio_tmp, $audio_path)) {
            die("Erro ao enviar o arquivo de áudio.");
        }
    } else {
        $audio_path = ""; // Caso nenhum áudio tenha sido enviado, não atualize o campo de áudio.
    }

    // Atualizar dados da música no banco de dados
    $sql_update_musica = "UPDATE Musica SET nomeMusica = ?, tempo = ?, audio = ? WHERE id_musica = ?";
    $stmt = $conn->prepare($sql_update_musica);
    $stmt->bind_param("sssi", $nomeMusica, $tempo, $audio_path, $id_musica);
    
    if ($stmt->execute()) {
        // Atualizar CDs associados
        $sql_delete_associados = "DELETE FROM CD_Musica WHERE id_musica = ?";
        $stmt = $conn->prepare($sql_delete_associados);
        $stmt->bind_param("i", $id_musica);
        $stmt->execute();

        // Inserir novos CDs associados
        foreach ($cds_associados as $cd_id) {
            $sql_insert_associado = "INSERT INTO CD_Musica (id_musica, id_cd) VALUES (?, ?)";
            $stmt = $conn->prepare($sql_insert_associado);
            $stmt->bind_param("ii", $id_musica, $cd_id);
            $stmt->execute();
        }

        // Inserir CDs adicionais
        foreach ($cds_adicionar as $cd_id) {
            $sql_insert_adicional = "INSERT INTO CD_Musica (id_musica, id_cd) VALUES (?, ?)";
            $stmt = $conn->prepare($sql_insert_adicional);
            $stmt->bind_param("ii", $id_musica, $cd_id);
            $stmt->execute();
        }

        echo "Música atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar a música.";
    }
    
    $stmt->close();
} else {
    echo "Método de requisição inválido ou ID da música não fornecido.";
}

$conn->close();
?>
