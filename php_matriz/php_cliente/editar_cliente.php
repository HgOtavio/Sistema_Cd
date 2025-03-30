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

// Busca os dados do usuário no banco
$query = "SELECT * FROM Usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    echo "Usuário não encontrado!";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form label {
            display: block;
            margin-top: 10px;
        }

        form input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        img {
            max-width: 100px;
            display: block;
            margin: 10px auto;
            border-radius: 50%;
        }

        .back-btn {
            background-color: #888;
            margin-top: 10px;
        }

        .back-btn:hover {
            background-color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Dados</h2>
        <form action="salvar_edicao_cliente.php" method="POST" enctype="multipart/form-data">
            <?php if (!empty($usuario['foto_perfil'])): ?>
                <img src="../<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de Perfil">
            <?php endif; ?>

            <label for="foto_perfil">Alterar Foto de Perfil:</label>
            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">

            <label for="nome_completo">Nome Completo:</label>
            <input type="text" id="nome_completo" name="nome_completo" value="<?php echo htmlspecialchars($usuario['nome_completo']); ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($usuario['cpf']); ?>" required>

            <label for="login">Login:</label>
            <input type="text" id="login" name="login" value="<?php echo htmlspecialchars($usuario['login']); ?>" required>

            <label for="senha_antiga">Senha Antiga:</label>
            <input type="password" id="senha_antiga" name="senha_antiga">

            <label for="senha">Nova Senha:</label>
            <input type="password" id="senha" name="senha">

            <label for="senha_confirmada">Confirmar Nova Senha:</label>
            <input type="password" id="senha_confirmada" name="senha_confirmada">

            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">

            <button type="submit">Salvar Alterações</button>
        </form>
        <a href="index.php"><button type="button" class="back-btn">Voltar ao Menu</button></a>
    </div>
</body>
</html>