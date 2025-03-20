<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Se for um pedido de exclusão
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_artista']) && is_numeric($_POST['id_artista'])) {
    $id_artista = intval($_POST['id_artista']);
    
    // Exclui o artista da tabela Artista
    $sql_delete = "DELETE FROM Artista WHERE id_artista = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id_artista);
    if ($stmt_delete->execute()) {
        echo "<div id='mensagem' class='sucesso'>Artista excluído com sucesso!</div>";
    } else {
        echo "<div id='mensagem' class='erro'>Erro ao excluir o artista.</div>";
    }
}

// Busca todos os artistas
$sql = "SELECT * FROM Artista";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Artistas</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        #mensagem {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px;
            border-radius: 5px;
            color: white;
            z-index: 1000;
        }
        .sucesso {
            background-color: green;
        }
        .erro {
            background-color: red;
        }
    </style>
</head>
<body>
    <h2>Gerenciar Artistas</h2>
    <a href="adicionar_artista.php">Adicionar Novo Artista</a><br><br>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($row['id_artista']) ?></td>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td>
                    <form action="gerenciar_artista.php" method="post" style="display:inline;">
                        <input type="hidden" name="id_artista" value="<?= $row['id_artista'] ?>">
                        <button type="submit">Excluir</button>
                    </form>
                    <a href="editar_artista.php?id_artista=<?= $row['id_artista'] ?>">Editar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        setTimeout(function() {
            const mensagem = document.getElementById('mensagem');
            if (mensagem) mensagem.style.display = 'none';
        }, 3000);
    </script>
</body>
</html>

<?php $conn->close(); ?>