/**
 * =========================================================================
 * SLOT SENSE - SCRIPT PRINCIPAL (INTEGRADO À API MySQL)
 * Este arquivo consome dados do backend dinâmico, mantém os jogos vitais,
 * e administra visualmente as porcentagens e filtragens na tela.
 * =========================================================================
 */

/**
 * =====================================================================
 * GESTÃO DAS BARRAS DE PROBABILIDADE (PERCENTUAIS)
 * =====================================================================
 */
function getPorcentagemJogo(nomeJogo) {
    let data = localStorage.getItem('porcentagensJogos_v2');
    let porcentagens = {};
    let timestamp = null;

    if (data) {
        try {
            const parsed = JSON.parse(data);
            porcentagens = parsed.valores || {};
            timestamp = new Date(parsed.timestamp);
        } catch(e) {}
    }

    const agora = new Date();
    const umaHora = 60 * 60 * 1000;

    if (!timestamp || (agora - timestamp) > umaHora) {
        porcentagens = {}; 
        timestamp = agora;
    }

    if (!porcentagens[nomeJogo]) {
        porcentagens[nomeJogo] = Math.floor(Math.random() * 81) + 20; 
        
        localStorage.setItem('porcentagensJogos_v2', JSON.stringify({
            valores: porcentagens,
            timestamp: timestamp.toISOString()
        }));
    }

    return porcentagens[nomeJogo];
}

/**
 * =====================================================================
 * LÓGICA DE LOAD VIA API (BANCO DE DADOS)
 * =====================================================================
 * Essa função requisita ao backend a lista completa de jogos com as
 * respectivas provas, imagens e IDs, sem precisarmos lidar com array fixo.
 */
async function carregarJogos() {
    const container = document.querySelector(".games-grid");
    container.innerHTML = '<div class="loading">Buscando os melhores jogos no servidor... ⏳</div>';
    
    try {
        // Faz a requisição na API PHP que puxa direto do MySQL
        const response = await fetch('slot-backend/public/api/jogos_get.php');
        if (!response.ok) throw new Error("Falha na comunicação com a API");
        
        const todosJogos = await response.json();
        
        // Se ainda não houver nenhum jogo cadastrado no Banco de Dados pelo Admin
        if (todosJogos.length === 0) {
            container.innerHTML = '<div class="loading" style="color:#ffb400;">Nenhum jogo cadastrado no momento. Acesse o Painel Admin para adicionar!</div>';
            return;
        }

        // Reconstrói o HTML usando os dados dinâmicos
        const cardsHTMLArray = todosJogos.map((jogo) => {
            // Se vier sem imagem salva no DB, cai num try safe,
            // caso contrário joga direto a imagem salva sem adivinhações
            const urlImagem = jogo.imagem ? `img/jogos_${jogo.provedora}/${jogo.imagem}` : null;
            
            // Checa a porcentagem (do Cache ou Gerada) pra cor visual
            // Se o jogo estivesse vindo com porcentagem FORÇADA do DB (jogo.porcentagem),
            // usaríamos ela, mas o layout gosta de parecer "vivo", então mesclamos a logica
            const percentDB = parseInt(jogo.porcentagem, 10);
            const useDBPercent = percentDB > 0 && percentDB !== 50; 
            const percent = useDBPercent ? percentDB : getPorcentagemJogo(jogo.nome);
            
            const cor = percent >= 50 ? '#44d06b' : '#ff4444'; 
            
            if (urlImagem) {
                return `
                    <div class="game-card" data-provider="${jogo.provedora}" style="display: none;">
                        <div class="game-image" style="background-image: url('${urlImagem}');">
                            <a href="https://www.510bet.xyz/?dl=7jkp70" target="_blank">
                                <button class="play-button">JOGAR</button>
                            </a>
                        </div>
                        <div class="progress-bar" data-percent="${percent}">
                            <div class="progress-fill" style="width:${percent}%; background-color:${cor};"></div>
                            <div class="progress-text">${percent}%</div>
                        </div>
                    </div>
                `;
            } else {
                return `
                    <div class="game-card" data-provider="${jogo.provedora}" style="display: none;">
                        <div class="game-image" style="background-color: #444; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 0.8rem; text-align: center;">
                            ${jogo.nome}<br><small>(imagem pendente)</small>
                            <a href="https://www.510bet.xyz/?dl=7jkp70" target="_blank">
                                <button class="play-button">JOGAR</button>
                            </a>
                        </div>
                        <div class="progress-bar" data-percent="${percent}">
                            <div class="progress-fill" style="width:${percent}%; background-color:${cor};"></div>
                            <div class="progress-text">${percent}%</div>
                        </div>
                    </div>
                `;
            }
        });
        
        container.innerHTML = cardsHTMLArray.join('');
        console.log(`✅ ${todosJogos.length} jogos carregados via Banco de Dados com sucesso!`);
        
        // Exibe por padrão os jogos da PG Soft assim que terminal o total load
        setTimeout(() => { filterGames('pgsoft'); }, 50);
        
    } catch (error) {
        console.error(error);
        container.innerHTML = '<div class="loading" style="color:red;">Erro ao buscar jogos. O servidor do banco de dados está online?</div>';
    }
}

/**
 * =====================================================================
 * LÓGICA DE FILTRAGEM (TABS)
 * =====================================================================
 */
function filterGames(provider) {
    const gameCards = document.querySelectorAll('.game-card');
    
    // Se não encontrou nenhum card da provedora em questão, mostra um aviso
    let encontrouAlgum = false;
    
    gameCards.forEach(card => {
        if (card.getAttribute('data-provider') === provider) {
            card.style.display = 'block'; 
            encontrouAlgum = true;
        } else {
            card.style.display = 'none'; 
        }
    });

    // Bônus: Se a base de dados não tiver retornado nada pra essa provedora
    const container = document.querySelector(".games-grid");
    const existingMsg = document.getElementById('empty-provider-msg');
    
    if (!encontrouAlgum && gameCards.length > 0) {
        if (!existingMsg) {
            const msg = document.createElement('div');
            msg.id = 'empty-provider-msg';
            msg.className = 'loading';
            msg.textContent = 'Sem jogos cadastrados nesta provedora no momento.';
            container.appendChild(msg);
        }
    } else if (existingMsg) {
        existingMsg.remove();
    }
}

/**
 * =====================================================================
 * INICIALIZAÇÃO
 * =====================================================================
 */
document.addEventListener('DOMContentLoaded', function() {
    const providerButtons = document.querySelectorAll('.provider-btn');
    
    carregarJogos();
    
    providerButtons.forEach(button => {
        button.addEventListener('click', function() {
            providerButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const selectedProvider = this.getAttribute('data-provider');
            filterGames(selectedProvider);
        });
    });
});