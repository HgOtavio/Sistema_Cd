<?php
session_start();
include "../php/conexao.php";

// Verifica se o usuário está logado e é do tipo administrador
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "admin") {
    header("Location: ../php/login.php");
    exit();
}

// Verifica se foi passado um ID de usuário para editar
if (!isset($_GET['id'])) {
    echo "Usuário não encontrado!";
    exit();
}

$id_usuario = $_GET['id'];

// Consulta os dados do usuário a ser editado
$result = $conn->query("SELECT * FROM Usuario WHERE id_usuario = $id_usuario");
$usuario = $result->fetch_assoc();

if (!$usuario) {
    echo "Usuário não encontrado!";
    exit();
}

// Se o formulário foi enviado, atualiza os dados do usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = $_POST["nome_completo"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];
    $login = $_POST["login"];
    $senha_antiga = $_POST["senha_antiga"];
    $senha_nova = $_POST["senha_nova"];
    $confirmar_senha = $_POST["confirmar_senha"];
    $foto_perfil = $usuario['foto_perfil']; // Foto atual

    // Processa o upload da foto se ela foi enviada
    if (!empty($_FILES['foto_perfil']['name'])) {
        $nome_arquivo = basename($_FILES["foto_perfil"]["name"]);
        $caminho_arquivo = "../php_cliente/uploads/" . $nome_arquivo;

        if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $caminho_arquivo)) {
            $foto_perfil = "php_cliente/uploads/" . $nome_arquivo;
        } else {
            echo "Erro ao fazer upload da foto.";
            exit();
        }
    }

    // Atualiza a senha se foi fornecida
    if (!empty($senha_antiga)) {
        if (md5($senha_antiga) === $usuario['senha']) {
            if ($senha_nova === $confirmar_senha) {
                $senha_nova_md5 = md5($senha_nova);
                $sql = "UPDATE Usuario SET nome_completo = ?, email = ?, cpf = ?, telefone = ?, login = ?, senha = ?, foto_perfil = ? WHERE id_usuario = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssi", $nome_completo, $email, $cpf, $telefone, $login, $senha_nova_md5, $foto_perfil, $id_usuario);
            } else {
                echo "As senhas novas não coincidem!";
                exit();
            }
        } else {
            echo "Senha antiga incorreta!";
            exit();
        }
    } else { 
        // Atualiza os dados sem alterar a senha
        $sql = "UPDATE Usuario SET nome_completo = ?, email = ?, cpf = ?, telefone = ?, login = ?, foto_perfil = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nome_completo, $email, $cpf, $telefone, $login, $foto_perfil, $id_usuario);
    }

    if ($stmt->execute()) {
        header("Location: admin_gerenciar_usuarios.php");
        exit();
    } else {
        echo "Erro ao atualizar os dados: " . $conn->error;
    }
}
?>