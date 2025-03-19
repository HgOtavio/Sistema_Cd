<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "cliente") {
    header("Location: ../php/login.php");
    exit();
}

// Conexão com o banco de dados
include "../php/puro/conexao.php";

// Obtém o ID do usuário logado
$id_usuario = $_SESSION["id_usuario"];

// Verifica se foi enviado um arquivo de imagem
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
    // Definir o diretório de destino para o upload
    $diretorio = 'uploads/';
    $extensao = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);

    // Gerar um nome único para o arquivo
    $nome_arquivo = uniqid('foto_') . '.' . $extensao;

    // Verifica se a extensão do arquivo é permitida (você pode remover essa parte, se desejar aceitar qualquer extensão)
    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff'];
    if (in_array($extensao, $extensoes_permitidas)) {
        // Move o arquivo para a pasta uploads
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $diretorio . $nome_arquivo)) {
            // Atualiza o caminho da foto no banco de dados
            $id_usuario = $_SESSION["id_usuario"];
            $sql = "UPDATE Usuario SET foto_perfil = ? WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $nome_arquivo, $id_usuario);
            if ($stmt->execute()) {
                header("Location: index.php"); // Redireciona para a página principal
                exit();
            } else {
                echo "Erro ao atualizar a foto de perfil no banco de dados.";
            }
            $stmt->close();
        } else {
            echo "Erro ao fazer upload da imagem. Verifique as permissões da pasta de upload.";
        }
    } else {
        echo "A extensão do arquivo não é permitida. Somente as imagens JPG, JPEG, PNG, GIF, BMP, WEBP ou TIFF são aceitas.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Foto de Perfil</title>
</head>
<body>
    <h2>Escolha sua Foto de Perfil</h2>
    <!-- Formulário para enviar a foto -->
    <form action="upload_foto.php" method="post" enctype="multipart/form-data">
        <label for="foto_perfil">Escolher foto:</label>
        <input type="file" name="foto_perfil" id="foto_perfil" required><br><br>
        <button type="submit">Enviar Foto</button>
    </form>

    <a href="index.php">Voltar para o painel</a>
</body>
</html>