<?php foreach ($mensagens as $mensagem): ?>
<div class="alert alert-<?php echo $mensagem['tipo']; ?> text-center" role="alert">
  <?php echo htmlspecialchars($mensagem['texto']); ?>
</div>
<?php endforeach; ?>
