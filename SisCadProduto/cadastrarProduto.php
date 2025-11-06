<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SisCadProduto</title>
    <link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php"> Home </a></li>
            <li><a href="cadastrarProduto.php"> Cadastrar </a></li>
            <li><a href="consultarProduto.php"> Consultar </a></li>
        </ul>
    </nav>

    <h1> CADASTRAR PRODUTO </h1>

    <form method="POST" action="cadastrarProdutoControl.php">
        <label>Nome Produto: </label>
        <input type="text" name="nome"/>
        <br><br>

        <label>Valor Produto: </label>
        <input type="text" name="valor"/>
        <br><br>

        <label>Quantidade Produto: </label>
        <input type="number" name="quantidade"/>
        <br><br>

        <label>Descrição Produto: </label>
        <input type="text" name="descricao"/>
        <br><br>

        <input type="submit" value="Cadastrar" name="btnCadastrar"/>

    </form>
    
</body>
</html>