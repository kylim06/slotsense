async function atualizarPorcentagensAutomaticas() {
    // Chama API que recalcula porcentagens com regras inteligentes
    await fetch("/slot/slot-backend/public/api/jogos_auto_porcentagem.php");
}

async function carregarJogos() {
    const grid = document.querySelector(".games-grid");

    grid.innerHTML = `
        <p style="color:white; text-align:center">Carregando jogos...</p>
    `;

    // Busca jogos no backend
    const req = await fetch("/slot/slot-backend/public/api/jogos_get.php");
    const jogos = await req.json();

    grid.innerHTML = "";

    if (!Array.isArray(jogos) || jogos.length === 0) {
        grid.innerHTML = `
            <p style="color:white; text-align:center">Nenhum jogo cadastrado ainda.</p>
        `;
        return;
    }

    // Renderiza cards
    jogos.forEach(j => {

        const cor = j.porcentagem >= 50 ? "green" : "red";

        grid.innerHTML += `
            <div class="game-card">

                <div class="game-image" style="background-image: url('${j.imagem}');">
                    <a href="/slot/slot-backend/public/api/go.php?id=${j.id}">
                        <button class="play-button">JOGAR</button>
                    </a>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill" style="width:${j.porcentagem}%; background-color:${cor};"></div>
                    <div class="progress-text">${j.porcentagem}%</div>
                </div>

            </div>
        `;
    });
}

// Executa ao carregar página
window.onload = async () => {
    await atualizarPorcentagensAutomaticas(); // gera porcentagens automáticas
    await carregarJogos(); // carrega jogos
};
