<?php
session_start();
include "../php/conexao.php";

// Verifica se o usuário está logado e é do tipo administrador
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "admin") {
    header("Location: ../php/login.php");
    exit();
}

// Obtém os filtros da URL (caso existam)
$filtro_titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';
$filtro_genero = isset($_GET['genero']) ? $_GET['genero'] : '';
$filtro_ano = isset($_GET['ano']) ? $_GET['ano'] : '';
$filtro_disponibilidade = isset($_GET['disponibilidade']) ? $_GET['disponibilidade'] : '';
$filtro_destaque = isset($_GET['destaque']) ? $_GET['destaque'] : '';
$filtro_preco_min = isset($_GET['preco_min']) ? $_GET['preco_min'] : '';
$filtro_preco_max = isset($_GET['preco_max']) ? $_GET['preco_max'] : '';

// Consulta CDs com base nos filtros
$sql_cd = "SELECT id_cd, titulo, capa, disponibilidade, preco, destaque, anoLancamento, genero, descricao, vendas FROM CD WHERE 1=1";

if (!empty($filtro_titulo)) {
    $sql_cd .= " AND titulo LIKE '%$filtro_titulo%'";
}

if (!empty($filtro_genero)) {
    $sql_cd .= " AND genero LIKE '%$filtro_genero%'";
}

if (!empty($filtro_ano)) {
    $sql_cd .= " AND anoLancamento = $filtro_ano";
}

if (!empty($filtro_disponibilidade)) {
    $sql_cd .= " AND disponibilidade LIKE '%$filtro_disponibilidade%'";
}

if (!empty($filtro_destaque)) {
    $sql_cd .= " AND destaque LIKE '%$filtro_destaque%'";
}

if (!empty($filtro_preco_min)) {
    $sql_cd .= " AND preco >= $filtro_preco_min";
}

if (!empty($filtro_preco_max)) {
    $sql_cd .= " AND preco <= $filtro_preco_max";
}

$result_cd = $conn->query($sql_cd);
?>

<h3>Gerenciar CDs</h3>

<!-- Formulário de Filtro -->
<form action="processar_filtro.php" method="GET">
    <label for="titulo">Título:</label>
    <input type="text" name="titulo" id="titulo" value="<?= $filtro_titulo ?>">

    <label for="genero">Gênero:</label>
    <input type="text" name="genero" id="genero" value="<?= $filtro_genero ?>">

    <label for="ano">Ano de Lançamento:</label>
    <input type="number" name="ano" id="ano" value="<?= $filtro_ano ?>">

    <label for="disponibilidade">Disponibilidade:</label>
    <input type="text" name="disponibilidade" id="disponibilidade" value="<?= $filtro_disponibilidade ?>">

    <label for="destaque">Destaque:</label>
    <input type="text" name="destaque" id="destaque" value="<?= $filtro_destaque ?>">

    <label for="preco_min">Preço Mínimo:</label>
    <input type="number" name="preco_min" id="preco_min" value="<?= $filtro_preco_min ?>">

    <label for="preco_max">Preço Máximo:</label>
    <input type="number" name="preco_max" id="preco_max" value="<?= $filtro_preco_max ?>">

    <button type="submit">Filtrar</button>
</form>

<!-- Tabela de CDs -->
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
            <th>Vendas</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result_cd->num_rows > 0) {
            while ($cd = $result_cd->fetch_assoc()) {
                $id_cd = $cd['id_cd'];

                // Buscar os artistas associados ao CD
                $sql_artistas = "SELECT Artista.nomeArtista FROM CD_Artista 
                                 JOIN Artista ON CD_Artista.id_artista = Artista.id_artista 
                                 WHERE CD_Artista.id_cd = $id_cd";
                $result_artistas = $conn->query($sql_artistas);

                $artistas = [];
                while ($artista = $result_artistas->fetch_assoc()) {
                    $artistas[] = $artista['nomeArtista'];
                }
                $artistas_list = !empty($artistas) ? implode(", ", $artistas) : "Nenhum artista associado";

                // Buscar as músicas associadas ao CD
                $sql_musicas = "SELECT Musica.nomeMusica FROM CD_Musica 
                                JOIN Musica ON CD_Musica.id_musica = Musica.id_musica 
                                WHERE CD_Musica.id_cd = $id_cd";
                $result_musicas = $conn->query($sql_musicas);

                $musicas = [];
                while ($musica = $result_musicas->fetch_assoc()) {
                    $musicas[] = $musica['nomeMusica'];
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
                        <td><img src='../{$cd['capa']}' alt='Capa do CD' width='100'></td>
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
                        <td>{$cd['vendas']}</td>
                        <td>
                            <a href='editar_cd.php?id_cd={$cd['id_cd']}'>Editar</a> |
                            <a href='excluir_cd.php?id_cd={$cd['id_cd']}'>Excluir</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='15'>Nenhum CD encontrado</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
?>