<?php
    include_once '../model/DAO/PacienteDAO.php';
    include_once '../model/DAO/conexao.php';
    
    

    $ProdutoDAO = new PacienteDAO($conexao);
    $edita = $PacienteDAO->consultarId($_GET['id']);
    //var_dump($edita);
    //echo $edita['nome'];

    ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../css/estilo.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="cadastrarPaciente.php">Cadastrar</a></li>
            <li><a href="consultarPaciente.php">Consultar</a></li>
        </ul>

    </nav>
</header>

<h1>Alterar Paciente</h1>


<form method="POST" action="../control/alterarProdutoControl.php">
    
    <input type="hidden" name="idAlterar" value="<?= $_GET['id']; ?>" />
    
    <label>Nome do Paciente: </label>    
    <input type="text" name="nome" value="<?= $edita->__getNome() ?>"/>
    <br><br>
    
    <label>CPF do Paciente: </label>    
    <input type="number" name="valor" value="<?= $edita->__getCPF() ?>"/>
    <br><br>

    <label>Telefone do Paciente: </label>    
    <input type="number" name="quantidade" value="<?= $edita->__getTelefone() ?>"/>
    <br><br>

    <label>Email do Paciente: </label>    
    <input type="text" name="descricao" value="<?= $edita->__getEmail() ?>"/>
    <br><br>

    <input type="submit" value="Alterar " name="btnCadastrar"/>
    
 
</form>



</body>
</html>