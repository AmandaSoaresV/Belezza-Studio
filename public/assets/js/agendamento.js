$(document).ready(function () {
  var form = $('#formAgendamento');
  if (!form.length) return;

  var etapas = form.find('.etapa-agendamento');
  var indicadores = $('.passo-item');
  var parsleyForm = form.parsley();

  function mostrarEtapa(numero) {
    etapas.each(function () {
      $(this).toggleClass('d-none', Number($(this).data('etapa')) !== numero);
    });

    indicadores.each(function () {
      var passo = Number($(this).data('passo'));
      $(this).toggleClass('passo-item--ativo', passo === numero);
      $(this).toggleClass('passo-item--concluido', passo < numero);
    });
  }

  function validarEtapa(numero) {
    if (parsleyForm) {
      return parsleyForm.validate({ group: 'passo' + numero });
    }
    return true;
  }

  form.find('.btn-etapa-avancar').on('click', function () {
    var etapaAtual = $(this).closest('.etapa-agendamento');
    var numeroEtapa = Number(etapaAtual.data('etapa'));

    if (!validarEtapa(numeroEtapa)) return;

    mostrarEtapa(numeroEtapa + 1);
  });

  form.find('.btn-etapa-voltar').on('click', function () {
    var etapaAtual = $(this).closest('.etapa-agendamento');
    mostrarEtapa(Number(etapaAtual.data('etapa')) - 1);
  });

  // A seleção de horário vive em horarios-disponiveis.js, que monta a grade
  // do dia sob demanda e usa evento delegado (os botões não existem no load).

  form.on('submit', function (evento) {
    if (parsleyForm && !parsleyForm.validate()) {
      evento.preventDefault();
    }
  });
});
