<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('conteudo') ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-sm-6 offset-3 col-8 offset-2">
            <div class="card p-4">
                <h3>Nova conta de usuário</h3>
                <hr>
                <?php
                helper('form');
                echo form_open('logincontroller/signup');
                ?>
                <div class="row mb-3">
                    <input type="text" name="user" class="form-control" placeholder="Usuário" value="<?= isset($userData)? $userData['user'] : ''?>" required autofocus>
                </div>
                <div class="row mb-3">
                    <input type="password" class="form-control" name="pass" placeholder="Digite sua senha" required>
                </div>
                <div class="row mb-3">
                    <input type="password" class="form-control" name="pass2" placeholder="Confirme a senha digitada" required>
                </div>
                <div class="row mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?= isset($userData)? $userData['email'] : ''?>" required>
                </div>
                <div class="row mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Nome" value="<?= isset($userData)? $userData['name'] : ''?>" required>
                    <div class="text-center mt-2">
                        <a href="<?= site_url('/') ?>" class="btn btn-secondary">Cancelar</a>
                        <input class="btn btn-primary" type="submit" value="Criar conta">
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endSection() ?>