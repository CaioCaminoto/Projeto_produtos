<?php

header("content-type: application/json");

require "conexao.php";

$metodo = $_SERVER["REQUEST_METHOD"]; 

//POST = criar, no caso produto
if($metodo == "POST"){
    $json = file_get_contents("php://input");

    $dados = json_decode($json, true);

    // Se receber apenas um produto isolado, transforma em lista para manter o padrão
    if (is_array($dados) && isset($dados["nome"])) {
        $dados = [$dados];
    }

    if (is_array($dados) && !empty($dados)) {
        // Monta os pontos de interrogação (?, ?) para cada produto da lista dinamicamente
        $linhas = array_fill(0, count($dados), "(?, ?)");
        $sql = "INSERT INTO produtos (nome, preco) VALUES " . implode(", ", $linhas);
        
        $comando = $pdo->prepare($sql);
        
        // Junta todos os nomes e preços em um único array plano para o execute
        $valores = [];
        foreach ($dados as $produto) {
            $valores[] = $produto["nome"] ?? null;
            $valores[] = $produto["preco"] ?? null;
        }
        
        $comando->execute($valores);
    }
    
    echo json_encode([
        "mensagem"=>"Produtos cadastrados com sucesso! 😁👍"
    ]);
}

//GET = ver, no caso os produtos criados
if($metodo == "GET"){
    $sql = "SELECT * FROM produtos ORDER BY id";

    //query = consulta
    $comando = $pdo -> query($sql);

    $produtos = $comando -> fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($produtos);
}
