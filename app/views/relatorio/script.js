document.addEventListener("DOMContentLoaded", () => {
  const canvas = document.getElementById("graficoReceita");

  if (!canvas) {
    return;
  }

  new Chart(canvas, {
    type: "line",

    data: {
      labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun"],

      datasets: [
        {
          label: "Receita (R$)",

          data: [1200, 1900, 1500, 2500, 3000, 2800],

          borderColor: " #3f2d52",

          backgroundColor: "rgba(63, 45, 82, 0.2)",

          fill: true,

          tension: 0.4,
        },
      ],
    },

    options: {
      responsive: true,
      maintainAspectRatio: false,

      plugins: {
        legend: {
          position: "top",
          display: true,
        },
      },

      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
});

const ctxServicos = document.getElementById("graficoServicos");

new Chart(ctxServicos, {
  type: "doughnut",

  data: {
    labels: [
      "Corte de Cabelo",
      "Barba Completa",
      "Manicure",
      "Tratamento Facial",
    ],

    datasets: [
      {
        data: [58, 42, 28, 15],

        backgroundColor: [
          " #8b5cf6",
          " #10b981",
          " #fef3c0",
          " #c78ab8",
        ],

        borderWidth: 0,
      },
    ],
  },

  options: {
    responsive: true,

    plugins: {
      legend: {
        display: false,
      },
    },

    cutout: "70%",
  },
});