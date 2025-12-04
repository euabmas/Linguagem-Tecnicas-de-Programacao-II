<?php
require_once 'db_connect.php'; 
session_start();

$usuario_logado = $_SESSION['usuario'] ?? 'ADMIN_SYSTEM'; 
$acao = $_REQUEST['acao'] ?? ''; 

if (!$acao) {
    header("Location: index.php");
    exit();
}

$msg_sucesso = '';
$msg_erro = '';

try {
    $pdo->beginTransaction(); 
    
    // Preparação do SQL para Log, que inclui o campo 'detalhes'
    $sql_log = "INSERT INTO historico_atividades (usuario, tabela_afetada, id_registro_afetado, acao, detalhes) 
                VALUES (?, 'talhoes_aplicacoes', ?, ?, ?)";


    // 1. ADICIONAR NOVO ITEM (CREATE)
    if ($acao === 'adicionar') {
        
        $talhao = trim($_POST['talhao']);
        $hectares = (float)$_POST['hectares'];
        $produto_solido_id = (int)$_POST['produto_solido_id'];
        $taxa_solido_kg_ha = (float)$_POST['taxa_solido_kg_ha'];
        $data_plantio = $_POST['data_plantio'];
        
        $total_solido_utilizado = $hectares * $taxa_solido_kg_ha;
        
        // Insere na tabela de Aplicações
        $sql_insert = "INSERT INTO talhoes_aplicacoes (
            talhao, hectares, produto_solido_id, taxa_solido_kg_ha, 
            total_solido_utilizado, data_plantio, status
        ) VALUES (?, ?, ?, ?, ?, ?, 'ATIVO')";
        
        $stmt = $pdo->prepare($sql_insert);
        $stmt->execute([
            $talhao, $hectares, $produto_solido_id, $taxa_solido_kg_ha, 
            $total_solido_utilizado, $data_plantio
        ]);
        
        $id_novo = $pdo->lastInsertId();

        // Atualiza o Estoque Mestre (SUBTRAI)
        $sql_estoque = "UPDATE estoque_insumos 
                        SET estoque_atual = estoque_atual - ? 
                        WHERE id_insumo = ?";
        $stmt = $pdo->prepare($sql_estoque);
        $stmt->execute([$total_solido_utilizado, $produto_solido_id]);
        
        // Registra no Histórico (USANDO DETALHES)
        $log_detalhes = "Adicionado novo Talhão: {$talhao}. Débito de estoque: " . number_format($total_solido_utilizado, 2, ',', '.') . "kg.";
        $stmt_log = $pdo->prepare($sql_log);
        $stmt_log->execute([$usuario_logado, $id_novo, "CRIAR", $log_detalhes]);

        $msg_sucesso = "Registro de aplicação e estoque atualizados com sucesso.";
    } 
    
    // 2. ALTERAR (UPDATE)
    elseif ($acao === 'alterar') {
        
        $id_aplicacao = (int)$_POST['id_aplicacao'];
        // Captura o comentário opcional (vem do POST)
        $comentario = trim($_POST['comentario'] ?? ''); 
        
        // Busca os Dados Antigos para Compensação
        $stmt = $pdo->prepare("SELECT talhao, produto_solido_id, total_solido_utilizado FROM talhoes_aplicacoes WHERE id_aplicacao = ?");
        $stmt->execute([$id_aplicacao]);
        $antigo = $stmt->fetch();
        
        if (!$antigo) {
            throw new Exception("Registro para alteração não encontrado.");
        }
        
        $talhao = trim($_POST['talhao']);
        $hectares = (float)$_POST['hectares'];
        $produto_solido_id_novo = (int)$_POST['produto_solido_id'];
        $taxa_solido_kg_ha = (float)$_POST['taxa_solido_kg_ha'];
        $data_plantio = $_POST['data_plantio'];

        $novo_total_solido_utilizado = $hectares * $taxa_solido_kg_ha;
        $antigo_total_solido_utilizado = (float)$antigo['total_solido_utilizado'];
        $produto_solido_id_antigo = (int)$antigo['produto_solido_id'];

        // PASSO A: Reverte Uso Antigo (ADICIONA)
        $sql_reverte = "UPDATE estoque_insumos 
                        SET estoque_atual = estoque_atual + ? 
                        WHERE id_insumo = ?";
        $stmt = $pdo->prepare($sql_reverte);
        $stmt->execute([$antigo_total_solido_utilizado, $produto_solido_id_antigo]);

        // PASSO B: Subtrai Novo Uso
        $sql_aplica_novo = "UPDATE estoque_insumos 
                            SET estoque_atual = estoque_atual - ? 
                            WHERE id_insumo = ?";
        $stmt = $pdo->prepare($sql_aplica_novo);
        $stmt->execute([$novo_total_solido_utilizado, $produto_solido_id_novo]);

        // Atualiza a tabela de Aplicações
        $sql_update = "UPDATE talhoes_aplicacoes SET
            talhao = ?, hectares = ?, produto_solido_id = ?, taxa_solido_kg_ha = ?, 
            total_solido_utilizado = ?, data_plantio = ?
        WHERE id_aplicacao = ?";
        
        $stmt = $pdo->prepare($sql_update);
        $stmt->execute([
            $talhao, $hectares, $produto_solido_id_novo, $taxa_solido_kg_ha, 
            $novo_total_solido_utilizado, $data_plantio, $id_aplicacao
        ]);

        // Registra no Histórico (USANDO DETALHES)
        $log_detalhes = "Alteração no Talhão {$talhao}. Estoque compensado. Uso antigo: " . number_format($antigo_total_solido_utilizado, 2, ',', '.') . "kg. Novo uso: " . number_format($novo_total_solido_utilizado, 2, ',', '.') . "kg.";
        
        if (!empty($comentario)) {
            $log_detalhes .= " Comentário: " . htmlspecialchars($comentario); // Adiciona o comentário
        }

        $stmt_log = $pdo->prepare($sql_log);
        $stmt_log->execute([$usuario_logado, $id_aplicacao, "ALTERAR", $log_detalhes]);
        
        $msg_sucesso = "Alterações no registro e compensação de estoque realizadas com sucesso!";
    } 
    
    // 3. INATIVAR (UPDATE STATUS)
    elseif ($acao === 'inativar') {
        
        $id_aplicacao = (int)$_REQUEST['id'];
        // Captura o comentário opcional (vem da URL)
        $comentario = trim($_REQUEST['comentario'] ?? ''); 
        
        // 3.1. Busca o nome do talhão para o log
        $stmt_talhao = $pdo->prepare("SELECT talhao FROM talhoes_aplicacoes WHERE id_aplicacao = ?");
        $stmt_talhao->execute([$id_aplicacao]);
        $talhao_nome = $stmt_talhao->fetchColumn();

        // 3.2. Atualiza o status
        $sql_inativar = "UPDATE talhoes_aplicacoes SET status = 'INATIVO' WHERE id_aplicacao = ?";
        $stmt = $pdo->prepare($sql_inativar);
        $stmt->execute([$id_aplicacao]);

        // 3.3. Registra no Histórico (USANDO DETALHES)
        $log_acao = "INATIVAR";
        $log_detalhes = "Talhão '{$talhao_nome}' (ID: {$id_aplicacao}) inativado. ";
        
        if (!empty($comentario)) {
            $log_detalhes .= "Comentário: " . htmlspecialchars($comentario);
        } else {
            $log_detalhes .= "Nenhum comentário fornecido.";
        }

        $stmt_log = $pdo->prepare($sql_log);
        $stmt_log->execute([$usuario_logado, $id_aplicacao, $log_acao, $log_detalhes ]);

        $msg_sucesso = "Registro inativado com sucesso!";
    }

    $pdo->commit();

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack(); 
    }
    $msg_erro = "Erro durante a operação: " . $e->getMessage() . ". Nenhuma alteração foi salva no banco de dados.";
}

// FEEDBACK E REDIRECIONAMENTO
if (!empty($msg_erro)) {
    $_SESSION['feedback'] = ['type' => 'error', 'message' => $msg_erro];
} elseif (!empty($msg_sucesso)) {
    $_SESSION['feedback'] = ['type' => 'success', 'message' => $msg_sucesso];
}

header("Location: index.php");
exit();