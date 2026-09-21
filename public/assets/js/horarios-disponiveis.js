$(document).ready(function () {
  var campoServico = $('#id_servico');
  var campoProfissional = $('#id_profissional');
  var campoData = $('#data');
  var caixaHorarios = $('#horarios');
  var horarioEscolhido = $('#horarioEscolhido');

  if (!campoData.length || !caixaHorarios.length) return;

  var horarioInicial = horarioEscolhido.val();
  var requisicaoAtual = null;

  function limparEscolha() {
    horarioEscolhido.val('');
  }

  function mostrarAviso(texto) {
    caixaHorarios.html(
      $('<p></p>').addClass('texto-lead mb-0 aviso-horarios').text(texto)
    );
  }

  function desenharHorarios(horarios) {
    caixaHorarios.empty();

    horarios.forEach(function (slot) {
      var botao = $('<button></button>')
        .attr('type', 'button')
        .addClass('botao-horario')
        .text(slot.horario);

      if (slot.disponivel) {
        botao.addClass('botao-horario--livre');
      } else {
        botao.addClass('botao-horario--ocupado')
          .attr('disabled', 'disabled')
          .attr('title', 'Horário já reservado');
      }

      if (slot.disponivel && slot.horario === horarioInicial) {
        botao.addClass('botao-horario--selecionado');
        horarioEscolhido.val(slot.horario);
      }

      caixaHorarios.append(botao);
    });
  }

  function carregarHorarios() {
    var idServico = campoServico.val();
    var idProfissional = campoProfissional.val();
    var data = campoData.val();

    if (!idServico || !idProfissional) {
      limparEscolha();
      mostrarAviso('Escolha o serviço e o profissional primeiro.');
      return;
    }

    if (!data) {
      limparEscolha();
      mostrarAviso('Escolha uma data para ver os horários livres.');
      return;
    }

    mostrarAviso('Carregando horários…');

    if (requisicaoAtual) requisicaoAtual.abort();

    requisicaoAtual = $.getJSON('/api/horarios', {
      id_servico: idServico,
      id_profissional: idProfissional,
      data: data,
    })
      .done(function (resposta) {
        limparEscolha();

        if (!resposta.horarios || !resposta.horarios.length) {
          mostrarAviso(resposta.mensagem || 'Nenhum horário disponível nesse dia.');
          return;
        }

        desenharHorarios(resposta.horarios);

        if (resposta.mensagem) {
          caixaHorarios.append(
            $('<p></p>').addClass('texto-lead mb-0 mt-2 aviso-horarios').text(resposta.mensagem)
          );
        }
      })
      .fail(function (erro) {
        if (erro.statusText === 'abort') return;
        limparEscolha();
        mostrarAviso('Não foi possível carregar os horários, tente novamente.');
      })
      .always(function () {
        horarioInicial = '';
        requisicaoAtual = null;
      });
  }

  caixaHorarios.on('click', '.botao-horario--livre', function () {
    caixaHorarios.find('.botao-horario').removeClass('botao-horario--selecionado');
    $(this).addClass('botao-horario--selecionado');
    horarioEscolhido.val($(this).text().trim());

    var validacao = horarioEscolhido.parsley && horarioEscolhido.parsley();
    if (validacao) validacao.reset();
  });

  campoServico.on('change', carregarHorarios);
  campoProfissional.on('change', carregarHorarios);
  campoData.on('change', carregarHorarios);

  carregarHorarios();
});
