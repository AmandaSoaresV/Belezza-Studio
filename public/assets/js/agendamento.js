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

  var horarios = $('.botao-horario--livre');
  var horarioEscolhido = $('#horarioEscolhido');

  horarios.on('click', function () {
    horarios.removeClass('botao-horario--selecionado');
    $(this).addClass('botao-horario--selecionado');
    horarioEscolhido.val($(this).text().trim());

    var campoHorario = horarioEscolhido.parsley();
    if (campoHorario) campoHorario.reset();
  });

  form.on('submit', function (evento) {
    if (parsleyForm && !parsleyForm.validate()) {
      evento.preventDefault();
    }
  });
});
