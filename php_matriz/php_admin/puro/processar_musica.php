<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Receber os dados do formulário
$nomeMusica = $_POST['nomeMusica'];
$tempo = $_POST['tempo'];
$id_cd = $_POST['id_cd'];

// Inserir a música na tabela Musica
$sql_musica = "INSERT INTO Musica (nomeMusica, tempo) VALUES ('$nomeMusica', '$tempo')";
if ($conn->query($sql_musica) === TRUE) {
    // Pegar o id da nova música inserida
    $id_musica = $conn->insert_id;

    // Associar a música ao CD na tabela CD_Musica
    $sql_associar_cd = "INSERT INTO CD_Musica (id_cd, id_musica) VALUES ('$id_cd', '$id_musica')";
    if ($conn->query($sql_associar_cd) === TRUE) {
        echo "Música adicionada e associada ao CD com sucesso!";
    } else {
        echo "Erro ao associar a música ao CD: " . $conn->error;
    }
} else {
    echo "Erro ao adicionar música: " . $conn->error;
}

$conn->close();
?>