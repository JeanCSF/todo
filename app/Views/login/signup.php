<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>
<style>
    .content {
        overflow: hidden;
    }
</style>

<div class="container-fluid user-container">
    <div class="row">
        <div class="p-4">
            <?php
            helper('form');
            echo form_open('logincontroller/signup', 'enctype="multipart/form-data"');
            ?>
            <div class="row mb-3">
                <input type="text" name="user" class="form-control" placeholder="Usuário" value="<?= isset($userData) ? $userData['user'] : '' ?>" required autofocus>
            </div>
            <div class="row mb-3">
                <input type="password" class="form-control" name="pass" placeholder="Senha" required>
            </div>
            <div class="row mb-3">
                <input type="password" class="form-control" name="pass2" placeholder="Confirme a senha digitada" required>
            </div>
            <div class="row mb-3">
                <input type="text" class="form-control" name="name" placeholder="Nome" value="<?= isset($userData) ? $userData['name'] : '' ?>">
            </div>
            <div class="row mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" value="<?= isset($userData) ? $userData['email'] : '' ?>" required>
            </div>
            <div class="row mb-3">
                <div class="col-4">
                    <label for="userpic">Foto de Perfil:</label>
                </div>
                <?php if (session()->has('errors')) : ?>
                    <p class="text-danger"><?= session()->get('errors')['userpic'] ?></p>
                <?php endif; ?>
                <?php if (session()->has('uploaded')) : ?>
                    <p class="text-success"><?= session()->get('uploaded') ?></p>
                <?php endif; ?>
                <input type="file" name="userpic" id="userpic" class="form-control">
            </div>
            <div class="d-flex justify-content-between mt-2">
                <a href="<?= site_url('/') ?>" class="btn btn-secondary">Cancelar</a>
                <input class="btn btn-primary" type="submit" value="Criar conta">
            </div>
            <?= form_close() ?>
        </div>
    </div>

    <?= $this->endSection() ?>