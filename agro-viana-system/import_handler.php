<?php
require_once 'db_connect.php';
session_start();

// Simulação de Usuário Logado
$usuario_logado = $_SESSION['usuario'] ?? 'ADMIN_SYSTEM'; 

if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Erro no upload do arquivo CSV.'];
    header("Location: import.php");
    exit();
}

$file_path = $_FILES['csv_file']['tmp_name'];
$produto_solido_id = (int)$_POST['produto_solido_id'];

$linhas_processadas = 0;
$total_consumo_solido = 0.00;
$registros_inseridos = 0;
$erros_importacao = [];

// Funções de Limpeza Específicas para sua Planilha
function clean_hectares($hac_str) {
    // Remove 'HAC', 'ha', e espaços, e substitui vírgula por ponto.
    $cleaned = str_replace([' HAC', 'HA', 'ha', ' '], '', strtoupper(trim($hac_str)));
    $cleaned = str_replace(',', '.', $cleaned);
    return (float)$cleaned;
}

function clean_rate($solidos_str) {
    // Extrai o número antes de 'kg' (Ex: '140 kg = 9 ton' -> '140')
    preg_match('/(\d+)\s*kg/i', $solidos_str, $matches);
    return isset($matches[1]) ? (float)$matches[1] : 0.00;
}

// Inicia a Transação para segurança
try {
    $pdo->beginTransaction(); 

    if (($handle = fopen($file_path, "r")) !== FALSE) {
        
        // Pula as 2 linhas de cabeçalho do AGRO VIANA APLICAÇÕES.csv
        fgetcsv($handle, 1000, ';');
        fgetcsv($handle, 1000, ';');

        // Loop principal para processar cada linha
        while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
            $linhas_processadas++;
            
            // Assume que o TALHÃO está na primeira coluna (índice 0)
            $talhao_raw = $data[0] ?? '';
            
            // Pula linhas vazias ou linhas de separação
            if (empty(trim($talhao_raw)) || trim($talhao_raw) === '.') {
                continue; 
            }
            
            // Mapeamento e Limpeza dos Dados
            $talhao = trim(strtoupper($talhao_raw));
            $hectares = clean_hectares($data[1] ?? 0);
            $taxa_solido_kg_ha = clean_rate($data[2] ?? 0);
            $data_plantio_raw = $data[4] ?? ''; // Coluna APLIQUE/PLANTE

            // Validação Mínima
            if ($hectares <= 0 || $taxa_solido_kg_ha <= 0 || empty($talhao)) {
                $erros_importacao[] = "Linha {$linhas_processadas}: Talhão '{$talhao}' com dados inválidos (Hactares/Taxa 0). Ignorado.";
                continue;
            }

            // Cálculo do Consumo
            $total_utilizado = $hectares * $taxa_solido_kg_ha;
            $total_consumo_solido += $total_utilizado;

            // Formatação da Data (Assume formato YYYY-MM-DD se a planilha estiver preenchida corretamente)
            $data_plantio = !empty($data_plantio_raw) ? date('Y-m-d', strtotime($data_plantio_raw)) : NULL;

            // Insere na tabela talhoes_aplicacoes
            $sql_insert = "INSERT INTO talhoes_aplicacoes (
                talhao, hectares, produto_solido_id, taxa_solido_kg_ha, 
                total_solido_utilizado, data_plantio, status, data_criacao
            ) VALUES (?, ?, ?, ?, ?, ?, 'ATIVO', NOW())";
            
            $stmt = $pdo->prepare($sql_insert);
            $stmt->execute([
                $talhao, $hectares, $produto_solido_id, $taxa_solido_kg_ha, 
                $total_utilizado, $data_plantio
            ]);
            
            $registros_inseridos++;
        }
        fclose($handle);
        
        // 2. Atualiza o Estoque Mestre (SUBTRAI o total acumulado)
        if ($total_consumo_solido > 0) {
            $sql_estoque = "UPDATE estoque_insumos 
                            SET estoque_atual = estoque_atual - ? 
                            WHERE id_insumo = ?";
            $stmt = $pdo->prepare($sql_estoque);
            $stmt->execute([$total_consumo_solido, $produto_solido_id]);
        
            // 3. Registra no Histórico
            $sql_log = "INSERT INTO historico_atividades (usuario, tabela_afetada, id_registro_afetado, acao, detalhes) 
                        VALUES (?, 'estoque_insumos', ?, ?, ?)";
            $stmt = $pdo->prepare($sql_log);
            $stmt->execute([
                $usuario_logado, 
                $produto_solido_id, 
                "IMPORTAR",
                "Importação CSV de Aplicações concluída. Total de {$registros_inseridos} registros inseridos. Consumo total: {$total_consumo_solido} kg. Produto ID: {$produto_solido_id}."
            ]);
        }
        
        // Se chegou até aqui, o COMMIT é seguro
        $pdo->commit();

        $msg = "Importação concluída! Foram inseridos **{$registros_inseridos}** registros de aplicações. Consumo total de insumo sólido: **" . number_format($total_consumo_solido, 2, ',', '.') . " kg**.";
        if (!empty($erros_importacao)) {
            $msg .= "<br><br>Atenção: Erros ignorados:<br>" . implode("<br>", $erros_importacao);
            $_SESSION['feedback'] = ['type' => 'error', 'message' => $msg];
        } else {
            $_SESSION['feedback'] = ['type' => 'success', 'message' => $msg];
        }

    } else {
        throw new Exception("Não foi possível abrir o arquivo para leitura.");
    }

} catch (Exception $e) {
    // ROLLBACK em caso de falha
    if ($pdo->inTransaction()) {
        $pdo->rollBack(); 
    }
    $_SESSION['feedback'] = ['type' => 'error', 'message' => "Erro Fatal durante a Importação. Nenhum dado foi salvo. Detalhe: " . $e->getMessage()];
}

header("Location: index.php");
exit();