<?php
if (isset($_GET['erro']) && $_GET['erro'] == 1) {
    echo "<p style='color: red;'>Login ou senha inválidos. Tente novamente.</p>";
}
?>
<form method="post" action="verificar_login.php">
    Login: <input type="text" name="login" required><br>
    Senha: <input type="password" name="senha" required><br>
    <button type="submit">Entrar</button>
</form>
<a href="cadastro.php">Criar conta</a>