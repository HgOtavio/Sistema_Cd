<?php
session_start();
include "../conexao.php";

// Verifique se o usuário está logado
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "cliente") {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_usuario = $_POST['id_usuario'];
    $nome_completo = $_POST['nome_completo'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $login = $_POST['login'];
    $senha_antiga = $_POST['senha_antiga'];
    $senha_nova = $_POST['senha'];
    $senha_confirmada = $_POST['senha_confirmada'];
    
    // Upload da foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $foto_perfil = $_FILES['foto_perfil']['name'];
        $foto_perfil_tmp = $_FILES['foto_perfil']['tmp_name'];
        
        $destino = "uploads/" . basename($foto_perfil);
        move_uploaded_file($foto_perfil_tmp, $destino);
    } else {
        $foto_perfil = null;
    }

    // Busca a senha atual no banco de dados
    $stmt = $conn->prepare("SELECT senha FROM Usuario WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $senha_atual_cripto = $usuario['senha'];

    // Verificar se a senha antiga está correta (se foi enviada)
    if (!empty($senha_antiga) && !password_verify($senha_antiga, $senha_atual_cripto)) {
        echo "Senha antiga incorreta. <a href='editar_cliente.php'>Tente novamente</a>";
        exit();
    }

    // Verificar se a nova senha coincide com a confirmação
    if (!empty($senha_nova) && $senha_nova !== $senha_confirmada) {
        echo "As senhas não coincidem. <a href='editar_cliente.php'>Tente novamente</a>";
        exit();
    }

    // Atualiza a senha se uma nova senha foi fornecida
    if (!empty($senha_nova)) {
        $senha_nova = password_hash($senha_nova, PASSWORD_DEFAULT);
    } else {
        $senha_nova = $senha_atual_cripto;
    }

    // Atualiza os dados no banco de dados
    $sql = "UPDATE Usuario SET nome_completo = ?, email = ?, telefone = ?, cpf = ?, login = ?, senha = ?, foto_perfil = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $nome_completo, $email, $telefone, $cpf, $login, $senha_nova, $foto_perfil, $id_usuario);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao atualizar os dados. <a href='editar_cliente.php'>Tente novamente</a>";
    }
}
?>