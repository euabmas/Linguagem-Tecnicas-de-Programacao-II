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
    <h1>Consulta Paciente</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>NOME</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Email</th>
        </tr>
        <?php    require_once '../model/DTO/PacienteDTO.php';
                 require_once '../control/consultarPacienteControl.php';
        
        $listaPaciente = $PacienteDTO->__getListaPaciente();
        if ($listaPaciente != null){
            foreach($listaPaciente as $Paciente) : ?>
                <tr>
                    <td><?= $Paciente['id'] ?></td>
                    <td><?= $Paciente['Nome'] ?></td>
                    <td><?= $Paciente['CPF'] ?></td>
                    <td><?= $Paciente['Telefone'] ?></td>
                    <td><?= $Paciente['Email'] ?></td>   
                    <td><button><a href="../control/excluirPacienteControl.php?id=<?= $Paciente['id']?>"> Excluir </a></button></td>   
                    <td><button><a href="../view/alterarPacinete.php?id=<?= $Paciente['id']?>"> Alterar </a></button></td>      
                </tr>
        <?php endforeach  ; } ?>
    </table>
</body>
</html>