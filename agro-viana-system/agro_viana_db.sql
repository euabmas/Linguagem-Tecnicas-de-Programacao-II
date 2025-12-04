-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Dez-2025 às 15:35
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `agro_viana_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `movimentacoes`
--

CREATE TABLE `movimentacoes` (
  `id_movimentacao` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_talhao` int(11) DEFAULT NULL,
  `tipo_movimento` varchar(50) NOT NULL,
  `quantidade` decimal(10,2) NOT NULL,
  `data_movimento` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `unidade_medida` varchar(10) NOT NULL,
  `saldo_atual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estoque_inicial` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `nome`, `unidade_medida`, `saldo_atual`, `estoque_inicial`) VALUES
(1, 'Herbicida (P001)', 'L', '500.00', '500.00'),
(2, 'Fungicida (P002)', 'L', '100.00', '100.00'),
(3, 'Fertilizante X', 'Kg', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `talhoes`
--

CREATE TABLE `talhoes` (
  `id_talhao` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `hectares` decimal(10,2) NOT NULL,
  `info_solidos` text DEFAULT NULL,
  `total_previsto_ton` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `talhoes`
--

INSERT INTO `talhoes` (`id_talhao`, `nome`, `hectares`, `info_solidos`, `total_previsto_ton`) VALUES
(1, 'SEDE', '65.00', '140 kg', '9 ton'),
(2, 'DORGIVAL', '37.00', '140 kg', '5 ton');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  ADD PRIMARY KEY (`id_movimentacao`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_talhao` (`id_talhao`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `talhoes`
--
ALTER TABLE `talhoes`
  ADD PRIMARY KEY (`id_talhao`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  MODIFY `id_movimentacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `talhoes`
--
ALTER TABLE `talhoes`
  MODIFY `id_talhao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `movimentacoes`
--
ALTER TABLE `movimentacoes`
  ADD CONSTRAINT `movimentacoes_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`),
  ADD CONSTRAINT `movimentacoes_ibfk_2` FOREIGN KEY (`id_talhao`) REFERENCES `talhoes` (`id_talhao`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
