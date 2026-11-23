<?php use function App\Core\{csrf, e, loggedIn, takeFlash, url}; $flash = takeFlash(); ?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Organiza') ?> · Organiza</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <header class="topbar"><div class="container top-inner"><a class="brand" href="<?= url('dashboard') ?>"><span class="brand-icon">✦</span> organiza<span class="brand-period">.</span></a>
    <?php if (loggedIn()): ?><div class="nav"><span class="welcome">Olá, <?= e((string)($_SESSION['user_name'] ?? '')) ?></span><form action="<?= url('logout') ?>" method="post"><input type="hidden" name="csrf" value="<?= e(csrf()) ?>"><button class="link-button" type="submit">Sair →</button></form></div>
    <?php else: ?><div class="nav"><a href="<?= url('login') ?>">Entrar</a><a class="nav-signup" href="<?= url('register') ?>">Criar conta</a></div><?php endif; ?>
  </div></header>
  <main class="container main">
    <?php if ($flash): ?><div role="alert" class="alert <?= $flash['type'] === 'error' ? 'alert-error' : 'alert-success' ?>"><?= e($flash['message']) ?></div><?php endif; ?>
    <?= $content ?>
  </main>
  <footer class="footer container">Organiza © <?= date('Y') ?> <span>Feito para deixar a rotina mais leve.</span></footer>
</body>
</html>
