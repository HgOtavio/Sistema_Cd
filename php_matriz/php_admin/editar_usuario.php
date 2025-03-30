<?php include "processar_edicao_user.php"; ?>

<h2>Editar Usuário</h2>
<form method="POST" action="processar_edicao_user.php?id=<?php echo $id_usuario; ?>" enctype="multipart/form-data">
    Nome Completo: <input type="text" name="nome_completo" value="<?php echo $usuario['nome_completo']; ?>" required><br>
    E-mail: <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required><br>
    CPF: <input type="text" name="cpf" value="<?php echo $usuario['cpf']; ?>" required><br>
    Telefone: <input type="text" name="telefone" value="<?php echo $usuario['telefone']; ?>" required><br>
    Login: <input type="text" name="login" value="<?php echo $usuario['login']; ?>" required><br>

    <!-- Foto de perfil atual -->
    <?php if (!empty($usuario['foto_perfil'])): ?>
        <img src="../<?php echo $usuario['foto_perfil']; ?>" width="100" height="100"><br>
    <?php else: ?>
        Nenhuma foto enviada.<br>
    <?php endif; ?>

    <!-- Upload de nova foto -->
    Foto de Perfil: <input type="file" name="foto_perfil"><br><br>

    <!-- Campos para alteração de senha -->
    Senha Antiga: <input type="password" name="senha_antiga" placeholder="Digite a senha antiga"><br>
    Nova Senha: <input type="password" name="senha_nova" placeholder="Digite a nova senha"><br>
    Confirmar Nova Senha: <input type="password" name="confirmar_senha" placeholder="Confirme a nova senha"><br>

    <button type="submit">Salvar Alterações</button>
</form>