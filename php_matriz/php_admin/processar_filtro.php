<?php
session_start();
include "../php/conexao.php";

// Verifica se o usuário está logado e é do tipo administrador
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "admin") {
    header("Location: ../php/login.php");
    exit();
}

// Obtém os filtros enviados pelo formulário
$filtro_titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';
$filtro_genero = isset($_GET['genero']) ? $_GET['genero'] : '';
$filtro_ano = isset($_GET['ano']) ? $_GET['ano'] : '';
$filtro_disponibilidade = isset($_GET['disponibilidade']) ? $_GET['disponibilidade'] : '';
$filtro_destaque = isset($_GET['destaque']) ? $_GET['destaque'] : '';
$filtro_preco_min = isset($_GET['preco_min']) ? $_GET['preco_min'] : '';
$filtro_preco_max = isset($_GET['preco_max']) ? $_GET['preco_max'] : '';

// Construa a URL de redirecionamento com os parâmetros de filtro
$filtro_url = "gerenciar_cd.php?";

if (!empty($filtro_titulo)) {
    $filtro_url .= "titulo=" . urlencode($filtro_titulo) . "&";
}

if (!empty($filtro_genero)) {
    $filtro_url .= "genero=" . urlencode($filtro_genero) . "&";
}

if (!empty($filtro_ano)) {
    $filtro_url .= "ano=" . urlencode($filtro_ano) . "&";
}

if (!empty($filtro_disponibilidade)) {
    $filtro_url .= "disponibilidade=" . urlencode($filtro_disponibilidade) . "&";
}

if (!empty($filtro_destaque)) {
    $filtro_url .= "destaque=" . urlencode($filtro_destaque) . "&";
}

if (!empty($filtro_preco_min)) {
    $filtro_url .= "preco_min=" . urlencode($filtro_preco_min) . "&";
}

if (!empty($filtro_preco_max)) {
    $filtro_url .= "preco_max=" . urlencode($filtro_preco_max) . "&";
}

// Remover o último "&" da URL
$filtro_url = rtrim($filtro_url, "&");

// Redireciona de volta para a página de gerenciamento com os filtros aplicados
header("Location: " . $filtro_url);
exit();
?>