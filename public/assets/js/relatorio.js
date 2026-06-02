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

});