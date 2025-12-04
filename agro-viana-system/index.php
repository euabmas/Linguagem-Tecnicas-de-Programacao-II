<?php
session_start(); // Inicia a sessão
require_once 'db_connect.php'; // Inclui a conexão PDO

// 1. Lógica para capturar e exibir a mensagem de feedback
$feedback_message = '';
$feedback_type = '';
if (isset($_SESSION['feedback'])) {
    $feedback_message = $_SESSION['feedback']['message'];
    $feedback_type = $_SESSION['feedback']['type'];
    unset($_SESSION['feedback']); // Limpa a mensagem
}

// 2. Lógica para buscar os dados dos Talhões ATIVOS
$talhoes = [];
try {
    $stmt = $pdo->prepare("SELECT 
        ta.*, 
        ins.nome_insumo AS nome_solido 
    FROM 
        talhoes_aplicacoes ta
    LEFT JOIN 
        estoque_insumos ins ON ta.produto_solido_id = ins.id_insumo
    WHERE 
        ta.status = 'ATIVO' 
    ORDER BY 
        ta.talhao ASC");
    $stmt->execute();
    $talhoes = $stmt->fetchAll();
} catch (PDOException $e) {
    $feedback_message = "Erro ao buscar aplicações: " . $e->getMessage();
    $feedback_type = 'error';
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>AGRO VIANA - Controle de Estoque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Controle de Aplicações e Estoque</h1>
        <nav>
            <a href="form_aplicacao.php" class="btn-primary">Adicionar Novo Item</a>
            <a href="historico.php">Histórico de Atividades</a>
            <a href="import.php">Importar CSV</a>
            <a href="export.php?tipo=aplicacoes">Baixar Aplicações (CSV)</a>
            <a href="export.php?tipo=inventario">Baixar Inventário (CSV)</a>
        </nav>
    </header>

    <main>
        <?php if (!empty($feedback_message)): ?>
            <div class="feedback <?= $feedback_type ?>">
                <?= htmlspecialchars($feedback_message) ?>
            </div>
        <?php endif; ?>
        
        <h2>Aplicações Ativas por Talhão</h2>
        
        <?php if (count($talhoes) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>TALHÃO</th>
                        <th>HACTARES</th>
                        <th>SOLIDOS (Produto)</th>
                        <th>TOTAL UTILIZADO</th>
                        <th>DATA PLANTIO</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($talhoes as $t): ?>
                    <tr>
                        <td data-label="Talhão:"><?= htmlspecialchars($t['talhao']) ?></td>
                        <td data-label="Hactares:"><?= number_format($t['hectares'], 2, ',', '.') ?> ha</td>
                        <td data-label="Sólido:"><?= htmlspecialchars($t['nome_solido'] ?? 'N/A') ?></td>
                        <td data-label="Total Utilizado:"><?= number_format($t['total_solido_utilizado'], 2, ',', '.') ?> kg</td>
                        <td data-label="Data Plantio:"><?= $t['data_plantio'] ? date('d/m/Y', strtotime($t['data_plantio'])) : 'N/A' ?></td>
                        <td>
                            <a href="form_aplicacao.php?id=<?= $t['id_aplicacao'] ?>" class="btn-edit">Alterar</a>
                            <a href="#" class="btn-delete" 
                               onclick="return handleInativar(<?= $t['id_aplicacao'] ?>, '<?= htmlspecialchars(addslashes($t['talhao'])) ?>');">Inativar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p>Nenhum registro de aplicação ativo encontrado.</p>
        <?php endif; ?>

    </main>

    <script>
    /**
     * Funcao para confirmar a inativacao e solicitar um comentario.
     */
    function handleInativar(id, talhao) {
        // 1. Confirmação
        if (!confirm('Tem certeza que deseja inativar o talhão ' + talhao + '?')) {
            return false;
        }
        
        // 2. Solicita o comentário opcional
        const comentario = prompt('Por favor, adicione um comentário sobre o motivo da inativação: (Opcional)');
        
        let url = 'crud_handler.php?acao=inativar&id=' + id;
        
        // 3. Adiciona o comentário à URL se for fornecido
        if (comentario !== null && comentario.trim() !== '') {
            url += '&comentario=' + encodeURIComponent(comentario);
        }
        
        // 4. Redireciona para o CRUD handler
        window.location.href = url;
        return false; // Previne o comportamento padrão do link
    }
    </script>
</body>
</html>