(function () {
  'use strict';

  if (typeof Swal === 'undefined') {
    return;
  }

  var CORES = {
    confirmar: ' #5c21c9',
    excluir: '#dc3545',
    cancelar: '#6c757d',
  };

  window.mensagem = function (texto, tipo, url) {
    Swal.fire({
      title: texto,
      icon: tipo || 'info',
      confirmButtonText: 'OK',
      confirmButtonColor: CORES.confirmar,
    }).then(function () {
      if (url) {
        location.href = url;
      }
    });
  };

  window.mostrarMensagens = function (lista) {
    if (!Array.isArray(lista) || lista.length === 0) {
      return;
    }

    var proxima = function (indice) {
      if (indice >= lista.length) {
        return;
      }

      Swal.fire({
        title: lista[indice].texto,
        icon: lista[indice].icone,
        confirmButtonText: 'OK',
        confirmButtonColor: CORES.confirmar,
      }).then(function () {
        proxima(indice + 1);
      });
    };

    proxima(0);
  };

  document.addEventListener('submit', function (evento) {
    var formulario = evento.target.closest('form[data-confirmar-exclusao]');

    if (!formulario || formulario.dataset.confirmado === '1') {
      return;
    }

    evento.preventDefault();

    Swal.fire({
      title: formulario.dataset.confirmarExclusao,
      text: formulario.dataset.confirmarDetalhe || 'Essa ação não pode ser desfeita.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Excluir',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: CORES.excluir,
      cancelButtonColor: CORES.cancelar,
      reverseButtons: true,
    }).then(function (resultado) {
      if (resultado.isConfirmed) {
        formulario.dataset.confirmado = '1';
        formulario.submit();
      }
    });
  });
})();
