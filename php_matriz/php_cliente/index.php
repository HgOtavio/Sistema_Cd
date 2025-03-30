<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "cliente") {
    header("Location: ../php/login.php");
    exit();
}

// Conexão com o banco de dados
include "../php/conexao.php";

// Obtém o ID do usuário logado
$id_usuario = $_SESSION["id_usuario"];

// Busca o caminho da foto de perfil do banco de dados
$sql = "SELECT foto_perfil FROM Usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$stmt->bind_result($foto_perfil);
$stmt->fetch();
$stmt->close();



// Verifica se o usuário tem uma foto de perfil definida, caso contrário, usa uma imagem padrão
$foto_exibida = $foto_perfil ? $foto_perfil : 'default-avatar.png';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Cliente</title>
    <style>
        /* Estilos gerais */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            margin-top: 30px;
            color: #007BFF;
        }

        /* Estilos para o painel */
        ul {
            list-style-type: none;
            padding: 0;
            margin-top: 20px;
            text-align: center;
        }

        li {
            margin: 10px 0;
        }

        a {
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #0056b3;
        }

        /* Estilos para a foto de perfil */
        .foto-perfil {
            margin-top: 30px;
            text-align: center;
        }

        .foto-perfil img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid #007BFF;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        /* Estilos do container principal */
        .container {
            width: 80%;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Títulos */
        h3 {
            color: #333;
            font-size: 22px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Bem-vindo ao Painel do Cliente</h2>

        <!-- Exibe a foto de perfil -->
        <div class="foto-perfil">
            <h3>Foto de Perfil</h3>
            <img src="../<?php echo $foto_exibida; ?>" alt="Foto de perfil">
        </div>

        <!-- Links do painel -->
        <ul>
            <li><a href="editar_cliente.php">Editar Meus Dados</a></li>
            <li><a href="listar_cds.php">Comprar CDs</a></li>
            <li><a href="carrinho_view.php">Carrinho</a></li>
            <li><a href="favoritos.php">Favoritos</a></li>
            <li><a href="sugestao.php">Sugerir Novos CDs</a></li>
            <li><a href="historico_de_compra.php">Histórico de Compras</a></li>
            <li><a href="../logout.php">Sair</a></li>
        </ul>

        
    </div>

</body>
</html>