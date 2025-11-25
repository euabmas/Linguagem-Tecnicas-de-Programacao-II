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

<h1>Cadastrar Paciente</h1>

<form method="POST" action="../control/cadastrarPacienteControl.php">
<label>Nome do Paciente: </label>    
<input type="text" name="Nome"/>
    <br><br>
    
    <label>CPF do Paciente: </label>    
    <input type="text" name="CPF"/>
    <br><br>

    <label>Telefone do Paciente: </label>    
    <input type="number" name="Telefone"/>
    <br><br>

    <label>Email do Paciente: </label>    
    <input type="text" name="Email"/>
    <br><br>

    <input type="submit" value="Cadastrar" name="btnCadastrar"/>
    <input type="hidden" name="id"/>
</form>



</body>
</html>