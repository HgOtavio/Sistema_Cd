<?php
session_start();
include "../php/conexao.php";

// Verifica se o parâmetro 'id_artista' foi passado na URL
if (isset($_GET['id_artista'])) {
    $id_artista = $_GET['id_artista'];

    // Consulta para obter os detalhes do artista
    $sql = "SELECT * FROM Artista WHERE id_artista = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_artista);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $artista = $result->fetch_assoc();
    } else {
        echo "<p>Artista não encontrado.</p>";
        exit();
    }
} else {
    echo "<p>ID do artista não especificado.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Artista</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .detalhes-container {
            width: 80%;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .detalhes-container img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            color: #007BFF;
        }

        .detalhes-container p {
            font-size: 18px;
            color: #333;
        }

        .voltar {
            display: block;
            text-align: center;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .voltar:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="detalhes-container">
        <h2>Detalhes do Artista: <?php echo $artista['nomeArtista']; ?></h2>
        <img src="../<?php echo $artista['fotoPerfil']; ?>" alt="Foto do Artista">
        <p><strong>Data de Nascimento:</strong> <?php echo date('d/m/Y', strtotime($artista['dataNascimento'])); ?></p>
        <p><strong>Descrição:</strong> <?php echo nl2br($artista['descricao']); ?></p>
        <a href="listar_cds.php" class="voltar">Voltar à lista de CDs</a>
    </div>

</body>
</html>