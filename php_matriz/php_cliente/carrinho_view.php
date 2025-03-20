<?php
// Conexão com o banco de dados (ajuste conforme necessário)
$servername = "localhost";  // ou o servidor do seu banco
$username = "root";         // ou seu usuário do banco
$password = "";             // ou sua senha do banco
$dbname = "LojaCDs";        // nome do seu banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>

<?php
// Inicia a sessão para verificar se o usuário está logado
session_start();
if (!isset($_SESSION['id_usuario'])) {
    echo "Você precisa estar logado para acessar o carrinho!";
    exit;
}

$user_id = $_SESSION['id_usuario'];  // ID do usuário logado

// Consulta SQL para buscar os itens no carrinho
$sql = "SELECT Carrinho.id, CD.id_cd, CD.titulo, CD.capa, Carrinho.quantidade, CD.preco, IFNULL(Promocao.desconto, 0) AS desconto, CD.disponibilidade
        FROM Carrinho
        JOIN CD ON Carrinho.id_cd = CD.id_cd
        LEFT JOIN Promocao ON CD.id_cd = Promocao.id_cd
        WHERE Carrinho.id_usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);  // Bind do parâmetro de usuário
$stmt->execute();

$result = $stmt->get_result();  // Obtém o resultado da consulta
if ($result === false) {
    echo "Erro ao buscar os itens do carrinho: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; }
        .container { margin-top: 50px; }
        .produto { display: flex; justify-content: space-between; padding: 10px; background: white; margin-bottom: 10px; border-radius: 10px; }
        .produto img { width: 100px; height: auto; border-radius: 10px; }
        .produto-info { flex: 1; padding-left: 10px; }
        .quantidade { width: 50px; }
        .btn-remover { background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-comprar { background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-atualizar { background-color: orange; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Carrinho de Compras</h1>

    <div class="container">
        <?php
        // Verifica se a consulta retornou algum item
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $preco_com_desconto = $row['preco'] * (1 - $row['desconto'] / 100);
                $total = $row['quantidade'] * $preco_com_desconto;
                
                // Verificando a quantidade máxima que pode ser inserida (estoque disponível)
                $quantidade_maxima = min($row['disponibilidade'], $row['quantidade']);
                
                echo "<div class='produto'>
                        <img src='../" . ($row['capa'] ? $row['capa'] : 'sem-capa.jpg') . "' alt='Capa do CD'>
                        <div class='produto-info'>
                            <h3>" . $row['titulo'] . "</h3>
                            <p>Preço: R$ " . number_format($row['preco'], 2, ',', '.') . "</p>";
                if ($row['desconto'] > 0) {
                    echo "<p>Desconto: " . $row['desconto'] . "%</p>";
                    echo "<p>Preço com Desconto: R$ " . number_format($preco_com_desconto, 2, ',', '.') . "</p>";
                }
                echo "<p>Total: R$ " . number_format($total, 2, ',', '.') . "</p>
                            <form action='compra.php' method='POST'>
                                <input type='hidden' name='id_cd' value='" . $row['id_cd'] . "'>
                                <input type='hidden' name='preco' value='" . $preco_com_desconto . "'>
                                <input type='hidden' name='quantidade' value='" . $row['quantidade'] . "'>
                                <button type='submit' class='btn-comprar'>Finalizar Compra</button>
                            </form>
                            <form action='atualizar_quantidade.php' method='POST'>
                                <input type='hidden' name='id_carrinho' value='" . $row['id'] . "'>
                                <label for='quantidade'>Quantidade:</label>
                                <input type='number' name='quantidade' value='" . $row['quantidade'] . "' class='quantidade' min='1' max='" . $row['disponibilidade'] . "'>
                                <button type='submit' class='btn-atualizar'>Atualizar Quantidade</button>
                            </form>
                            <form action='remover_item.php' method='POST'>
                                <input type='hidden' name='id_carrinho' value='" . $row['id'] . "'>
                                <button type='submit' class='btn-remover'>Remover</button>
                            </form>
                        </div>
                    </div>";
            }
        } else {
            echo "<p style='text-align: center;'>Seu carrinho está vazio.</p>";
        }
        ?>

        <div style="text-align: center; margin-top: 20px;">
            <a href="cds.php" class="btn-comprar">Voltar</a>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>