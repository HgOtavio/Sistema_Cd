<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "cliente") {
    header("Location: ../login.php");
    exit();
}

include "../conexao.php";

$id_usuario = $_SESSION["id_usuario"];

// Consulta para buscar os CDs favoritos do usuário
$sql = "SELECT CD.id_cd, CD.titulo, CD.capa, CD.disponibilidade, CD.preco
        FROM Favoritos
        INNER JOIN CD ON Favoritos.id_cd = CD.id_cd
        WHERE Favoritos.id_usuario = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoritos</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; }
        .container { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-top: 50px; }
        .cd { background: white; padding: 10px; border-radius: 10px; width: 250px; text-align: center; }
        .cd img { width: 100%; border-radius: 10px; }
        .remove-btn { margin-top: 10px; background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Seus CDs Favoritos</h1>
    
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='cd'>";
                echo "<img src='../" . ($row['capa'] ? $row['capa'] : 'sem-capa.jpg') . "' alt='Capa do CD'>";
                echo "<h3>" . $row['titulo'] . "</h3>";
                echo "<p>Preço: R$ " . number_format($row['preco'], 2, ',', '.') . "</p>";
                
                if ($row['disponibilidade'] > 0) {
                    echo "<p style='color: green;'>Disponível</p>";
                } else {
                    echo "<p style='color: red;'>Esgotado</p>";
                }

                // Botão para remover dos favoritos
                echo "<form action='remover_favorito.php' method='POST'>
                        <input type='hidden' name='id_cd' value='{$row['id_cd']}'>
                        <button type='submit' class='remove-btn'>Remover dos Favoritos</button>
                      </form>";
                
                echo "</div>";
            }
        } else {
            echo "<p style='text-align: center;'>Você ainda não possui CDs favoritos.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="cds.php">Voltar para a Loja</a>
    </div>
</body>
</html>