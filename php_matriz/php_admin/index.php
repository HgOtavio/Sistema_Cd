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

// Consulta para pegar a foto do perfil do administrador
$sql = "SELECT foto_perfil FROM Usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Caminho da foto do perfil (caso exista)
$foto_perfil = !empty($usuario['foto_perfil']) ? "../" . $usuario['foto_perfil'] : "../php_cliente/uploads/default.png";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        h2 {
            color: #333;
        }
        .menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        .menu a {
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            width: 200px;
            text-align: center;
            font-size: 16px;
        }
        .menu a:hover {
            background-color: #0056b3;
        }
        .perfil {
            margin-bottom: 20px;
        }
        .perfil img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #007BFF;
        }
    </style>
</head>
<body>

    <h2>Painel Administrativo</h2>

    <!-- Exibe a foto de perfil do administrador -->
    <div class="perfil">
        <img src="<?php echo $foto_perfil; ?>" alt="Foto de Perfil">
    </div>

    <div class="menu">
        <a href="gerenciar_cd.php">Gerenciar CDs</a>
        <a href="gerenciar_musicas.php">Gerenciar Músicas</a>
        <a href="gerenciar_artista.php">Gerenciar Artistas</a>
        <a href="gerenciar_usuarios.php">Gerenciar Usuários</a>
        <a href="admin_editar_usuario_form.php?id=<?php echo $_SESSION['id_usuario']; ?>">Editar seu usuário</a>
        <li><a href="../logout.php">Sair</a></li>
    </div>

</body>
</html>