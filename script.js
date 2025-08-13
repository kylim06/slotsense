function gerarPorcentagens(qtd) {
  const porcentagens = [];
  for (let i = 0; i < qtd; i++) {
    const percent = Math.floor(Math.random() * 81) + 20; // 20 a 100
    porcentagens.push(percent);
  }
  return porcentagens;
}
function atualizarBarras() {
  const barras = document.querySelectorAll('.progress-bar');
  const qtd = barras.length;

  let data = localStorage.getItem('porcentagensBarras');
  let porcentagens, timestamp;

  if (data) {
    data = JSON.parse(data);
    porcentagens = data.valores;
    timestamp = new Date(data.timestamp);
  }

  const agora = new Date();
  const umaHora = 60 * 60 * 1000;

  if (!porcentagens || (agora - timestamp) > umaHora) {
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

window.onload = () => {
  atualizarBarras();
  setInterval(atualizarBarras, 3600000); // Atualiza a cada 1 hora
};
