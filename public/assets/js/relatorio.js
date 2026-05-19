document.addEventListener("DOMContentLoaded", () => {
  const canvasReceita = document.getElementById("graficoReceita");
  const canvasServicos = document.getElementById("graficoServicos");

  if (canvasReceita) {
    new Chart(canvasReceita.getContext("2d"), {
      type: "line",
      data: {
        labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun"],
        datasets: [
          {
            label: "Receita (R$)",
            data: [1200, 1900, 1500, 2500, 3000, 2800],
            borderColor: "#3f2d52",
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
  }

  if (canvasServicos) {
    new Chart(canvasServicos.getContext("2d"), {
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
            backgroundColor: ["#8b5cf6", "#10b981", "#fef3c0", "#c78ab8"],
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
  }
});
