$(document).ready(function () {
  var campoServico = $('#id_servico');
  var campoProfissional = $('#id_profissional');

  if (!campoServico.length || !campoProfissional.length) return;

  var textoSemServico = 'Escolha o serviço primeiro';
  var textoSemProfissional = 'Nenhum profissional disponível para este serviço';
  var opcoesOriginais = campoProfissional.find('option[value!=""]').clone();

  function montarPlaceholder(texto) {
    return $('<option></option>')
      .attr('value', '')
      .attr('disabled', 'disabled')
      .attr('selected', 'selected')
      .text(texto);
  }

  function filtrarProfissionais(manterSelecionado) {
    var idServico = campoServico.val();
    var escolhidoAntes = manterSelecionado ? campoProfissional.val() : '';

    campoProfissional.empty();

    if (!idServico) {
      campoProfissional.append(montarPlaceholder(textoSemServico));
      return;
    }

    var disponiveis = opcoesOriginais.filter(function () {
      var servicos = String($(this).data('servicos') || '').split(',');
      return servicos.indexOf(String(idServico)) !== -1;
    });

    if (!disponiveis.length) {
      campoProfissional.append(montarPlaceholder(textoSemProfissional));
      return;
    }

    campoProfissional.append(montarPlaceholder('Selecione um profissional'));
    disponiveis.clone().appendTo(campoProfissional);

    if (escolhidoAntes && campoProfissional.find('option[value="' + escolhidoAntes + '"]').length) {
      campoProfissional.val(escolhidoAntes);
    }

    var validacao = campoProfissional.parsley && campoProfissional.parsley();
    if (validacao) validacao.reset();
  }

  campoServico.on('change', function () {
    filtrarProfissionais(false);
  });

  filtrarProfissionais(true);
});
