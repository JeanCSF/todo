<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>
<style>
    .content {
        overflow: hidden;
    }
</style>

<div class="container-fluid p-5 d-flex justify-content-center align-items-center">
    <?php
    helper('form');
    echo form_open('logincontroller/login');
    ?>
    <?php if (isset($error)) : ?>
        <p class="alert alert-danger text-center"><?php echo $error; ?></p>
    <?php endif; ?>
    <div class="row mb-3">
        <input type="text" class="form-control" placeholder="Usuário" name="user" maxlength="30" required>
    </div>
    <div class="row mb-3">
        <input type="password" class="form-control" placeholder="Senha" name="pass" minlength="8" maxlength="16" required>
    </div>
    <div class="d-flex justify-content-between">
        <a href="<?= site_url('/') ?>" class="btn btn-secondary">Cancelar</a>
        <input class="btn btn-primary" type="submit" value="Entrar">
    </div>
    <?= form_close() ?>
</div>
<?= $this->endSection() ?>