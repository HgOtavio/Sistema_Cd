<?php
session_start();
include "../php/conexao.php";

// Verifica se o usuário está logado e é do tipo administrador
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "admin") {
    header("Location: ../php/login.php");
    exit();
}

// Obtém o id do usuário logado
$id_usuario = $_SESSION['id_usuario'];

// Se for um pedido de exclusão
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_artista']) && is_numeric($_POST['id_artista'])) {
    $id_artista = intval($_POST['id_artista']);
    
    // Exclui as associações na tabela CD_Artista primeiro
    $sql_delete_associacao = "DELETE FROM CD_Artista WHERE id_artista = ?";
    $stmt_delete_associacao = $conn->prepare($sql_delete_associacao);
    $stmt_delete_associacao->bind_param("i", $id_artista);
    $stmt_delete_associacao->execute();
    
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
            <th>Data de Nascimento</th>
            <th>Foto</th>
            <th>Descrição</th>
            <th>CDs Associados</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($row['id_artista']) ?></td>
                <td><?= htmlspecialchars($row['nomeArtista']) ?></td>
                <td><?= htmlspecialchars($row['dataNascimento']) ?></td>
                <td>
                    <?php if ($row['fotoPerfil']) : ?>
                        <img src="../Artista/<?= htmlspecialchars($row['fotoPerfil']) ?>" alt="Foto do Artista" width="50">
                    <?php else : ?>
                        Sem Foto
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['descricao']) ?></td>
                <td>
                    <?php
                    // Buscar CDs associados ao artista
                    $id_artista = $row['id_artista'];
                    $sql_cds = "SELECT titulo FROM CD
                                INNER JOIN CD_Artista ON CD.id_cd = CD_Artista.id_cd
                                WHERE CD_Artista.id_artista = ?";
                    $stmt_cds = $conn->prepare($sql_cds);
                    $stmt_cds->bind_param("i", $id_artista);
                    $stmt_cds->execute();
                    $result_cds = $stmt_cds->get_result();

                    // Exibir os CDs associados
                    $cds = [];
                    while ($cd = $result_cds->fetch_assoc()) {
                        $cds[] = htmlspecialchars($cd['titulo']);
                    }

                    if (count($cds) > 0) {
                        echo implode(", ", $cds); // Exibe os CDs separados por vírgula
                    } else {
                        echo "Nenhum CD associado";
                    }
                    ?>
                </td>
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