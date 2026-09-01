(function ($) {
  'use strict';

  if (typeof $ === 'undefined') {
    return;
  }

  function campoJaMascarado(elemento) {
    return Boolean(
      elemento.inputmask ||
      $(elemento).data('mask') ||
      elemento.dataset.maskInit === '1'
    );
  }

  function marcarCampo(elemento) {
    elemento.dataset.maskInit = '1';
  }

  function aplicarInputmask(elemento, mascara) {
    if (campoJaMascarado(elemento)) {
      return;
    }

    if ($.fn.inputmask) {
      $(elemento).inputmask({
        mask: mascara,
        showMaskOnHover: false,
        showMaskOnFocus: true,
      });
      marcarCampo(elemento);
      return;
    }

    if (window.Inputmask) {
      Inputmask({ mask: mascara, showMaskOnHover: false, showMaskOnFocus: true }).mask(elemento);
      marcarCampo(elemento);
    }
  }

  function aplicarJqueryMask(elemento, mascara, opcoes) {
    if (campoJaMascarado(elemento) || !$.fn.mask) {
      return;
    }

    $(elemento).mask(mascara, opcoes || {});
    marcarCampo(elemento);
  }

  window.aplicarMascarasImdb = function (raiz) {
    var contexto = raiz || document;

    $(contexto).find('#cpf').each(function () {
      aplicarInputmask(this, '999.999.999-99');
    });

    $(contexto).find('#datanascimento').each(function () {
      aplicarInputmask(this, '99/99/9999');
    });

    $(contexto).find('#telefone').each(function () {
      aplicarJqueryMask(this, '(00) 00000-0000');
    });

    $(contexto).find('#preco').each(function () {
      aplicarJqueryMask(this, '000.000.000.000.000,00', { reverse: true });
    });
  };

  window.inicializarValidacao = function (raiz) {
    if (typeof $.fn.parsley === 'undefined') {
      return;
    }

    var $container = raiz ? $(raiz) : $(document);

    $container.find('form[data-parsley-validate]').each(function () {
      this.setAttribute('novalidate', 'novalidate');
      $(this).parsley();
    });
  };

  window.inicializarSenhaEdicao = function (raiz) {
    if (typeof $.fn.parsley === 'undefined') {
      return;
    }

    var $container = raiz ? $(raiz) : $(document);
    var $form = $container.find('form[data-form-usuario-edicao="1"]');

    if (!$form.length) {
      return;
    }

    $form.find('#senha, #senha2').each(function () {
      $(this).removeAttr('required').parsley().reset();
    });
  };

  $(function () {
    if (typeof $.fn.parsley !== 'undefined') {
      window.Parsley.setLocale('pt-br');
    }

    aplicarMascarasImdb();
    inicializarValidacao();
    inicializarSenhaEdicao();

    $(document).on('shown.bs.modal', function (evento) {
      aplicarMascarasImdb(evento.target);
      inicializarValidacao(evento.target);
    });
  });
})(window.jQuery);
