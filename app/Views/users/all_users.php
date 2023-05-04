<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('conteudo') ?>
<div class="container mt-1">
    <div class="row">
        <h1 class="text-center">Usuários cadastrados</h1>
        <div class="col-8 offset-2 mt-5">
                <div class="text-end mb-2">
                    <a class="btn btn-primary" href="<?= base_url('userscontroller/newuser')?>" title="Adicionar Usuário" role="Add User"><i class="fa fa-plus"></i></a>
                </div>
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Usuário</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data Criação</th>
                        <th class="text-end">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->USER ?></td>
                            <td><a class="nav-link" href="<?= base_url('userscontroller/profile/' . base64_encode($user->USER_ID)) ?>"><?= $user->NAME ?></a></td>
                            <td><?= $user->EMAIL ?></td>
                            <td><?= date("d/m/Y", strtotime($user->DATETIME_CREATED)) ?></td>
                            <td class="text-end">
                            <a class="btn btn-light btn-sm mx-1 text-primary" href="<?=base_url('userscontroller/edit/' . base64_encode($user->USER_ID))?>" title="Editar Usuário" role="edit"><i class="fa fa-pencil"></i></a>
                            <button type="button" class="btn btn-light btn-sm mx-1 text-danger" data-bs-toggle="modal" title="Excluír Usuário" role="delete" data-bs-target="#deleteModal" onclick="fillModalDeleteUser(<?= $user->USER_ID ?>)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>