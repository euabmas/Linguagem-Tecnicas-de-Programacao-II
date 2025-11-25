<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica ETC - Cadastro de Paciente</title>
    <link rel="stylesheet" type="text/css" href="../css/estilo.css">
    
    <style>
        .registration-form {
            width: 100%;
            max-width: 450px; /* Limita a largura do formulário para melhor estética */
            text-align: left; /* Alinha os rótulos à esquerda */
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
            font-size: 18px; /* Usa o tamanho de fonte definido no body */
        }
        .registration-form input[type="text"], 
        .registration-form input[type="email"], 
        .registration-form input[type="tel"], 
        .registration-form input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box; /* Garante que o padding não aumente a largura total */
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background-color: #007bff; /* Cor mais moderna para o botão */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>
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
            <h1>Cadastrar Novo Paciente</h1>

            <form method="POST" action="../control/cadastrarPacienteControl.php" class="registration-form">
                
                <div class="form-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="Nome" placeholder="Ex: João da Silva" required />
                </div>

                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="CPF" placeholder="000.000.000-00" pattern="\d{3}\.?\d{3}\.?\d{3}-?\d{2}" maxlength="14" required />
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="tel" id="telefone" name="Telefone" placeholder="Ex: (11) 99999-8888" required />
                </div>

                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="Email" placeholder="Ex: email@paciente.com" required />
                </div>

                <div class="form-group">
                    <input type="submit" value="Cadastrar Paciente" name="btnCadastrar" class="btn-submit" />
                    <input type="hidden" name="id" />
                </div>
            </form>
        </div>
    </div>
</body>

</html>