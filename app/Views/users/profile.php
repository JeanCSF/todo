<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('conteudo') ?>
<style>
</style>
<div class="container mt-1">
    <div class="row">
        <div class="col-lg-8 col-md-auto col-sm-auto offset-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item <?= isset($userData) ? '' : 'active' ?>" <?= isset($userData) ? '' : 'aria-current="page"' ?>>
                        <?= isset($userData) ? "<a  href='" . base_url('/') . "' >Home</a>" : "Home" ?></li>

                    <?php if (isset($userData)) : ?>
                        <li class='breadcrumb-item active' aria-current='page'>Perfil - <?= $_SESSION['NAME'] ?> </li>
                    <?php endif; ?>
                </ol>
            </nav>
            <div class="card mb-3" style="max-width: 740px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="https://placehold.co/1000x1000" class="img-fluid rounded-start" alt="Profile pic">
                        <p class="card-text"><small class="text-muted text-nowrap">Data do cadastro: <?= date('d/m/Y', strtotime($userData->DATETIME_CREATED)) ?></small></p>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $userData->NAME ?></h5>
                            <ul class="list-group">
                                <li class="col list-group-item">
                                    <ul class="list-group list-group-horizontal row">
                                        <li style="list-style-type: none;"><strong>Usuário: </strong><?= $userData->USER ?></li>
                                        <li class="mt-1" style="list-style-type: none;"><strong>Email: </strong><?= $userData->EMAIL ?></li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="mt-5 card-footer bg-transparent">
                                <?php if ($_SESSION['SU'] == 1) : ?>
                                    <p class="card-text"><small class="text-muted">E-mail confirmado? <?= ($userData->ACTIVATION == 0) ? 'Não' : 'Sim' ?></small></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <?php if (count($userTasks) == 0) : ?>
                <h3 class="alert alert-warning text-center">Não existem tarefas!</h3>
            <?php else : ?>
                <table class="table table-hover">
                    <thead class="table table-dark">
                        <tr>
                            <th>Tarefa</th>
                            <th class="text-center">Data de Criação</th>
                            <th class="text-center">Data de Finalização</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userTasks as $job) : ?>
                            <tr>
                                <td><?= $job->JOB ?></td>
                                <td class="text-center"><?= date('d/m/Y',strtotime($job->DATETIME_CREATED)) ?></td>
                                <td class="text-center"><?= isset($job->DATETIME_FINISHED) ? date('d/m/Y',strtotime($job->DATETIME_FINISHED)) : 'Não finalizada' ?></td>
                                <td class="text-end">
                                    <?php if (empty($job->DATETIME_FINISHED)) : ?>
                                        <a href="<?= site_url('todocontroller/jobdone/' . $job->ID_JOB) ?>" class="btn btn-light btn-sm mx-1" role="finish" title="Finalizar Tarefa">&#9680;</a>
                                        <a class="btn btn-light btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit('<?= $job->ID_JOB ?>', '<?= $job->JOB ?>')">&#9997;</a>
                                    <?php else : ?>
                                        <button class="btn btn-light btn-sm mx-1" disabled>&#10004;</button>
                                        <button class="btn btn-light btn-sm mx-1" disabled>&#9997;</button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-light btn-sm mx-1" data-bs-toggle="modal" title="Excluír Tarefa" role="delete" data-bs-target="#deleteModal" onclick="fillModalDelete(<?= $job->ID_JOB ?>)">&#128465;</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row" id="pager">
                    <div class="col mt-1">
                        <?php
                        if ($pager) {
                            echo $pager->links();
                        }
                        ?>
                    </div>
                    <div class="col text-end">
                        <p>Mostrando <strong><?= count($userTasks) ?></strong> de <strong><?= $alltasks ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>