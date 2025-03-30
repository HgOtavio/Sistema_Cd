<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
</head>
<body>
    <h2>Adicionar Novo Usuário</h2>
    <form action="atualizar_usuario.php" method="POST" enctype="multipart/form-data">
        Nome Completo: <input type="text" name="nome_completo" required><br>
        E-mail: <input type="email" name="email" required><br>
        CPF: <input type="text" name="cpf" required><br>
        Telefone: <input type="text" name="telefone" required><br>
        Login: <input type="text" name="login" required><br>
        Senha: <input type="password" name="senha" required><br>
        Foto de Perfil: <input type="file" name="foto_perfil"><br><br>
        <button type="submit">Adicionar Usuário</button>
    </form>
</body>
</html>