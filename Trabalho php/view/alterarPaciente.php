<?php
    // Inclui os arquivos necessários
    include_once '../model/DAO/PacienteDAO.php';
    include_once '../model/DAO/conexao.php';
    include_once '../model/DTO/PacienteDTO.php'; // Incluído para usar métodos de DTO, se necessário
    
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $pacienteDTO = null;

    if ($id > 0) {
        $PacienteDAO = new PacienteDAO($conexao);
        
        // Assumindo que consultarId retorna o PacienteDTO populado ou um array de dados
        $dadosPaciente = $PacienteDAO->consultarId($id); 

        // Se PacienteDAO->consultarId retorna um PacienteDTO, usamos ele.
        // Se retornar um array, vamos usar o array diretamente para preencher os campos.
        // Para simplificar, vamos assumir que ele retorna um array associativo.
        $pacienteAtual = $dadosPaciente;
    }

    // Se o paciente não foi encontrado ou ID ausente, redireciona
    if (!isset($pacienteAtual) || empty($pacienteAtual)) {
        header("Location: consultarPaciente.php");
        exit;
    }

    // Usaremos as variáveis para preencher os campos:
    $nome = htmlspecialchars($pacienteAtual['nome']);
    $cpf = htmlspecialchars($pacienteAtual['CPF']);
    $telefone = htmlspecialchars($pacienteAtual['Telefone']);
    $email = htmlspecialchars($pacienteAtual['Email']);
    
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica ETC - Alterar</title>
    <link rel="stylesheet" type="text/css" href="../css/estilo.css">
</head>

<body class="cadastro-bg">
    <nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="cadastrarPaciente.php">Cadastrar</a></li>
            <li><a href="consultarPaciente.php">Consultar</a></li>
        </ul>
    </nav>
    
    <div class="content-wrapper">
        <div class="container">
            <h1>Alterar Paciente (ID: <?= $id ?>)</h1>

            <form method="POST" action="../control/alterarPacienteControl.php">

                <input type="hidden" name="idAlterar" value="<?= $id; ?>" />

                <label>Nome do Paciente: </label>
                <input type="text" name="Nome" value="<?= $nome ?>" placeholder="Ex: João da Silva" />
                <br><br>

                <label>CPF do Paciente: </label>
                <input type="text" name="CPF" value="<?= $cpf ?>" placeholder="Ex: 000.000.000-00" />
                <br><br>

                <label>Telefone do Paciente: </label>
                <input type="number" name="Telefone" value="<?= $telefone ?>" placeholder="Ex: 61999998888" />
                <br><br>

                <label>Email do Paciente: </label>
                <input type="text" name="Email" value="<?= $email ?>" placeholder="Ex: email@paciente.com" />
                <br><br>

                <input type="submit" value="Salvar Alterações" name="btnAlterar" />
            </form>
        </div>
    </div>
</body>

</html>