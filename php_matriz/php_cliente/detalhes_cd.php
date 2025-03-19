<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "LojaCDs");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Pegar o ID do CD da URL
$id_cd = $_GET['id_cd'];

// Consultar os dados do CD, artistas e promoção
$sql = "
    SELECT 
        CD.*, 
        GROUP_CONCAT(DISTINCT Artista.nomeArtista SEPARATOR ', ') AS artistas,
        IFNULL(Promocao.desconto, 0) AS desconto
    FROM CD
    LEFT JOIN CD_Artista ON CD.id_cd = CD_Artista.id_cd
    LEFT JOIN Artista ON CD_Artista.id_artista = Artista.id_artista
    LEFT JOIN Promocao ON CD.id_cd = Promocao.id_cd
    WHERE CD.id_cd = $id_cd
    GROUP BY CD.id_cd
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $cd = $result->fetch_assoc();
    $caminho_capa = "../" . $cd['capa'];
    $precoOriginal = $cd['preco'];
    $desconto = $cd['desconto'];

    if ($desconto > 0) {
        $precoComDesconto = $precoOriginal - ($precoOriginal * ($desconto / 100));
        $precoExibido = "<del>R$ " . number_format($precoOriginal, 2, ',', '.') . "</del> R$ " . number_format($precoComDesconto, 2, ',', '.');
    } else {
        $precoExibido = "R$ " . number_format($precoOriginal, 2, ',', '.');
    }
} else {
    echo "CD não encontrado.";
    exit;
}

// Consultar todas as músicas do CD
$sqlMusicas = "
    SELECT Musica.nomeMusica, Musica.tempo
    FROM CD_Musica
    INNER JOIN Musica ON CD_Musica.id_musica = Musica.id_musica
    WHERE CD_Musica.id_cd = $id_cd
";

$resultMusicas = $conn->query($sqlMusicas);

$musicas = [];
if ($resultMusicas->num_rows > 0) {
    while ($musica = $resultMusicas->fetch_assoc()) {
        $musicas[] = $musica;
    }
}
?>

<h3>Detalhes do CD</h3>
<img src="<?php echo $caminho_capa; ?>" alt="Capa do CD" width="200">
<p><strong>Título:</strong> <?php echo $cd['titulo']; ?></p>
<p><strong>Artistas:</strong> <?php echo $cd['artistas']; ?></p>
<p><strong>Preço:</strong> <?php echo $precoExibido; ?></p>
<p><strong>Disponibilidade:</strong> <?php echo $cd['disponibilidade']; ?></p>
<p><strong>Destaque:</strong> <?php echo $cd['destaque']; ?></p>
<p><strong>Ano de Lançamento:</strong> <?php echo $cd['anoLancamento']; ?></p>
<p><strong>Gênero:</strong> <?php echo $cd['genero']; ?></p>
<p><strong>Descrição:</strong> <?php echo $cd['descricao']; ?></p>
<p><strong>Vendas:</strong> <?php echo $cd['vendas']; ?></p>

<!-- (Mantém o código anterior como está, apenas adiciona os botões abaixo da exibição das músicas) -->

<h4>Músicas:</h4>
<ul>
    <?php
    if (!empty($musicas)) {
        foreach ($musicas as $musica) {
            echo "<li>{$musica['nomeMusica']} - {$musica['tempo']} min</li>";
        }
    } else {
        echo "<li>Nenhuma música encontrada.</li>";
    }
    ?>
</ul>

<!-- Botões -->
<a href="listar_cds.php">Voltar</a>

<!-- Botão para adicionar ao carrinho -->
<form action="carrinho.php" method="post">
    <input type="hidden" name="id_cd" value="<?php echo $id_cd; ?>">
    <button type="submit">Adicionar ao Carrinho</button>
</form>

<?php $conn->close(); ?>