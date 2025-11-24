document.addEventListener('DOMContentLoaded', () => {
    
    // --- CONFIGURAÇÃO DA API ---
    const API_BASE_URL = 'http://localhost:3000/api';
    
    // Variáveis que armazenarão os dados do banco de dados
    let produtos = [];
    let talhoes = [];
    let historico = [];

    // --- REFERÊNCIAS DOM (iguais ao original) ---
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.content-section');
    const aplicacaoForm = document.getElementById('aplicacao-form');
    const addTalhaoForm = document.getElementById('add-talhao-form');
    const addProdutoForm = document.getElementById('add-produto-form');
    // ... (restante das referências DOM) ...

    // --- FUNÇÕES DE UTILIDADE (iguais ao original) ---
    const formatNumber = (num) => parseFloat(num).toFixed(2).replace('.', ',');
    const findProduct = (id) => produtos.find(p => p.id === id);
    const findTalhao = (id) => talhoes.find(t => t.id === id);
    const getTimestamp = () => new Date().toLocaleString('pt-BR');

    // --- FUNÇÃO PRINCIPAL PARA CARREGAR TODOS OS DADOS DA API ---
    async function loadAllData() {
        try {
            // 1. Busca Produtos
            const prodResponse = await fetch(`${API_BASE_URL}/produtos`);
            produtos = await prodResponse.json();
            
            // 2. Busca Talhões
            const talhaoResponse = await fetch(`${API_BASE_URL}/talhoes`);
            talhoes = await talhaoResponse.json();
            
            // 3. Busca Histórico (Movimentações)
            const historicoResponse = await fetch(`${API_BASE_URL}/movimentacoes`);
            historico = await historicoResponse.json();
            
            // 4. Calcular Consumo Total (Simulação do que o backend faria)
            // OBS: O backend deve retornar os dados de consumo em um sistema real.
            // Para este exemplo, vamos manter o consumo zero ou buscar de outra forma.

            // Atualiza o UI
            updateSelects();
            renderHomeDashboard();
            renderInventario(document.getElementById('inventario-produtos-detalhe'), true);
            renderPlanejamento();

        } catch (error) {
            console.error('Erro ao carregar dados da API:', error);
            alert('Não foi possível conectar ao servidor (Node.js/MySQL). Verifique o console.');
        }
    }
    
    // --- LÓGICA DE NAVEGAÇÃO SPA (igual ao original) ---
    // ... (código da função showSection e event listeners) ...
    function showSection(sectionId) {
        // ... (código original para mostrar seção) ...
        // Chama loadAllData() no início para garantir que os dados estejam frescos
        loadAllData();
    }
    
    // --- RENDERIZAÇÃO (Adaptada para usar dados do DB) ---

    function updateSelects() {
        // IDs e nomes foram alterados para refletir as colunas do DB (id_talhao, id_produto)
        const talhaoOptions = talhoes.map(t => `<option value="${t.id_talhao}">${t.nome} (${formatNumber(t.hectares)} HAC)</option>`).join('');
        const produtoOptions = produtos.map(p => `<option value="${p.id_produto}">${p.nome} (${p.unidade_medida})</option>`).join('');

        document.getElementById('aplicacao-talhao').innerHTML = talhaoOptions;
        document.getElementById('aplicacao-produto').innerHTML = produtoOptions;
        document.getElementById('gestao-produto').innerHTML = produtoOptions;
    }

    // Adapte as funções renderInventario, renderPlanejamento, renderHistorico e renderHomeDashboard
    // para usar as variáveis 'produtos', 'talhoes' e 'historico' carregadas pela API.

    // Exemplo de adaptação para renderHistorico:
    function renderHistorico(limit = 10) {
        const tbody = document.getElementById('registros-tbody-home');
        tbody.innerHTML = '';
        
        [...historico].slice(0, limit).forEach(item => {
            const row = tbody.insertRow();
            // A API já retorna o nome do produto e do talhão
            const location = item.nome_talhao || 'ESTOQUE CENTRAL'; 
            const typeText = item.tipo.replace('_', ' ');

            // ... (Lógica de cores e formatação - igual ao original) ...

            row.innerHTML = `
                <td>${new Date(item.data_movimento).toLocaleString('pt-BR')}</td>
                <td>${typeText}</td>
                <td>${item.nome_produto}</td>
                <td>${location}</td>
                <td>${formatNumber(item.quantidade)} ${item.unidade_medida}</td>
            `;
        });
    }

    // --- LÓGICA DE TRANSAÇÕES (AGORA USANDO A API) ---

    // A. LANÇAMENTO DE APLICAÇÃO (POST para a API)
    aplicacaoForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id_produto = parseInt(document.getElementById('aplicacao-produto').value);
        const id_talhao = parseInt(document.getElementById('aplicacao-talhao').value);
        const quantidade = parseFloat(document.getElementById('aplicacao-quantidade').value);
        
        try {
            const response = await fetch(`${API_BASE_URL}/movimentacoes/aplicacao`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_produto, id_talhao, quantidade })
            });
            
            if (response.ok) {
                alert(`Sucesso! Aplicação registrada e estoque atualizado.`);
                await loadAllData(); // Recarrega todos os dados do DB
            } else {
                const result = await response.json();
                alert(`ERRO ao registrar aplicação: ${result.error || response.statusText}`);
            }
            aplicacaoForm.reset();
        } catch (error) {
            console.error('Erro de rede:', error);
            alert('Erro de comunicação com o servidor. Verifique se o Node.js está rodando.');
        }
    });

    // C. ADICIONAR TALHÃO (POST para a API)
    addTalhaoForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const nome = document.getElementById('novo-talhao-nome').value.toUpperCase().trim();
        const hectares = parseFloat(document.getElementById('novo-talhao-hac').value);
        
        try {
            const response = await fetch(`${API_BASE_URL}/talhoes`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nome, hectares, info_solidos: 'A DEFINIR', total_previsto_ton: 'A DEFINIR' })
            });

            if (response.ok) {
                alert(`Talhão ${nome} adicionado com sucesso ao DB!`);
                await loadAllData(); 
            } else {
                const result = await response.json();
                alert(`Erro no cadastro: ${result.error}`);
            }
            addTalhaoForm.reset();
        } catch (error) {
            console.error('Erro na comunicação com a API:', error);
            alert('Erro de rede ao tentar cadastrar o talhão. Verifique o servidor Node.js.');
        }
    });

    // D. ADICIONAR PRODUTO (POST para a API) - Conforme seu snippet original
    addProdutoForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const nome = document.getElementById('novo-produto-nome').value.trim();
        const unidade = document.getElementById('novo-produto-unidade').value.trim();
        
        try {
            const response = await fetch(`${API_BASE_URL}/produtos`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nome, unidade })
            });

            if (response.ok) {
                alert(`Produto ${nome} adicionado ao DB!`);
                await loadAllData(); // Recarrega produtos, atualizando dropdowns
            } else {
                const result = await response.json();
                alert(`Erro no cadastro: ${result.error}`);
            }
            addProdutoForm.reset();
        } catch (error) {
            console.error('Erro na comunicação com a API:', error);
            alert('Erro de rede ao tentar cadastrar o produto. Verifique o servidor Node.js.');
        }
    });


    // B. GESTÃO DE ESTOQUE (Gestão de Estoque - Mantida local por enquanto)
    // OBS: Em um sistema completo, esta lógica também faria um POST para uma rota
    // '/api/movimentacoes/entrada' ou 'ajuste' e o backend cuidaria da atualização do saldo.
    // Por enquanto, mantenha a lógica original (simples)
    // ... (manter o event listener do gestaoEstoqueForm original, mas usando loadAllData no final) ...
    
    
    // --- INICIALIZAÇÃO GERAL ---
    loadAllData(); // Começa carregando os dados do banco de dados
    document.getElementById('aplicacao-data').valueAsDate = new Date(); 
});