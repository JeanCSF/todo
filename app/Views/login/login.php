<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>

<div class="container mt-5">
    <div class="row mt-5">
        <div class=" mt-5 col-6 offset-3">
            <?php
            helper('form');
            echo form_open('logincontroller/login');
            ?>
            <?php if (isset($error)) : ?>
                <p class="alert alert-danger text-center"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="row form-floating mb-3">
                <input type="text" class="form-control" name="user" required>
                <label for="user" class="form-label">Usuário</label>
            </div>
            <div class="row form-floating mb-3">
                <input type="password" class="form-control" name="pass" required>
                <label for="pass">Senha</label>
            </div>
            <div class="text-center">
                <a href="<?= site_url('/') ?>" class="btn btn-secondary">Cancelar</a>
                <input class="btn btn-primary" type="submit" value="Entrar">
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>