<?php
$iconesDoAlerta = [
    'success' => 'success',
    'warning' => 'warning',
    'danger' => 'error',
];

$mensagensDoSweetAlert = [];

foreach ($mensagens as $mensagem) {
    $mensagensDoSweetAlert[] = [
        'texto' => $mensagem['texto'],
        'icone' => $iconesDoAlerta[$mensagem['tipo']] ?? 'info',
    ];
}
?>
<?php if (!empty($mensagensDoSweetAlert)): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    mostrarMensagens(<?php echo json_encode(
        $mensagensDoSweetAlert,
        JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    ); ?>);
  });
</script>
<?php endif; ?>
