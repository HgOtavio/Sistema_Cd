<?php
// Inclui o arquivo de lógica para pegar os dados do usuário
include 'admin_editar_usuario.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
</head>
<body>

    <h2>Editar Usuário</h2>
    <form action="admin_editar_usuario.php?id=<?php echo $id_usuario; ?>" method="POST" enctype="multipart/form-data">
        <label for="nome_completo">Nome Completo:</label>
        <input type="text" name="nome_completo" value="<?php echo $usuario['nome_completo']; ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required><br><br>

        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" value="<?php echo $usuario['cpf']; ?>" required><br><br>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" value="<?php echo $usuario['telefone']; ?>" required><br><br>

        <label for="login">Login:</label>
        <input type="text" name="login" value="<?php echo $usuario['login']; ?>" required><br><br>

        <label for="senha_antiga">Senha Antiga:</label>
        <input type="password" name="senha_antiga"><br><br>

        <label for="senha_nova">Nova Senha:</label>
        <input type="password" name="senha_nova"><br><br>

        <label for="confirmar_senha">Confirmar Nova Senha:</label>
        <input type="password" name="confirmar_senha"><br><br>

        <label for="foto_perfil">Foto de Perfil:</label>
        <input type="file" name="foto_perfil"><br><br>

        <button type="submit">Salvar Alterações</button>
    </form>

    <a href="gerenciar_usuarios.php">Voltar para Gerenciar Usuários</a>

</body>
</html>