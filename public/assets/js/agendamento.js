document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formAgendamento");
  if (!form) return;

  const etapas = Array.from(form.querySelectorAll(".etapa-agendamento"));
  const indicadores = Array.from(document.querySelectorAll(".passo-item"));

  function mostrarEtapa(numero) {
    etapas.forEach((etapa) => {
      etapa.classList.toggle("d-none", Number(etapa.dataset.etapa) !== numero);
    });

    indicadores.forEach((item) => {
      const passo = Number(item.dataset.passo);
      item.classList.toggle("passo-item--ativo", passo === numero);
      item.classList.toggle("passo-item--concluido", passo < numero);
    });
  }

  form.querySelectorAll(".btn-etapa-avancar").forEach((botao) => {
    botao.addEventListener("click", () => {
      const etapaAtual = botao.closest(".etapa-agendamento");
      const camposObrigatorios = etapaAtual.querySelectorAll("[required]");

      for (const campo of camposObrigatorios) {
        if (!campo.checkValidity()) {
          campo.reportValidity();
          return;
        }
      }

      mostrarEtapa(Number(etapaAtual.dataset.etapa) + 1);
    });
  });

  form.querySelectorAll(".btn-etapa-voltar").forEach((botao) => {
    botao.addEventListener("click", () => {
      const etapaAtual = botao.closest(".etapa-agendamento");
      mostrarEtapa(Number(etapaAtual.dataset.etapa) - 1);
    });
  });

  const horarios = document.querySelectorAll(".botao-horario--livre");
  const horarioEscolhido = document.getElementById("horarioEscolhido");

  horarios.forEach((botao) => {
    botao.addEventListener("click", () => {
      horarios.forEach((h) => h.classList.remove("botao-horario--selecionado"));
      botao.classList.add("botao-horario--selecionado");
      if (horarioEscolhido) horarioEscolhido.value = botao.textContent.trim();
    });
  });
});
