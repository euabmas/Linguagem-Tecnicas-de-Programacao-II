// Carrega as variáveis de ambiente do ficheiro .env
require('dotenv').config({ path: './.env' });

const express = require('express');
const mysql = require('mysql');
const app = express();

// Middlewares para JSON e CORS
app.use(express.json());
app.use((req, res, next) => {
    // Permite que o Front-end (rodando local) acesse a API
    res.header('Access-Control-Allow-Origin', '*'); 
    res.header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
    res.header('Access-Control-Allow-Headers', 'Content-Type');
    next();
});

// Configuração da Conexão MySQL
const connection = mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
    port: 3306 // Porta padrão
});

// 1. Conectar ao Banco de Dados
function connectToDatabase() {
    return new Promise((resolve, reject) => {
        connection.connect(err => {
            if (err) {
                console.error('Erro ao conectar ao MySQL:', err.stack);
                return reject(err);
            }
            console.log('Conectado ao MySQL como id ' + connection.threadId);
            resolve();
        });
    });
}

// ROTA 1: GET - Buscar todos os produtos
app.get('/api/produtos', async (req, res) => {
    try {
        const [rows] = await connection.promise().query('SELECT * FROM Produtos');
        res.status(200).json(rows);
    } catch (error) {
        console.error('Erro ao buscar produtos:', error);
        res.status(500).json({ error: 'Erro interno do servidor ao buscar produtos.' });
    }
});

// ROTA 2: POST - Cadastrar novo produto
app.post('/api/produtos', async (req, res) => {
    const { nome, unidade } = req.body;
    try {
        const query = 'INSERT INTO Produtos (nome, unidade_medida) VALUES (?, ?)';
        const [result] = await connection.promise().query(query, [nome, unidade]);
        res.status(201).json({ success: true, id: result.insertId });
    } catch (error) {
        console.error('Erro ao cadastrar produto:', error);
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({ error: 'Produto já cadastrado.' });
        }
        res.status(500).json({ error: 'Erro interno ao cadastrar produto.' });
    }
});

// ROTA 3: GET - Buscar todos os talhões
app.get('/api/talhoes', async (req, res) => {
    try {
        const [rows] = await connection.promise().query('SELECT * FROM Talhoes');
        res.status(200).json(rows);
    } catch (error) {
        console.error('Erro ao buscar talhões:', error);
        res.status(500).json({ error: 'Erro interno do servidor ao buscar talhões.' });
    }
});

// ROTA 4: GET - Buscar Histórico de Movimentações
app.get('/api/movimentacoes', async (req, res) => {
    try {
        // Junta dados de movimentação com o nome do produto
        const query = `
            SELECT 
                m.data_movimento, m.tipo, m.quantidade, p.nome AS nome_produto, p.unidade_medida, t.nome AS nome_talhao
            FROM Movimentacoes m
            JOIN Produtos p ON m.id_produto = p.id_produto
            LEFT JOIN Talhoes t ON m.id_talhao = t.id_talhao
            ORDER BY m.data_movimento DESC 
            LIMIT 10`;
        const [rows] = await connection.promise().query(query);
        res.status(200).json(rows);
    } catch (error) {
        console.error('Erro ao buscar movimentações:', error);
        res.status(500).json([]); // Retorna vazio em caso de erro
    }
});

// ROTA 5: POST - Cadastrar Aplicação (Simplificado)
app.post('/api/movimentacoes/aplicacao', async (req, res) => {
    const { id_produto, id_talhao, quantidade } = req.body;
    const tipo = 'APLICACAO';
    
    // Simplificado: apenas registra a movimentação e DEPOIS atualiza o saldo (em uma transação)
    try {
        await connection.promise().beginTransaction(); // Inicia transação
        
        // 1. Verificar saldo (Omitido para simplicidade, mas é essencial em produção)
        
        // 2. Insere a movimentação
        const insertQuery = 'INSERT INTO Movimentacoes (id_produto, id_talhao, tipo, quantidade) VALUES (?, ?, ?, ?)';
        await connection.promise().query(insertQuery, [id_produto, id_talhao, tipo, quantidade]);

        // 3. Atualiza o saldo do produto
        const updateQuery = 'UPDATE Produtos SET saldo_atual = saldo_atual - ? WHERE id_produto = ?';
        await connection.promise().query(updateQuery, [quantidade, id_produto]);

        await connection.promise().commit(); // Confirma transação
        res.status(201).json({ success: true, message: 'Aplicação registrada com sucesso.' });
    } catch (error) {
        await connection.promise().rollback(); // Reverte em caso de erro
        console.error('Erro ao registrar aplicação:', error);
        res.status(500).json({ error: 'Erro interno ao registrar aplicação.' });
    }
});

// 6. Iniciar o Servidor
connectToDatabase()
    .then(() => {
        app.listen(process.env.PORT, () => {
            console.log(`Servidor rodando em http://localhost:${process.env.PORT}`);
        });
    })
    .catch(err => {
        console.log('Não foi possível iniciar o servidor devido ao erro do DB.');
    });