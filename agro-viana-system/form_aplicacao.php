<?php
require_once 'db_connect.php';
session_start();

$is_edit = false;
$id_aplicacao = null;
$dados = ['talhao' => '', 'hectares' => '', 'data_plantio' => '', 'produto_solido_id' => '', 'taxa_solido_kg_ha' => '', 'produto_dessecacao_id' => '', 'data_dessecacao_inicial' => '', 'produto_pos_plantio_id' => '', 'data_pos_plantio' => '',];
$title = "Adicionar Novo Item";

$insumos_list = [];
try {
    $stmt = $pdo->query("SELECT id_insumo, nome_insumo, unidade FROM estoque_insumos WHERE status = 'ATIVO' ORDER BY nome_insumo");
    $insumos_list = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro fatal ao carregar insumos: " . $e->getMessage());
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $is_edit = true;
    $id_aplicacao = (int)$_GET['id'];
    $title = "Alterar Registro (ID: $id_aplicacao)";
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM talhoes_aplicacoes WHERE id_aplicacao = ?");
        $stmt->execute([$id_aplicacao]);
        $dados_edicao = $stmt->fetch();
        
        if ($dados_edicao) {
            $dados = array_merge($dados, $dados_edicao);
        } else {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => "Registro não encontrado para edição."];
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['feedback'] = ['type' => 'error', 'message' => "Erro ao buscar dados para edição: " . $e->getMessage()];
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title><?= $title ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1><?= $title ?></h1>
        <a href="index.php" class="btn-back">Voltar para a Lista</a>
    </header>

    <main class="form-container">
        <form action="crud_handler.php" method="POST">
            
            <?php if ($is_edit): ?>
                <input type="hidden" name="id_aplicacao" value="<?= $id_aplicacao ?>">
                <input type="hidden" name="acao" value="alterar">
            <?php else: ?>
                <input type="hidden" name="acao" value="adicionar">
            <?php endif; ?>

            <fieldset>
                <legend>Dados Básicos do Talhão</legend>
                <label for="talhao">TALHÃO:</label>
                <input type="text" id="talhao" name="talhao" value="<?= htmlspecialchars($dados['talhao']) ?>" required>

                <label for="hectares">HACTARES (ha):</label>
                <input type="number" step="0.01" id="hectares" name="hectares" value="<?= htmlspecialchars($dados['hectares']) ?>" required>
            </fieldset>

            <fieldset>
                <legend>Aplicações Sólidas e Plantio</legend>

                <label for="produto_solido_id">INSUMO SÓLIDO (Fertilizantes):</label>
                <select id="produto_solido_id" name="produto_solido_id" required>
                    <option value="">-- Selecione o Produto --</option>
                    <?php foreach ($insumos_list as $insumo): ?>
                        <option value="<?= $insumo['id_insumo'] ?>" 
                            <?= ((int)$dados['produto_solido_id'] === (int)$insumo['id_insumo']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($insumo['nome_insumo']) ?> (<?= $insumo['unidade'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label for="taxa_solido_kg_ha">TAXA SÓLIDO (kg/ha):</label>
                <input type="number" step="0.01" id="taxa_solido_kg_ha" name="taxa_solido_kg_ha" value="<?= htmlspecialchars($dados['taxa_solido_kg_ha']) ?>">

                <label for="data_plantio">DATA APLIQUE/PLANTE:</label>
                <input type="date" id="data_plantio" name="data_plantio" value="<?= htmlspecialchars($dados['data_plantio']) ?>">
                
            </fieldset>
            
            <fieldset>
                <legend>Dessecação e Pós Plantio</legend>
                
                <label for="data_dessecacao_inicial">DATA DESSECAÇÃO INICIAL:</label>
                <input type="date" name="data_dessecacao_inicial" value="<?= htmlspecialchars($dados['data_dessecacao_inicial']) ?>">

                <label for="produto_dessecacao_id">PRODUTO DESSECAÇÃO:</label>
                <select name="produto_dessecacao_id">
                    <option value="">-- N/A --</option>
                     <?php foreach ($insumos_list as $insumo): ?>
                        <option value="<?= $insumo['id_insumo'] ?>" 
                            <?= ((int)$dados['produto_dessecacao_id'] === (int)$insumo['id_insumo']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($insumo['nome_insumo']) ?> (<?= $insumo['unidade'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label for="data_pos_plantio">DATA PÓS PLANTIO:</label>
                <input type="date" name="data_pos_plantio" value="<?= htmlspecialchars($dados['data_pos_plantio']) ?>">
            </fieldset>

            <?php if ($is_edit): ?>
                <fieldset>
                    <legend>Detalhes da Alteração (Histórico)</legend>
                    <label for="comentario">Comentário/Detalhes da Alteração (Opcional):</label>
                    <textarea id="comentario" name="comentario" rows="5"></textarea>
                </fieldset>
            <?php endif; ?>

            <button type="submit" class="btn-submit"><?= $is_edit ? 'Salvar Alterações' : 'Salvar Novo Item' ?></button>
        </form>
    </main>
</body>
</html>