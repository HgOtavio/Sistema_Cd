<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Receber dados do formulário
$id_cd = intval($_POST['id_cd']);
$titulo = trim($_POST['titulo']);
$capa = trim($_POST['capa']);
$disponibilidade = trim($_POST['disponibilidade']);
$preco = floatval($_POST['preco']);
$destaque = trim($_POST['destaque']);
$anoLancamento = intval($_POST['anoLancamento']);
$genero = trim($_POST['genero']);
$descricao = trim($_POST['descricao']);
$artistas = isset($_POST['artistas']) ? $_POST['artistas'] : [];
$musicas = isset($_POST['musicas']) ? $_POST['musicas'] : [];

// Atualizar dados do CD
$sql_update_cd = "UPDATE CD SET titulo = ?, capa = ?, disponibilidade = ?, preco = ?, destaque = ?, anoLancamento = ?, genero = ?, descricao = ? WHERE id_cd = ?";
$stmt_update_cd = $conn->prepare($sql_update_cd);
$stmt_update_cd->bind_param("sssdsissi", $titulo, $capa, $disponibilidade, $preco, $destaque, $anoLancamento, $genero, $descricao, $id_cd);
$stmt_update_cd->execute();

// Remover associações antigas
$sql_delete_artistas = "DELETE FROM CD_Artista WHERE id_cd = ?";
$stmt_delete_artistas = $conn->prepare($sql_delete_artistas);
$stmt_delete_artistas->bind_param("i", $id_cd);
$stmt_delete_artistas->execute();

$sql_delete_musicas = "DELETE FROM CD_Musica WHERE id_cd = ?";
$stmt_delete_musicas = $conn->prepare($sql_delete_musicas);
$stmt_delete_musicas->bind_param("i", $id_cd);
$stmt_delete_musicas->execute();

// Adicionar novas associações
$sql_insert_artistas = "INSERT INTO CD_Artista (id_cd, id_artista) VALUES (?, ?)";
$stmt_insert_artistas = $conn->prepare($sql_insert_artistas);
foreach ($artistas as $id_artista) {
    $stmt_insert_artistas->bind_param("ii", $id_cd, $id_artista);
    $stmt_insert_artistas->execute();
}

$sql_insert_musicas = "INSERT INTO CD_Musica (id_cd, id_musica) VALUES (?, ?)";
$stmt_insert_musicas = $conn->prepare($sql_insert_musicas);
foreach ($musicas as $id_musica) {
    $stmt_insert_musicas->bind_param("ii", $id_cd, $id_musica);
    $stmt_insert_musicas->execute();
}

// Fechar conexão e redirecionar
$conn->close();
header("Location: gerenciar_cds.php");
exit();
?>