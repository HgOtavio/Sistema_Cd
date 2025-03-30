<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consultar CDs
$sql_cd = "SELECT id_cd, titulo, capa, disponibilidade, preco, destaque, anoLancamento, genero, descricao FROM CD";
$result_cd = $conn->query($sql_cd);
?>

<h3>Gerenciar CDs</h3>
<a href="adicionar_cd.php">Adicionar Novo CD</a>
<table border="1">
    <thead>
        <tr>
            <th>ID do CD</th>
            <th>Título</th>
            <th>Capa</th>
            <th>Disponibilidade</th>
            <th>Preço</th>
            <th>Destaque</th>
            <th>Ano de Lançamento</th>
            <th>Gênero</th>
            <th>Descrição</th>
            <th>Artistas Associados</th>
            <th>Músicas Associadas</th>
            <th>Promoção (%)</th>
            <th>Preço com Desconto</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result_cd->num_rows > 0) {
            while ($cd = $result_cd->fetch_assoc()) {
                $id_cd = $cd['id_cd'];

                // Buscar os artistas associados ao CD
                $sql_artistas = "SELECT Artista.nomeArtista 
                                 FROM CD_Artista 
                                 JOIN Artista ON CD_Artista.id_artista = Artista.id_artista 
                                 WHERE CD_Artista.id_cd = $id_cd";
                $result_artistas = $conn->query($sql_artistas);

                $artistas = [];
                if ($result_artistas->num_rows > 0) {
                    while ($artista = $result_artistas->fetch_assoc()) {
                        $artistas[] = $artista['nomeArtista'];
                    }
                }
                $artistas_list = !empty($artistas) ? implode(", ", $artistas) : "Nenhum artista associado";

                // Buscar as músicas associadas ao CD
                $sql_musicas = "SELECT Musica.nomeMusica 
                                FROM CD_Musica 
                                JOIN Musica ON CD_Musica.id_musica = Musica.id_musica 
                                WHERE CD_Musica.id_cd = $id_cd";
                $result_musicas = $conn->query($sql_musicas);

                $musicas = [];
                if ($result_musicas->num_rows > 0) {
                    while ($musica = $result_musicas->fetch_assoc()) {
                        $musicas[] = $musica['nomeMusica'];
                    }
                }
                $musicas_list = !empty($musicas) ? implode(", ", $musicas) : "Nenhuma música associada";

                // Buscar o desconto aplicado
                $sql_promocao = "SELECT desconto FROM Promocao WHERE id_cd = $id_cd";
                $result_promocao = $conn->query($sql_promocao);
                $desconto = ($result_promocao->num_rows > 0) ? $result_promocao->fetch_assoc()['desconto'] : 0;

                // Calcular o preço com desconto
                $preco_original = $cd['preco'];
                $preco_desconto = $preco_original - ($preco_original * ($desconto / 100));

                echo "<tr>
                        <td>{$cd['id_cd']}</td>
                        <td>{$cd['titulo']}</td>
                        <td><img src='{$cd['capa']}' alt='Capa do CD' width='100'></td>
                        <td>{$cd['disponibilidade']}</td>
                        <td>R$ {$preco_original}</td>
                        <td>{$cd['destaque']}</td>
                        <td>{$cd['anoLancamento']}</td>
                        <td>{$cd['genero']}</td>
                        <td>{$cd['descricao']}</td>
                        <td>{$artistas_list}</td>
                        <td>{$musicas_list}</td>
                        <td>
                            <form action='puro/promocao_cd.php' method='POST'>
                                <input type='hidden' name='id_cd' value='{$id_cd}'>
                                <input type='number' name='desconto' min='0' max='100' value='{$desconto}' required> %
                                <button type='submit'>Aplicar</button>
                            </form>
                        </td>
                        <td>R$ " . number_format($preco_desconto, 2, ',', '.') . "</td>
                        <td>
                            <a href='editar_cd.php?id_cd={$cd['id_cd']}'>Editar</a> |
                            <a href='excluir_cd.php?id_cd={$cd['id_cd']}'>Excluir</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='14'>Nenhum CD encontrado</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
?>