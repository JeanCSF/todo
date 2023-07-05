<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>

<div class="container-fluid">
    <div class="row">
        <div class="p-4">
            <?php
            helper('form');
            echo form_open('logincontroller/signup', 'enctype="multipart/form-data"');
            ?>
            <div class="row form-floating mb-3">
                <input type="text" name="user" class="form-control" placeholder="Usuário" value="<?= isset($userData) ? $userData['user'] : '' ?>" required autofocus>
                <label for="user">Usuário</label>
            </div>
            <div class="row form-floating mb-3">
                <input type="password" class="form-control" name="pass" placeholder="Senha" required>
                <label for="pass">Senha</label>
            </div>
            <div class="row form-floating mb-3">
                <input type="password" class="form-control" name="pass2" placeholder="Confirme a senha digitada" required>
                <label for="pass2">Confirme a senha</label>
            </div>
            <div class="row form-floating mb-3">
                <input type="text" class="form-control" name="name" placeholder="Nome" value="<?= isset($userData) ? $userData['name'] : '' ?>">
                <label for="name">Nome</label>
            </div>
            <div class="row form-floating mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" value="<?= isset($userData) ? $userData['email'] : '' ?>" required>
                <label for="email">Email</label>
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
            <div class="text-center mt-2">
                <a href="<?= site_url('/') ?>" class="btn btn-secondary">Cancelar</a>
                <input class="btn btn-primary" type="submit" value="Criar conta">
            </div>
            <?= form_close() ?>
        </div>
    </div>

    <?= $this->endSection() ?>