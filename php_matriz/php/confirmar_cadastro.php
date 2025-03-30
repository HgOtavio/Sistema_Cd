<?php
include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = trim($_POST["nome_completo"]);
    $email = trim($_POST["email"]);
    $cpf = trim($_POST["cpf"]);
    $telefone = trim($_POST["telefone"]);
    $login = trim($_POST["login"]);
    $senha = $_POST["senha"];
    $confirma_senha = $_POST["confirma_senha"];

    // Verifica se as senhas coincidem
    if ($senha !== $confirma_senha) {
        die("As senhas não coincidem!");
    }

    // Hash da senha para segurança
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Verificar se o nome de usuário já existe
    $sql_check = "SELECT * FROM Usuario WHERE login = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Erro: O nome de usuário já está em uso. Escolha outro.");
    }

    // Inserir usuário na tabela Usuario
    $sql = "INSERT INTO Usuario (nome_completo, email, cpf, telefone, login, senha, tipo) 
            VALUES (?, ?, ?, ?, ?, ?, 'cliente')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nome_completo, $email, $cpf, $telefone, $login, $senha_hash);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso! <a href='login.php'>Faça login</a>";
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>