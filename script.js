// JOGOS SEPARADOS POR PROVEDORA
const jogosPorProvedora = {
    "pgsoft": [
        "tigre", "touro", "coelho", "rato", "dragao", "fortunesnake",
        "doublefortune", "dragontiguer", "bikiniparadise", "piggygold",
        "luckypiggy", "caishenwins", "treeoffortune", "majongways",
        "secretsofcleopatra", "riseofapollo", "speedwinner", "hawaiiantiki",
        "ninjaraccoonfrenzy", "jackthegianthunter", "ganeshagold", "jungledelight", "circusdelight", 
        "ganeshafortune", "luckyneko", "hiphoppanda",
        "ninjavssamurai", "shaolinsoccer", "thairiverwonders",
        "waysoftheqilin", "prosperityfortunetree", "safariwilds",
        "wildheistcashout", "thegreaticescape", "wildbandito", "genies3wishes", 
        "wildbountyshowdown", "cocktailnights", "vampirescharm",
        "dreamsofmacau", "balivacation", "jurassickingdom",
        "asgardianrising", "cruiseroyale", "mafiamayhem", "dragonhatch", "supermarketspree", "midasfortune",
        "captainsbounty", "leprechaumriches", "treasuresofaztec",
        "heiststakes", "wildcoaster", "supergolfdrive",
        "ultimatestriker"
    ],
    
    "pragmatic": [
        "GatesofOlympus", "gatesofolympus1000", "SugarRush1000", "SweetBonanza1000", "BigBassSplash"
    ],
    
    "tada": [
    "luckyjaguar", "crazy777", "fortunegems", "fortunegems2", "fortunegems3",
    ],
    
    "wg": [
        // Adicione jogos da WG aqui quando tiver
    ]
};

// Função para detectar a extensão correta da imagem
function detectarExtensaoImagem(nome, provedora) {
    return new Promise((resolve) => {
        const extensoes = ['webp', 'png', 'jpg', 'jpeg'];
        let extensaoEncontrada = null;
        let tentativas = 0;

        function tentarProximaExtensao(index) {
            if (index >= extensoes.length) {
                resolve(extensaoEncontrada);
                return;
            }

            const ext = extensoes[index];
            const img = new Image();
            
            img.onload = function() {
                if (!extensaoEncontrada) {
                    extensaoEncontrada = ext;
                    console.log(`✅ Imagem encontrada: ${nome}.${ext} (${provedora})`);
                    resolve(extensaoEncontrada);
                }
            };
            
            img.onerror = function() {
                tentativas++;
                if (tentativas === extensoes.length && !extensaoEncontrada) {
                    console.log(`❌ Imagem não encontrada: ${nome} (${provedora})`);
                    resolve(null);
                }
                tentarProximaExtensao(index + 1);
            };
            
            img.src = `img/jogos_${provedora}/${nome}.${ext}`;
        }

        tentarProximaExtensao(0);
    });
}

// Função para carregar todos os jogos
async function carregarJogos() {
    const container = document.querySelector(".games-grid");
    
    // Limpa o container antes de adicionar novos jogos
    container.innerHTML = '<div class="loading">Carregando jogos...</div>';
    
    const todosJogos = [];
    
    // Prepara a lista de todos os jogos
    for (const [provedora, jogos] of Object.entries(jogosPorProvedora)) {
        for (const nome of jogos) {
            todosJogos.push({ nome, provedora });
        }
    }
    
    let jogosCarregados = 0;
    
    // Carrega cada jogo sequencialmente
    for (const jogo of todosJogos) {
        try {
            const percent = Math.floor(Math.random() * 100);
            const cor = percent >= 50 ? 'green' : 'red';
            
            // Detecta a extensão correta
            const extensao = await detectarExtensaoImagem(jogo.nome, jogo.provedora);
            
            if (extensao) {
                container.innerHTML += `
                    <div class="game-card" data-provider="${jogo.provedora}" style="display: none;">
                        <div class="game-image" style="background-image: url('img/jogos_${jogo.provedora}/${jogo.nome}.${extensao}');">
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
                // Se não encontrou a imagem, cria um card de fallback
                container.innerHTML += `
                    <div class="game-card" data-provider="${jogo.provedora}" style="display: none;">
                        <div class="game-image" style="background-color: #444; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 0.8rem; text-align: center;">
                            ${jogo.nome}<br><small>(imagem não encontrada)</small>
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
            
            jogosCarregados++;
            console.log(`Progresso: ${jogosCarregados}/${todosJogos.length} jogos carregados`);
            
        } catch (error) {
            console.error(`Erro ao carregar jogo ${jogo.nome}:`, error);
            jogosCarregados++;
        }
    }
    
    // Remove a mensagem de loading
    const loadingElement = container.querySelector('.loading');
    if (loadingElement) {
        loadingElement.remove();
    }
    
    // Aplica o filtro da PG Soft após carregar todos os jogos
    console.log(`✅ Todos os ${jogosCarregados} jogos foram processados`);
    setTimeout(() => {
        filterGames('pgsoft');
        atualizarBarras();
    }, 100);
}

// Filtro de Provedoras
function filterGames(provider) {
    const gameCards = document.querySelectorAll('.game-card');
    console.log(`🎮 Filtrando por ${provider}. Total de cards: ${gameCards.length}`);
    
    gameCards.forEach(card => {
        if (card.getAttribute('data-provider') === provider) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Inicialização quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    const providerButtons = document.querySelectorAll('.provider-btn');
    
    console.log('🚀 Página carregada, iniciando carregamento de jogos...');
    
    // Carrega os jogos quando a página estiver pronta
    carregarJogos();
    
    providerButtons.forEach(button => {
        button.addEventListener('click', function() {
            providerButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const selectedProvider = this.getAttribute('data-provider');
            console.log(`🔽 Provedora selecionada: ${selectedProvider}`);
            filterGames(selectedProvider);
        });
    });
});

// Funções de porcentagem
function gerarPorcentagens(qtd) {
    const porcentagens = [];
    for (let i = 0; i < qtd; i++) {
        const percent = Math.floor(Math.random() * 81) + 20;
        porcentagens.push(percent);
    }
    return porcentagens;
}

function atualizarBarras() {
    const barras = document.querySelectorAll('.progress-bar');
    const qtd = barras.length;

    console.log(`📊 Atualizando ${qtd} barras de progresso`);

    let data = localStorage.getItem('porcentagensBarras');
    let porcentagens, timestamp;

    if (data) {
        data = JSON.parse(data);
        porcentagens = data.valores;
        timestamp = new Date(data.timestamp);
    }

    const agora = new Date();
    const umaHora = 60 * 60 * 1000;

    if (!porcentagens || porcentagens.length !== qtd || (agora - timestamp) > umaHora) {
        porcentagens = gerarPorcentagens(qtd);
        localStorage.setItem('porcentagensBarras', JSON.stringify({
            valores: porcentagens,
            timestamp: agora.toISOString()
        }));
    }

    barras.forEach((bar, i) => {
        const percent = porcentagens[i];
        const cor = percent >= 50 ? 'green' : 'red';

        bar.innerHTML = `
            <div class="progress-fill" style="width:${percent}%; background-color:${cor};"></div>
            <div class="progress-text">${percent}%</div>
        `;
    });
}

// Adicione este CSS para a mensagem de loading
const style = document.createElement('style');
style.textContent = `
    .loading {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        color: #ffb400;
        font-size: 1.2rem;
    }
`;
document.head.appendChild(style);