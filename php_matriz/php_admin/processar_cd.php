<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Receber os dados do formulário
$titulo = $_POST['titulo'];
$disponibilidade = $_POST['disponibilidade'];
$preco = $_POST['preco'];
$destaque = $_POST['destaque'];
$anoLancamento = $_POST['anoLancamento'];
$genero = $_POST['genero'];
$descricao = $_POST['descricao'];

// Verificar se já existe um CD com o mesmo título
$sql_verificar_cd = "SELECT * FROM CD WHERE titulo = '$titulo'";
$result_cd = $conn->query($sql_verificar_cd);

if ($result_cd->num_rows > 0) {
    // Se existir, avise ao usuário que o CD já existe
    echo "Erro: Já existe um CD com o título '$titulo'.";
} else {
    // Verificar se a imagem foi carregada
    $capa = $_FILES['capa']['name'];
    $capa_tmp = $_FILES['capa']['tmp_name'];
    $capa_path = "../imagens/" . $capa;

    // Mover a imagem para a pasta de uploads
    move_uploaded_file($capa_tmp, $capa_path);

    // Inserir o CD na tabela CD
    $sql_cd = "INSERT INTO CD (titulo, capa, disponibilidade, preco, destaque, anoLancamento, genero, descricao)
               VALUES ('$titulo', '$capa_path', '$disponibilidade', '$preco', '$destaque', '$anoLancamento', '$genero', '$descricao')";
    if ($conn->query($sql_cd) === TRUE) {
        $id_cd = $conn->insert_id;

        // Associar os artistas ao CD
        if (!empty($_POST['artistas'])) {
            foreach ($_POST['artistas'] as $id_artista) {
                $sql_artista = "INSERT INTO CD_Artista (id_cd, id_artista) VALUES ('$id_cd', '$id_artista')";
                $conn->query($sql_artista);
            }
        }

        // Associar as músicas ao CD
        if (!empty($_POST['musicas'])) {
            foreach ($_POST['musicas'] as $id_musica) {
                $sql_musica = "INSERT INTO CD_Musica (id_cd, id_musica) VALUES ('$id_cd', '$id_musica')";
                $conn->query($sql_musica);
            }
        }

        echo "CD adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar CD: " . $conn->error;
    }
}

$conn->close();
?>