<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeArtista = trim($_POST["nomeArtista"]);
    $id_cd = $_POST["id_cd"];

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
        $sql_insere_artista = "INSERT INTO Artista (nomeArtista) VALUES (?)";
        $stmt = $conn->prepare($sql_insere_artista);
        $stmt->bind_param("s", $nomeArtista);
        if ($stmt->execute()) {
            $id_artista = $stmt->insert_id; // Pega o ID do novo artista
        }
    }

    // Verifica se conseguiu um ID de artista válido
    if ($id_artista) {
        // Associar artista ao CD na tabela CD_Artista
        $sql_associa = "INSERT INTO CD_Artista (id_cd, id_artista) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_associa);
        $stmt->bind_param("ii", $id_cd, $id_artista);
        if ($stmt->execute()) {
            echo "Artista adicionado e associado ao CD com sucesso!";
        } else {
            echo "Erro ao associar artista ao CD.";
        }
    } else {
        echo "Erro ao adicionar artista.";
    }
}
$conn->close();
?>