<?php
ob_start();
?>

<!-- Hero / Banner -->
<?php include __DIR__ . '/partials/banner.php'; ?>

<!-- Sobre -->
<?php include __DIR__ . '/partials/sobre.php'; ?>

<!-- Eventos -->
<?php include __DIR__ . '/partials/eventos.php'; ?>

<!-- Sermões -->
<?php include __DIR__ . '/partials/sermoes.php'; ?>

<!-- Citação -->
<?php include __DIR__ . '/partials/citacao.php'; ?>

<!-- Publicações -->
<?php include __DIR__ . '/partials/publicacoes.php'; ?>

<!-- Contacto -->
<?php include __DIR__ . '/partials/contacto.php'; ?>

<!-- Mapa -->
<?php include __DIR__ . '/partials/mapa.php'; ?>

<?php
$content = ob_get_clean();
include __DIR__ . "/../../Shared/Views/layout_public.php";
?>