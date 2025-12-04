<?php
// =========================================================================
// CONFIGURAÇÕES DO BANCO DE DADOS
// POR FAVOR, ALTERE ESTAS VARIÁVEIS PARA AS SUAS CREDENCIAIS REAIS!
// =========================================================================

// Nome do host onde o MySQL está rodando (geralmente 'localhost' em ambientes de desenvolvimento)
$host = 'localhost'; 

// Nome do banco de dados que criamos
$dbname = 'agro_viana_estoque'; 

// Usuário do MySQL (Geralmente 'root' em ambientes locais como XAMPP/WAMP)
$user = 'root'; 

// Senha do MySQL (Geralmente vazia '' ou 'root' em ambientes locais. MUDAR PARA PRODUÇÃO!)
$password = ''; 

// =========================================================================
// CONEXÃO PDO (PHP Data Objects)
// =========================================================================

try {
    // String de Conexão (DSN - Data Source Name)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    
    // Cria a instância de PDO
    $pdo = new PDO($dsn, $user, $password);
    
    // Define o modo de erro: Lançar exceções em caso de falha (melhor para tratamento de erros)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Define o modo padrão de fetch para arrays associativos (FETCH_ASSOC)
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // O objeto $pdo está pronto para ser usado em todas as operações de banco de dados
    
} catch (PDOException $e) {
    // Em caso de erro na conexão, exibe a mensagem de erro e interrompe o script
    // Em produção, esta mensagem deve ser genérica por segurança.
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}