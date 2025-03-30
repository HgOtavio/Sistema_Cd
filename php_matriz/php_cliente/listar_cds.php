<?php
session_start();
include "../php/conexao.php";

// Consulta para obter todos os CDs com nome do artista e disponibilidade
$sql = "
    SELECT 
        CD.id_cd, 
        CD.titulo, 
        CD.capa, 
        CD.preco, 
        CD.disponibilidade, 
        GROUP_CONCAT(nomeArtista ORDER BY nomeArtista ASC) AS artistas
    FROM CD
    LEFT JOIN CD_Artista ON CD.id_cd = CD_Artista.id_cd
    LEFT JOIN Artista ON CD_Artista.id_artista = Artista.id_artista
    GROUP BY CD.id_cd
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar CDs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin: 30px 0;
            color: #007BFF;
        }

        .cd-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .cd-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            padding: 10px;
            text-align: center;
        }

        .cd-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        .cd-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .cd-card p {
            color: #555;
            font-size: 14px;
        }

        .cd-card .preco {
            color: #007BFF;
            font-weight: bold;
            margin: 10px 0;
        }

        .cd-card .btn {
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }

        .cd-card .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h2>Todos os CDs Disponíveis</h2>

    <div class="cd-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='cd-card'>";
                echo "<img src='../" . $row['capa'] . "' alt='Capa do CD'>";
                echo "<h3>" . $row['titulo'] . "</h3>";
                echo "<p><strong>Artistas:</strong> " . $row['artistas'] . "</p>";
                echo "<p><strong>Preço:</strong> R$ " . number_format($row['preco'], 2, ',', '.') . "</p>";
                echo "<p><strong>Disponibilidade:</strong> " . ($row['disponibilidade'] > 0 ? "Disponível" : "Indisponível") . "</p>";
                echo "<a href='detalhes_cd.php?id_cd=" . $row['id_cd'] . "' class='btn'>Ver Mais</a>";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhum CD disponível no momento.</p>";
        }
        ?>
    </div>

</body>
</html>