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

// Verificar se a música já existe no banco (prevenir duplicação)
$sql_verifica = "SELECT id_musica FROM Musica WHERE nomeMusica = ?";
$stmt_verifica = $conn->prepare($sql_verifica);
$stmt_verifica->bind_param("s", $nomeMusica);
$stmt_verifica->execute();
$stmt_verifica->store_result();

if ($stmt_verifica->num_rows > 0) {
    die("Erro: Música já cadastrada!");
}
$stmt_verifica->close();

// Verificar se a pasta de áudio existe, caso contrário, cria
if (!is_dir("audio")) {
    mkdir("audio", 0777, true); // Cria a pasta 'audio' com permissões adequadas
}

// Verificar se um arquivo de áudio foi enviado
if (isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
    $audio_tmp = $_FILES['audio']['tmp_name'];
    $audio_nome = basename($_FILES['audio']['name']);
    $audio_destino = "../audio/" . $audio_nome;

    // Tentar mover o arquivo para a pasta de áudio
    if (!move_uploaded_file($audio_tmp, $audio_destino)) {
        die("Erro ao mover o arquivo para 'audio/'. Verifique permissões.");
    }
} else {
    $audio_destino = NULL;
}

// Inserir a música na tabela Musica
$sql_musica = "INSERT INTO Musica (nomeMusica, tempo, audio) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql_musica);
$stmt->bind_param("sds", $nomeMusica, $tempo, $audio_destino);

if ($stmt->execute()) {
    // Pegar o id da nova música inserida
    $id_musica = $stmt->insert_id;

    // Associar a música ao CD na tabela CD_Musica
    $sql_associar_cd = "INSERT INTO CD_Musica (id_cd, id_musica) VALUES (?, ?)";
    $stmt_cd = $conn->prepare($sql_associar_cd);
    $stmt_cd->bind_param("ii", $id_cd, $id_musica);

    if ($stmt_cd->execute()) {
        echo "Música adicionada e associada ao CD com sucesso!";
    } else {
        echo "Erro ao associar a música ao CD: " . $conn->error;
    }
} else {
    echo "Erro ao adicionar música: " . $conn->error;
}

$stmt->close();
$conn->close();
?>