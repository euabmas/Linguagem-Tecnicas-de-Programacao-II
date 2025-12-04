<?php
require_once 'db_connect.php'; 
session_start();

$historico = [];
try {
    // Busca todo o histórico, incluindo o campo 'detalhes'
    $stmt = $pdo->prepare("SELECT * FROM historico_atividades ORDER BY data_hora DESC");
    $stmt->execute();
    $historico = $stmt->fetchAll();
} catch (PDOException $e) {
    die("<p style='color: red;'>Erro ao buscar histórico: " . $e->getMessage() . "</p>");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Histórico de Atividades</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Histórico de Atividades</h1>
        <a href="index.php" class="btn-back">Voltar para a Lista</a>
    </header>

    <main>
        <h2>Log Completo do Sistema</h2>
        
        <?php if (count($historico) > 0): ?>
        <div class="table-responsive">
            <table class="historico-table">
                <thead>
                    <tr>
                        <th>Data/Hora</th>
                        <th>Usuário</th>
                        <th>Ação</th>
                        <th>Tabela Afetada</th>
                        <th>ID Afetado</th>
                        <th>Detalhes/Comentário</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico as $item): ?>
                    <tr>
                        <td data-label="Data/Hora:"><?= date('d/m/Y H:i:s', strtotime($item['data_hora'])) ?></td>
                        <td data-label="Usuário:"><?= htmlspecialchars($item['usuario']) ?></td>
                        <td data-label="Ação:"><?= htmlspecialchars($item['acao']) ?></td>
                        <td data-label="Tabela:"><?= htmlspecialchars($item['tabela_afetada']) ?></td>
                        <td data-label="ID Afetado:"><?= htmlspecialchars($item['id_registro_afetado']) ?></td>
                        <td data-label="Detalhes:"><?= htmlspecialchars($item['detalhes']) ?></td> 
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p>Nenhuma atividade registrada ainda.</p>
        <?php endif; ?>
    </main>
</body>
</html>