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
            echo form_open('logincontroller/signup', 'enctype="multipart/form-data" id="signUpForm"');
            ?>
            <div class="row mb-3">
                <input type="text" name="user" id="user" class="form-control limit" placeholder="Usuário" value="<?= isset($userData) ? $userData['user'] : '' ?>" maxlength="30" required autofocus>
                <span class="text-muted text-end" id="userCharCount"></span>
            </div>
            <div class="row mb-3">
                <input type="password" class="form-control limit" name="pass" id="pass" placeholder="Senha" minlength="8" maxlength="16" required>
                <span class="text-muted text-end" id="passCharCount"></span>
            </div>
            <div class="row mb-3">
                <input type="password" class="form-control limit" name="pass2" id="pass2" placeholder="Confirme a senha digitada" minlength="8" maxlength="16" required>
                <span class="text-muted text-end" id="pass2CharCount"></span>
            </div>
            <div class="row mb-3">
                <input type="text" class="form-control limit" name="name" id="name" placeholder="Nome" value="<?= isset($userData) ? $userData['name'] : '' ?>" maxlength="150" required>
                <span class="text-muted text-end" id="nameCharCount"></span>
            </div>
            <div class="row mb-3">
                <input type="email" class="form-control limit" name="email" id="email" placeholder="Email" value="<?= isset($userData) ? $userData['email'] : '' ?>" maxlength="100" required>
                <span class="text-muted text-end" id="emailCharCount"></span>
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
    <?= $this->section('section') ?>
    <script>
        const signUpForm = document.querySelector('#signUpForm');
        const signUpElements = signUpForm.querySelectorAll('.limit');
        signUpElements.forEach(input => {
            input.addEventListener('input', () => {
                const count = document.querySelector(`#${input.id}CharCount`);
                const length = input.value.length;
                const limit = parseInt(input.getAttribute('maxlength'));
                count.textContent = `${length}/${limit}`;

                if (length > limit) {
                    input.value = input.value.slice(0, limit);
                }
            });
        });
    </script>
    <?= $this->endSection() ?>