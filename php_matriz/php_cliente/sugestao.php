<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "cliente") {
    header("Location: ../php/login.php");
    exit();
}

// Conexão com o banco de dados
include "../php/conexao.php";

// Obtém o ID do usuário logado
$id_usuario = $_SESSION["id_usuario"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $titulo = $_POST['titulo'];
    $genero = $_POST['genero'];
    $anoLancamento = $_POST['anoLancamento'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $capa = $_FILES['capa']['name'];
    $capa_tmp = $_FILES['capa']['tmp_name'];

    // Salva a imagem (capa) na pasta de imagens
    if ($capa) {
        $capa_path = "../imagens/" . $capa;
        move_uploaded_file($capa_tmp, $capa_path);
    }

    // Prepara a consulta para salvar a sugestão
    $id_usuario = $_SESSION["id_usuario"];
    $sql = "INSERT INTO Sugestoes (id_usuario, titulo, genero, anoLancamento, preco, descricao, capa) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiiss", $id_usuario, $titulo, $genero, $anoLancamento, $preco, $descricao, $capa);
    
    if ($stmt->execute()) {
        echo "<p>Sugestão enviada com sucesso!</p>";
    } else {
        echo "<p>Ocorreu um erro ao enviar sua sugestão. Tente novamente.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugerir CD</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; }
        .container { width: 50%; margin: 0 auto; padding: 20px; background-color: white; border-radius: 10px; }
        h1 { text-align: center; }
        label { display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { background-color: #ff8c00; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px; }
        button:hover { background-color: #e67300; }
    </style>
</head>
<body>

<div class="container">
    <h1>Sugerir um Novo CD</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" required>

        <label for="genero">Gênero:</label>
        <input type="text" name="genero" id="genero">

        <label for="anoLancamento">Ano de Lançamento:</label>
        <input type="number" name="anoLancamento" id="anoLancamento" min="1900" max="2099">

        <label for="preco">Preço:</label>
        <input type="number" name="preco" id="preco" step="0.01" required>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" rows="4" required></textarea>

        <label for="capa">Capa do CD (opcional):</label>
        <input type="file" name="capa" id="capa" accept="image/*">

        <button type="submit">Enviar Sugestão</button>
    </form>
    <a href="ver_sugestoes.php"> sugestao</a>
</div>



</body>
</html>