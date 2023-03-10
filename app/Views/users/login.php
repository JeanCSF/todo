<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('conteudo') ?>

<div class="container mt-5">
    <div class="row mt-5">
        <div class="mt-5 col-2 offset-5">
            <?php
            helper('form');
            echo form_open('userscontroller/login');
            ?>
            <?php if (isset($error)) : ?>
                <p class="alert alert-danger text-center"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="row mb-3">
                <input type="text" class="form-control" name="txtUser" placeholder="Usuário" required>
            </div>
            <div class="row mb-3">
                <input type="password" class="form-control" name="txtPass" placeholder="Senha" required>
            </div>
            <hr>
            <div class="text-center">
                <a href="<?= site_url('/') ?>" class="btn btn-secondary">Cancelar</a>
                <input class="btn btn-primary" type="submit" value="Entrar">
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>