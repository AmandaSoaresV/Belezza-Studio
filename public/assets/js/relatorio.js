document.addEventListener("DOMContentLoaded", () => {

  const MESES = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun"];

  const dadosReceita = [1200, 1800, 1500, 2100, 3000, 2800];

  const dadosServicos = {
    labels: ["Escova Progressiva", "Dia da Noiva", "Tratamento Facial Gold Therapy", "Tratamento Facial"],
    valores: [58, 42, 28, 15],
    cores: ["#8b5cf6", "#10b981", "#fef3c0", "#c78ab8"],
  };

  const configReceita = {
    type: "line",
    data: {
      labels: MESES,
      datasets: [{
        label: "Receita (R$)",
        data: dadosReceita,
        borderColor: "#3f2d52",
        backgroundColor: "rgba(63, 45, 82, 0.2)",
        fill: true,
        tension: 0.4,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: "top", display: true },
      },
      scales: {
        y: { beginAtZero: true },
      },
    },
  };

  const configServicos = {
    type: "doughnut",
    data: {
      labels: dadosServicos.labels,
      datasets: [{
        data: dadosServicos.valores,
        backgroundColor: dadosServicos.cores,
        borderWidth: 0,
      }],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
      },
      cutout: "70%",
    },
  };

  const graficos = [
    { id: "graficoReceita",  config: configReceita  },
    { id: "graficoServicos", config: configServicos },
  ];

  graficos.forEach(({ id, config }) => {
    const canvas = document.getElementById(id);
    if (canvas) new Chart(canvas.getContext("2d"), config);
  });

  const btnExportar = document.querySelector(".btn-outline-warning");

  btnExportar.addEventListener("click", () => {
    btnExportar.disabled = true;
    btnExportar.innerHTML = `Gerando... <i class="ph ph-spinner"></i>`;

    setTimeout(() => {
      const agora = new Date();
      const dataFormatada = agora.toLocaleDateString("pt-BR", {
        day: "2-digit",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });

      const imgReceita = document.getElementById("graficoReceita").toDataURL("image/png");
      const imgServicos = document.getElementById("graficoServicos").toDataURL("image/png");

      const servicos = [
        { nome: "Escova Progressiva", agend: 58, pct: "100%", cor: "#8b5cf6" },
        { nome: "Dia da Noiva", agend: 42, pct: "72%", cor: "#10b981" },
        { nome: "Trat. Facial Gold Therapy", agend: 28, pct: "48%", cor: "#f59e0b" },
        { nome: "Tratamento Facial", agend: 15, pct: "26%", cor: "#c78ab8" },
      ];

      const barras = servicos.map((s, i) => `
        <div style="margin-bottom: 14px;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
            <span style="font-size: 12px; font-weight: 600;">${i + 1}. ${s.nome}</span>
            <span style="font-size: 12px; color: #888;">${s.agend} agend.</span>
          </div>
          <div style="background: #f0f0f0; border-radius: 99px; height: 7px;">
            <div style="width: ${s.pct}; background: ${s.cor}; height: 7px; border-radius: 99px;"></div>
          </div>
        </div>
      `).join("");

      const resumo = [
        { label: "Taxa de comparecimento", valor: "94%" },
        { label: "Avaliação média", valor: "4.8 ★" },
        { label: "Clientes recorrentes", valor: "67%" },
        { label: "Tempo médio de atendimento", valor: "45 min" },
      ];

      const cardsResumo = resumo.map(r => `
        <div style="flex: 1; text-align: center; border: 1px solid #eee; border-radius: 10px; padding: 12px 8px;">
          <div style="font-size: 16px; font-weight: bold; color: #3f2d52;">${r.valor}</div>
          <div style="font-size: 11px; color: #888; margin-top: 4px;">${r.label}</div>
        </div>
      `).join("");

      const container = document.createElement("div");
      container.innerHTML = `
        <div style="padding: 36px; font-family: sans-serif; background: #fff; color: #1a1a1a;">

          <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid #3f2d52; padding-bottom: 16px; margin-bottom: 28px;">
            <div>
              <h1 style="font-size: 20px; font-weight: bold; margin: 0 0 4px;">Painel Administrativo</h1>
              <p style="font-size: 12px; color: #888; margin: 0;">Visão geral do desempenho do salão</p>
            </div>
            <p style="font-size: 11px; color: #aaa; margin: 0;">Gerado em: ${dataFormatada}</p>
          </div>

          <h2 style="font-size: 13px; font-weight: bold; color: #3f2d52; margin-bottom: 4px;">Receita por período</h2>
          <p style="font-size: 11px; color: #aaa; margin-bottom: 12px;">Receita diária acumulada nos últimos 30 dias</p>
          <img src="${imgReceita}" style="width: 100%; border-radius: 8px; margin-bottom: 28px;" />

          <div style="display: flex; gap: 32px; margin-bottom: 28px;">
            <div style="flex: 1;">
              <h2 style="font-size: 13px; font-weight: bold; color: #3f2d52; margin-bottom: 4px;">Serviços Mais Populares</h2>
              <p style="font-size: 11px; color: #aaa; margin-bottom: 14px;">Ranking por número de agendamentos</p>
              ${barras}
            </div>
            <div style="width: 180px; display: flex; align-items: center;">
              <img src="${imgServicos}" style="width: 100%;" />
            </div>
          </div>

          <div style="border-top: 1px solid #eee; padding-top: 20px;">
            <h2 style="font-size: 13px; font-weight: bold; color: #3f2d52; margin-bottom: 14px;">Resumo do período</h2>
            <div style="display: flex; gap: 12px;">
              ${cardsResumo}
            </div>
          </div>

        </div>
      `;

      const opcoes = {
        margin: 0,
        filename: `relatorio-${agora.toISOString().slice(0, 10)}.pdf`,
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true, logging: false },
        jsPDF: { unit: "mm", format: "a4", orientation: "portrait" },
      };

      html2pdf()
        .set(opcoes)
        .from(container)
        .save()
        .then(() => {
          btnExportar.disabled = false;
          btnExportar.innerHTML = `Exportar Relatório <i class="ph ph-download"></i>`;
        });
    }, 300);
  });
});