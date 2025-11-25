<?php
    // --- Lógica de Consulta Integrada (Ajustada para o erro de ArgumentCountError) ---
    require_once '../model/DTO/PacienteDTO.php'; 
    require_once '../model/DAO/PacienteDAO.php';
    require_once '../model/DAO/conexao.php';
    
    // ATENÇÃO: Você precisa definir a variável $conexao corretamente no conexao.php.
    // O erro 'Undefined variable $conexao' persiste se a conexão falhar.
    
    $PacienteDTO = new PacienteDTO(); 
    $PacienteDAO = new PacienteDAO($conexao);
    
    // Chamada ajustada com dois argumentos (DTO e null) para evitar o ArgumentCountError.
    // Assumimos que a função PacienteDAO->consultarPaciente retorna a lista de pacientes.
    $listaPaciente = $PacienteDAO->consultarPaciente($PacienteDTO, null); 
    
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica ETC - Consulta</title>
    <link rel="stylesheet" type="text/css" href="../css/estilo.css">
    
    <style>
        .data-table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }
        .data-table th {
            background-color: #333;
            color: white;
            text-align: center;
        }
        .data-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .data-table tr:hover {
            background-color: #ddd;
        }
        .data-table button {
            padding: 8px 12px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .data-table button a {
            text-decoration: none;
            color: white;
            display: block;
        }
        .data-table .excluir {
            background-color: #dc3545; 
        }
        .data-table .alterar {
            background-color: #007bff;
        }
    </style>
</head>
<body class="consulta-bg"> 
    <nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="cadastrarPaciente.php">Cadastrar</a></li>
            <li><a href="consultarPaciente.php">Consultar</a></li>
        </ul>
    </nav>
    
    <div class="content-wrapper"> 
        <div class="container">
            <h1>Consulta Paciente</h1>
            <table class="data-table">
                <tr>
                    <th>ID</th>
                    <th>NOME</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th colspan="2">Ações</th>
                </tr>
                <?php 
                if (is_array($listaPaciente) && !empty($listaPaciente)){
                    foreach($listaPaciente as $Paciente) : ?>
                        <tr>
                            <td><?= htmlspecialchars($Paciente['id']) ?></td>
                            <td><?= htmlspecialchars($Paciente['nome']) ?></td>
                            <td><?= htmlspecialchars($Paciente['CPF']) ?></td>
                            <td><?= htmlspecialchars($Paciente['Telefone']) ?></td>
                            <td><?= htmlspecialchars($Paciente['Email']) ?></td>   
                            
                            <td>
                                <button class="excluir">
                                    <a href="../control/excluirPacienteControl.php?id=<?= htmlspecialchars($Paciente['id'])?>"> Excluir </a>
                                </button>
                            </td>   
                            
                            <td>
                                <button class="alterar">
                                    <a href="../view/alterarPaciente.php?id=<?= htmlspecialchars($Paciente['id'])?>"> Alterar </a>
                                </button>
                            </td>      
                        </tr>
                <?php endforeach; 
                } else { ?>
                    <tr>
                        <td colspan="7">Nenhum paciente encontrado.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>