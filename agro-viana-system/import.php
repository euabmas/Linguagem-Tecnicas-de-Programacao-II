<?php
session_start();
require_once 'db_connect.php';

$feedback_message = '';
$feedback_type = '';
if (isset($_SESSION['feedback'])) {
    $feedback_message = $_SESSION['feedback']['message'];
    $feedback_type = $_SESSION['feedback']['type'];
    unset($_SESSION['feedback']);
}

$insumos_solidos = [];
if ($pdo) {
    try {
        // Busca apenas insumos sólidos para o campo de seleção
        $stmt = $pdo->prepare("SELECT id_insumo, nome_insumo, unidade FROM estoque_insumos WHERE status = 'ATIVO' AND tipo_insumo = 'SOLIDO' ORDER BY nome_insumo");
        $stmt->execute();
        $insumos_solidos = $stmt->fetchAll();
    } catch (PDOException $e) {
        $feedback_message = "Erro ao carregar insumos: " . $e->getMessage();
        $feedback_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>AGRO VIANA - Importar Aplicações CSV</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Importar Aplicações de Planilha</h1>
        <nav>
            <a href="index.php" class="btn-back">Voltar</a>
        </nav>
    </header>

    <main class="form-container">
        <?php if (!empty($feedback_message)): ?>
            <div class="feedback <?= $feedback_type ?>">
                <?= htmlspecialchars($feedback_message) ?>
            </div>
        <?php endif; ?>
        
        <h2>Instruções de Importação</h2>
        <p>A planilha deve ser um arquivo CSV delimitado por ponto e vírgula (;) e ter as colunas na seguinte ordem: **Talhão**, **Hectares**, **Taxa Sólido (kg/ha)** e **Data Plantio** (formato YYYY-MM-DD). A primeira linha é ignorada, assumindo que seja o nome das colunas na linha 3.</p>

        <?php if (count($insumos_solidos) == 0): ?>
            <div class="feedback error">
                Não há insumos **sólidos** ativos no estoque! Por favor, cadastre um insumo sólido na tabela `estoque_insumos` antes de importar.
            </div>
        <?php else: ?>
        <form action="import_handler.php" method="POST" enctype="multipart/form-data">

            <fieldset>
                <legend>Configurações da Importação</legend>

                <label for="produto_solido_id">1. Insumo Sólido Padrão (Usado em todas as linhas):</label>
                <select id="produto_solido_id" name="produto_solido_id" required>
                    <option value="">-- Selecione o Produto --</option>
                    <?php foreach ($insumos_solidos as $insumo): ?>
                        <option value="<?= $insumo['id_insumo'] ?>">
                            <?= htmlspecialchars($insumo['nome_insumo']) ?> (Estoque: <?= $insumo['unidade'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label for="csv_file">2. Selecione o Arquivo CSV:</label>
                <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
                
            </fieldset>

            <button type="submit" class="btn-submit">Importar Dados</button>
        </form>
        <?php endif; ?>
    </main>
</body>
</html>