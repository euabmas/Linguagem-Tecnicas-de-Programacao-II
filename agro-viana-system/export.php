<?php
require_once 'db_connect.php'; 
session_start();

// Determina o tipo de exportação (padrão é 'aplicacoes')
$export_type = $_GET['tipo'] ?? 'aplicacoes';

// Define o nome do arquivo com base na data e no tipo
$filename = "AGRO_VIANA_ESTOQUE_" . strtoupper($export_type) . "_" . date('Ymd_His') . ".csv";

// 1. Configura os Headers HTTP para forçar o download do arquivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Cria um "arquivo virtual" para o output
$output = fopen('php://output', 'w');

try {
    // =========================================================================
    // EXPORTAÇÃO 1: INVENTÁRIO (Saldos Atuais)
    // =========================================================================
    if ($export_type === 'inventario') {
        
        // Define o cabeçalho do CSV
        $header = [
            'ID_INSUMO', 
            'NOME_INSUMO', 
            'UNIDADE', 
            'ESTOQUE_ATUAL', 
            'STATUS'
        ];
        fputcsv($output, $header, ';');

        // Busca os dados
        $stmt = $pdo->query("SELECT id_insumo, nome_insumo, unidade, estoque_atual, status 
                             FROM estoque_insumos 
                             ORDER BY nome_insumo ASC");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Formata o estoque para garantir duas casas decimais
            $data_export = [
                $row['id_insumo'],
                $row['nome_insumo'],
                $row['unidade'],
                number_format($row['estoque_atual'], 2, ',', '.'),
                $row['status']
            ];
            fputcsv($output, $data_export, ';');
        }

    // =========================================================================
    // EXPORTAÇÃO 2: APLICAÇÕES (Detalhes por Talhão - Mais complexa)
    // =========================================================================
    } else { // Padrão 'aplicacoes'
        
        // Define o cabeçalho do CSV, incluindo os nomes dos produtos
        $header = [
            'TALHÃO', 'HACTARES (ha)', 
            'PRODUTO_SOLIDO', 'TAXA_SOLIDO (kg/ha)', 'TOTAL_UTILIZADO', 
            'DATA_PLANTE', 'PRODUTO_FUNG1', 'DATA_FUNG1', 
            'STATUS_REGISTRO'
            // Adicionar mais colunas de fungicidas e dessecação conforme necessário
        ];
        fputcsv($output, $header, ';');

        // Busca os dados, juntando com a tabela de insumos para obter o nome do produto
        $sql = "SELECT 
                    ta.talhao, ta.hectares, ta.taxa_solido_kg_ha, ta.total_solido_utilizado, 
                    ta.data_plantio, ta.status,
                    ins_s.nome_insumo AS produto_solido,
                    ins_f1.nome_insumo AS produto_fung1
                FROM talhoes_aplicacoes ta
                LEFT JOIN estoque_insumos ins_s ON ta.produto_solido_id = ins_s.id_insumo
                LEFT JOIN estoque_insumos ins_f1 ON ta.produto_fung1_id = ins_f1.id_insumo
                ORDER BY ta.talhao ASC";

        $stmt = $pdo->query($sql);
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data_export = [
                $row['talhao'],
                number_format($row['hectares'], 2, ',', '.'),
                $row['produto_solido'] ?? 'N/A',
                number_format($row['taxa_solido_kg_ha'], 2, ',', '.'),
                number_format($row['total_solido_utilizado'], 2, ',', '.'),
                ($row['data_plantio'] ? date('d/m/Y', strtotime($row['data_plantio'])) : ''),
                $row['produto_fung1'] ?? 'N/A',
                ($row['data_fung1'] ? date('d/m/Y', strtotime($row['data_fung1'])) : ''),
                $row['status']
            ];
            fputcsv($output, $data_export, ';');
        }
    }

} catch (PDOException $e) {
    // Não é ideal lançar erros em arquivos de exportação, mas é útil para debug.
    die("Erro ao gerar o CSV: " . $e->getMessage());
} finally {
    fclose($output);
    exit;
}