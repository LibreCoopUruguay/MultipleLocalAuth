<?php
use MapasCulturais\i;
use MapasCulturais\App;

$app = App::i();
$errors = $errors ?? [];
$triedEmail = $triedEmail ?? '';
$conflictEmail = $conflictEmail ?? '';
$formAction = $formAction ?? $app->createUrl('auth', 'govbr-email');
?>
<div class="auth-govbr-email" style="max-width: 480px; margin: 2rem auto; padding: 1.5rem;">
    <h2><?= i::__('E-mail já utilizado', 'multipleLocal') ?></h2>

    <p>
        <?= i::__('O e-mail informado pelo Gov.br já está vinculado a outra conta neste mapa cultural.', 'multipleLocal') ?>
        <?php if ($conflictEmail): ?>
            (<strong><?= htmlspecialchars($conflictEmail) ?></strong>)
        <?php endif; ?>
    </p>
    <p>
        <?= i::__('Para criar a sua conta, informe outro e-mail. Este e-mail será o da sua conta e deve ser único.', 'multipleLocal') ?>
    </p>

    <?php if ($errors): ?>
        <ul class="auth-govbr-email__errors" style="color: #b00020; padding-left: 1.2rem;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($formAction) ?>">
        <label for="govbr-alternate-email">
            <?= i::__('Novo e-mail', 'multipleLocal') ?>
        </label>
        <br>
        <input
            type="email"
            id="govbr-alternate-email"
            name="email"
            required
            value="<?= htmlspecialchars((string) $triedEmail) ?>"
            style="width: 100%; margin: 0.5rem 0 1rem; padding: 0.5rem;"
            autocomplete="email"
        >
        <button type="submit" class="button button--primary">
            <?= i::__('Criar conta com este e-mail', 'multipleLocal') ?>
        </button>
    </form>

    <p style="margin-top: 1.5rem;">
        <a href="<?= $app->createUrl('auth', '') ?>">
            <?= i::__('Voltar para o login', 'multipleLocal') ?>
        </a>
    </p>
</div>
