-- SCRIPT SQL: CRIAÇÃO DO BANCO DE DADOS AGRO VIANA

-- PASSO 1: CRIAÇÃO E SELEÇÃO DO BANCO DE DADOS
DROP DATABASE IF EXISTS agro_viana_db;
CREATE DATABASE agro_viana_db;
USE agro_viana_db;

-- ----------------------------------------------------
-- 1. TABELA PRODUTOS (ESTOQUE)
-- ----------------------------------------------------
CREATE TABLE Produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY, -- Chave primária
    nome VARCHAR(100) NOT NULL UNIQUE,         -- Nome do insumo (único)
    unidade_medida VARCHAR(10) NOT NULL,       -- L (Litros) ou Kg (Quilos)
    saldo_atual DECIMAL(10, 2) NOT NULL DEFAULT 0.00, -- Estoque atual
    estoque_inicial DECIMAL(10, 2) NOT NULL DEFAULT 0.00 -- Registro do estoque inicial
);

-- ----------------------------------------------------
-- 2. TABELA TALHÕES (ÁREAS DE CONSUMO)
-- ----------------------------------------------------
CREATE TABLE Talhoes (
    id_talhao INT AUTO_INCREMENT PRIMARY KEY,  -- Chave primária
    nome VARCHAR(100) NOT NULL UNIQUE,         -- Nome do Talhão (Ex: SEDE)
    hectares DECIMAL(10, 2) NOT NULL,          -- Tamanho em Hectares
    info_solidos TEXT,                         -- Informações adicionais de insumos sólidos
    total_previsto_ton TEXT                    -- Previsão de colheita ou consumo (texto)
);

-- ----------------------------------------------------
-- 3. TABELA MOVIMENTAÇÕES (HISTÓRICO/RASTREABILIDADE)
-- ----------------------------------------------------
CREATE TABLE Movimentacoes (
    id_movimentacao INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT NOT NULL,
    id_talhao INT, -- Opcional: NULL para ENTRADA/AJUSTE DE ESTOQUE
    tipo VARCHAR(50) NOT NULL, -- (EX: ENTRADA, AJUSTE_SAIDA, APLICACAO)
    quantidade DECIMAL(10, 2) NOT NULL,
    data_movimento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Definição das chaves estrangeiras
    FOREIGN KEY (id_produto) REFERENCES Produtos(id_produto),
    FOREIGN KEY (id_talhao) REFERENCES Talhoes(id_talhao)
);

-- ----------------------------------------------------
-- DADOS INICIAIS (PARA TESTE)
-- ----------------------------------------------------
INSERT INTO Produtos (nome, unidade_medida, estoque_inicial, saldo_atual) VALUES
('Herbicida (P001)', 'L', 500.00, 500.00),
('Fungicida (P002)', 'L', 100.00, 100.00),
('Fertilizante X', 'Kg', 0.00, 0.00);

INSERT INTO Talhoes (nome, hectares, info_solidos, total_previsto_ton) VALUES
('SEDE', 65.00, '140 kg', '9 ton'),
('PRETO/MAURO', 75.00, 'KCL (250 Kg), Fósforo (75 kg)', 'KCL (19 ton), Fósforo (6 ton)'),
('DOCA', 50.00, '140 kg', '7 ton');