<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>
    <form action="../cadastro.php" method="POST">
        Nome Completo: <input type="text" name="nome_completo" required><br>
        E-mail: <input type="email" name="email" required><br>
        CPF: <input type="text" name="cpf" required><br>
        Telefone: <input type="text" name="telefone" required><br>
        Usuário: <input type="text" name="login" required><br>
        Senha: <input type="password" name="senha" required><br>
        Confirmar Senha: <input type="password" name="confirma_senha" required><br>
        <button type="submit">Cadastrar</button>
    </form>
    <p>Se você já tem um login, <a href="login.php">faça login</a>.</p>
</body>
</html>