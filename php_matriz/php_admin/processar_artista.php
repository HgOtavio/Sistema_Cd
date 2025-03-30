<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeArtista = trim($_POST["nomeArtista"]);
    $descricao = trim($_POST["descricao"]);
    $dataNascimento = $_POST["dataNascimento"];
    $cds = isset($_POST["cds"]) ? $_POST["cds"] : [];
    $fotoPerfil = $_FILES["fotoPerfil"];

    // Verificar se o artista já existe
    $sql_verifica = "SELECT id_artista FROM Artista WHERE nomeArtista = ?";
    $stmt = $conn->prepare($sql_verifica);
    $stmt->bind_param("s", $nomeArtista);
    $stmt->execute();
    $result = $stmt->get_result();
    $id_artista = null;

    if ($result->num_rows > 0) {
        // Artista já existe, pega o ID
        $row = $result->fetch_assoc();
        $id_artista = $row["id_artista"];
    } else {
        // Inserir novo artista
        $sql_insere_artista = "INSERT INTO Artista (nomeArtista, descricao, dataNascimento) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql_insere_artista);
        $stmt->bind_param("sss", $nomeArtista, $descricao, $dataNascimento);
        if ($stmt->execute()) {
            $id_artista = $stmt->insert_id; // Pega o ID do novo artista
        }
    }

    // Verificar e fazer upload da foto de perfil
    if ($fotoPerfil['error'] == 0) {
        $upload_dir = '../Artista/';
        $upload_file = $upload_dir . basename($fotoPerfil['name']);
        
        // Verifica se é uma imagem válida
        $imageFileType = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($fotoPerfil['tmp_name'], $upload_file)) {
                // Atualizar o caminho da foto no banco de dados
                $sql_update_foto = "UPDATE Artista SET fotoPerfil = ? WHERE id_artista = ?";
                $stmt = $conn->prepare($sql_update_foto);
                $stmt->bind_param("si", $upload_file, $id_artista);
                $stmt->execute();
            } else {
                echo "Erro ao fazer upload da foto.";
            }
        } else {
            echo "Arquivo não é uma imagem válida.";
        }
    }

    // Associar o artista aos CDs selecionados
    if (!empty($cds)) {
        $sql_associa = "INSERT INTO CD_Artista (id_cd, id_artista) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_associa);
        foreach ($cds as $id_cd) {
            $stmt->bind_param("ii", $id_cd, $id_artista);
            if (!$stmt->execute()) {
                echo "Erro ao associar artista ao CD.";
                break;
            }
        }
    }

    // Mensagem de sucesso ou erro
    if ($id_artista) {
        echo "Artista adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar o artista.";
    }
}

$conn->close();
?>