<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('conteudo') ?>

<div class="container col-8 offset-2">
    <div class="row">
        <h1 class="text-center mt-3">Cadastrar Usuário</h1>
        <hr>
        <div class="col-8 offset-2">
            <form action="<?= base_url('userscontroller/newuser') ?>" method="post">
                <div class="row my-3">
                    <div class="row">
                        <div class="col-3 text-end">
                            <label for="name">Nome:</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form form-control" id="name" name="name" value="<?= isset($userData)? $userData['name'] : ''?>" required autofocus>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3 text-end">
                            <label for="user">Usuário:</label>
                        </div>
                        <div class="col-4">
                            <input type="text" class="form form-control" id="user" name="user" value="<?= isset($userData)? $userData['user'] : ''?>" required>
                        </div>
                        <div class="col-1 text-end">
                            <label for="pass">Senha:</label>
                        </div>
                        <div class="col-3">
                            <input type="password" class="form form-control" id="pass" name="pass" value="<?= isset($userData)? $userData['pass'] : ''?>" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3 text-end">
                            <label for="email">Email:</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form form-control" id="email" name="email" value="<?= isset($userData)? $userData['email'] : ''?>" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class=" text-center d-flex justify-content-between">
                <a href="javascript:history.go(-1)" class="btn btn-secondary">Cancelar</a>
                <input class="btn btn-primary" type="submit" value="Cadastrar">
            </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>