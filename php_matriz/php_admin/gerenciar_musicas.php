<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consultar Músicas (incluindo tempo/duração)
$sql_musica = "SELECT id_musica, nomeMusica, tempo FROM Musica";
$result_musica = $conn->query($sql_musica);
?>

<h3>Gerenciar Músicas</h3>
<a href="adicionar_musica.php">Adicionar Nova Música</a>
<table border="1">
    <thead>
        <tr>
            <th>ID da Música</th>
            <th>Nome da Música</th>
            <th>Duração</th>
            <th>CDs Associados</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result_musica->num_rows > 0) {
            while ($musica = $result_musica->fetch_assoc()) {
                $id_musica = $musica['id_musica'];

                // Buscar os CDs associados a esta música
                $sql_cds = "SELECT CD.titulo 
                            FROM CD_Musica 
                            JOIN CD ON CD_Musica.id_cd = CD.id_cd 
                            WHERE CD_Musica.id_musica = ?";
                $stmt_cds = $conn->prepare($sql_cds);
                $stmt_cds->bind_param("i", $id_musica);
                $stmt_cds->execute();
                $result_cds = $stmt_cds->get_result();

                // Montar lista de CDs
                $cds = [];
                while ($cd = $result_cds->fetch_assoc()) {
                    $cds[] = $cd['titulo'];
                }
                $cds_list = !empty($cds) ? implode(", ", $cds) : "Nenhum CD associado";

                // Exibir música com botões de editar e excluir
                echo "<tr>
                        <td>{$musica['id_musica']}</td>
                        <td>{$musica['nomeMusica']}</td>
                        <td>" . number_format($musica['tempo'], 2) . " minutos</td>
                        <td>{$cds_list}</td>
                        <td>
                            <a href='editar_musica.php?id_musica={$musica['id_musica']}'>Editar</a> |
                            <a href='deletar_musica.php?id_musica={$musica['id_musica']}'
                               onclick='return confirm(\"Tem certeza que deseja excluir esta música e suas associações de CDs?\");'>
                               Excluir</a>
                        </td>
                      </tr>";

                $stmt_cds->close();
            }
        } else {
            echo "<tr><td colspan='5'>Nenhuma Música encontrada</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
?>