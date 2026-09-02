<?php

$injetorAgente = 'https://cdn.botpress.cloud/webchat/v5.0/inject.js';
$configAgente  = 'https://files.bpcontent.cloud/2026/09/02/00/20260902004907-4HFXUEME.js';

if ($configAgente === '') {
    return;
}
?>
<script src="<?php echo htmlspecialchars($injetorAgente, ENT_QUOTES); ?>"></script>
<script src="/assets/js/agente.js"></script>
<script src="<?php echo htmlspecialchars($configAgente, ENT_QUOTES); ?>" defer></script>
