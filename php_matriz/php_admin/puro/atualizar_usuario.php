<?php
session_start();
include "../conexao.php";

// Verifica se o usuário está logado e é do tipo administrador
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "admin") {
    header("Location: ../login.php");
    exit();
}

// Processa o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = $_POST["nome_completo"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];
    $login = $_POST["login"];
    $senha = md5($_POST["senha"]);  // Criptografa a senha usando MD5
    $tipo = "cliente"; // Tipo padrão de usuário é cliente
    $foto_perfil = null;

    // Upload da foto de perfil, se houver
    if (isset($_FILES["foto_perfil"]) && $_FILES["foto_perfil"]["error"] == 0) {
        $pasta = "../php_cliente/upload/";
        $nome_arquivo = uniqid() . "_" . basename($_FILES["foto_perfil"]["name"]);
        $caminho_completo = $pasta . $nome_arquivo;

        if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $caminho_completo)) {
            $foto_perfil = "php_cliente/upload/" . $nome_arquivo; // Caminho relativo da foto
        } else {
            echo "Erro ao fazer o upload da foto de perfil.";
        }
    }

    // Insere os dados no banco de dados
    $sql = "INSERT INTO Usuario (nome_completo, email, cpf, telefone, login, senha, tipo, foto_perfil) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $nome_completo, $email, $cpf, $telefone, $login, $senha, $tipo, $foto_perfil);

    if ($stmt->execute()) {
        header("Location: gerenciar_usuarios.php"); // Redireciona para a página de gerenciamento
        exit();
    } else {
        echo "Erro ao adicionar o usuário: " . $conn->error;
    }
}
?>