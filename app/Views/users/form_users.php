<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>

<div class="container col-8 offset-2">
    <div class="row">
        <h1 class="text-center mt-3"><?= isset($edit) ? 'Editar Usuário' : 'Cadastrar Usuário' ?></h1>
        <hr>
        <div class="col-8 offset-2">
            <form action="<?= isset($edit) ? base_url('userscontroller/edit/' . base64_encode($user->USER_ID)) : base_url('userscontroller/newuser') ?>" method="post">
                <div class="row my-3">
                    <div class="row">
                        <div class="col-3 text-end">
                            <label for="name">Nome:</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form form-control" id="name" name="name" value="<?= isset($userData) ? $userData['name'] : '' ?><?= isset($user) ? $user->NAME : '' ?>" required autofocus>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3 text-end">
                            <label for="user">Usuário:</label>
                        </div>
                        <div class="col-4">
                            <input type="text" class="form form-control" id="user" name="user" value="<?= isset($userData) ? $userData['user'] : '' ?><?= isset($user) ? $user->USER : '' ?>" required>
                        </div>
                        <div class="col-1 text-end" <?= isset($edit)? 'hidden' : ''?>>
                            <label for="pass">Senha:</label>
                        </div>
                        <div class="col-3" <?= isset($edit)? 'hidden' : ''?>>
                            <input type="password" class="form form-control" id="pass" name="pass" value="<?= isset($userData) ? $userData['pass'] : '' ?>" <?= isset($edit)? '' : 'required'?>>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3 text-end">
                            <label for="email">Email:</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form form-control" id="email" name="email" value="<?= isset($userData) ? $userData['email'] : '' ?><?= isset($user) ? $user->EMAIL : '' ?>" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class=" text-center d-flex justify-content-between">
                    <a href="javascript:history.go(-1)" class="btn btn-secondary">Cancelar</a>
                    <input class="btn btn-primary" type="submit" value="<?= isset($edit) ? 'Atualizar' : 'Cadastrar' ?>">
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>