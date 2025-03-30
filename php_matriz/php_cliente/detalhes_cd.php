<?php
session_start();
include "../php/conexao.php";

// Verifica se o parâmetro 'id_cd' foi passado na URL
if (isset($_GET['id_cd'])) {
    $id_cd = $_GET['id_cd'];

    // Consulta para obter os detalhes do CD
    $sql_cd = "SELECT id_cd, titulo, capa, preco, anoLancamento, genero, descricao FROM CD WHERE id_cd = ?";
    $stmt_cd = $conn->prepare($sql_cd);
    $stmt_cd->bind_param('i', $id_cd);
    $stmt_cd->execute();
    $result_cd = $stmt_cd->get_result();

    if ($result_cd->num_rows > 0) {
        $cd = $result_cd->fetch_assoc();
    } else {
        echo "<p>CD não encontrado.</p>";
        exit();
    }

    // Consulta para obter as músicas associadas ao CD
    $sql_musicas = "SELECT m.id_musica, m.nomeMusica FROM Musica m
                    JOIN CD_Musica cm ON m.id_musica = cm.id_musica
                    WHERE cm.id_cd = ?";
    $stmt_musicas = $conn->prepare($sql_musicas);
    $stmt_musicas->bind_param('i', $id_cd);
    $stmt_musicas->execute();
    $result_musicas = $stmt_musicas->get_result();

    $musicas = [];
    while ($musica = $result_musicas->fetch_assoc()) {
        $musicas[] = $musica;
    }

    // Consulta para obter os artistas associados ao CD
    $sql_artistas = "SELECT a.id_artista, a.nomeArtista FROM Artista a
                     JOIN CD_Artista ca ON a.id_artista = ca.id_artista
                     WHERE ca.id_cd = ?";
    $stmt_artistas = $conn->prepare($sql_artistas);
    $stmt_artistas->bind_param('i', $id_cd);
    $stmt_artistas->execute();
    $result_artistas = $stmt_artistas->get_result();

    $artistas = [];
    while ($artista = $result_artistas->fetch_assoc()) {
        $artistas[] = $artista;
    }
} else {
    echo "<p>ID do CD não especificado.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do CD</title>
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
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
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

        .detalhes-container .preco {
            color: #007BFF;
            font-weight: bold;
            font-size: 22px;
            margin: 20px 0;
        }

        .musicas-lista {
            margin-top: 20px;
        }

        .musicas-lista ul {
            list-style-type: none;
            padding: 0;
        }

        .musicas-lista li {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .artistas-lista {
            margin-top: 20px;
        }

        .artistas-lista ul {
            list-style-type: none;
            padding: 0;
        }

        .artistas-lista li {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
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
        <h2>Detalhes do CD: <?php echo $cd['titulo']; ?></h2>
        <img src="../<?php echo $cd['capa']; ?>" alt="Capa do CD">
        <p><strong>Ano de Lançamento:</strong> <?php echo $cd['anoLancamento']; ?></p>
        <p><strong>Gênero:</strong> <?php echo $cd['genero']; ?></p>
        <p><strong>Descrição:</strong> <?php echo $cd['descricao']; ?></p>
        <p class="preco">Preço: R$ <?php echo number_format($cd['preco'], 2, ',', '.'); ?></p>

        <div class="musicas-lista">
            <h3>Músicas:</h3>
            <ul>
                <?php foreach ($musicas as $musica): ?>
                    <li><?php echo $musica['nomeMusica']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="artistas-lista">
            <h3>Artistas:</h3>
            <ul>
                <?php foreach ($artistas as $artista): ?>
                    <li><a href="detalhes_artista.php?id_artista=<?php echo $artista['id_artista']; ?>"><?php echo $artista['nomeArtista']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <a href="listar_cds.php" class="voltar">Voltar à lista de CDs</a>
    </div>

</body>
</html>