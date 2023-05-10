<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>

<div class="container mt-5">
    <div class="row mt-5">
        <div class=" card p-4 mt-5 col-4 offset-4">
            <h1 class="text-center">Login</h1>
            <hr>
            <?php
            helper('form');
            echo form_open('logincontroller/login');
            ?>
            <?php if (isset($error)) : ?>
                <p class="alert alert-danger text-center"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="row mb-3">
                <input type="text" class="form-control" name="user" placeholder="Usuário" required>
            </div>
            <div class="row mb-3">
                <input type="password" class="form-control" name="pass" placeholder="Senha" required>
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