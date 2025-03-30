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

// Busca todos os usuários do tipo "cliente"
$result = $conn->query("SELECT * FROM Usuario WHERE tipo = 'cliente'");

?>

<h2>Gerenciar Usuários</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Foto</th>
        <th>Nome</th>
        <th>Email</th>
        <th>CPF</th>
        <th>Telefone</th>
        <th>Login</th>
        <th>Ações</th>
    </tr>

    <?php while ($usuario = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $usuario['id_usuario']; ?></td>

            <!-- Exibe a foto do usuário, se existir -->
            <td>
                <?php if (!empty($usuario['foto_perfil'])): ?>
                    <img src="../<?php echo $usuario['foto_perfil']; ?>" width="50" height="50">
                <?php else: ?>
                    <img src="../php_cliente/upload/default.png" width="50" height="50">
                <?php endif; ?>
            </td>

            <td><?php echo $usuario['nome_completo']; ?></td>
            <td><?php echo $usuario['email']; ?></td>
            <td><?php echo $usuario['cpf']; ?></td>
            <td><?php echo $usuario['telefone']; ?></td>
            <td><?php echo $usuario['login']; ?></td>
            <td>
                <a href="editar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>">Editar</a> | 
                <a href="excluir_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
            </td>
        </tr>
    <?php endwhile; ?>

</table>

<a href="../admin_dashboard.php">Voltar ao Painel</a>