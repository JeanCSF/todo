<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('conteudo') ?>
<?php

use App\Models\Todo;
$db = new Todo();
$id = $_SESSION['USER_ID'];
$alltasks = $db->select()->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.USER_ID', $id)->countAllResults();
$alldone = $db->select()->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.USER_ID', $id)->where('DATETIME_FINISHED !=', NULL)->countAllResults();
$notdone = $db->select()->join('login', 'login.USER_ID = jobs.USER_ID')->where('jobs.USER_ID', $id)->where('DATETIME_FINISHED =', NULL)->countAllResults();


$baseurl = base_url('/');
$doneurl = site_url('main/done');

?>

<header class="ms-5 me-5 d-flex justify-content-between">
        <h3>
            <?php if (isset($done)) : ?>
                Concluídas
            <?php else : ?>
                Todas as tarefas
            <?php endif; ?>
        </h3>
        <form class="d-flex me-5" role="search">
            <input class="form-control me-1" type="search" name="search" placeholder="Pesquisar" aria-label="Search">
            <input type="submit" class="btn btn-lg btn-outline-primary mt-1 me-5 fa fa-search">
        </form>
        <button type="button" class="ms-5 btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskModal" role="new task" onclick="fillModalNewJob()">Criar Tarefa</button>
</header>
<hr>
<div class="container mt-1">
    <div class="row justify-content-md-center">
        <div class="col-lg-8 col-md-auto col-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item <?= isset($done) || isset($search) ? '' : 'active' ?>" <?= isset($done) ? '' : 'aria-current="page"' ?>>
                        <?= isset($done) || isset($search) ? "<a  href='$baseurl'>Home</a>" : "Home" ?></li>

                    <?= isset($search) && empty($done) ?
                        "<li class='breadcrumb-item active' aria-current='page'>Pesquisa</li>" : '' ?>

                    <?= isset($done) && empty($search) ? '<li class="breadcrumb-item active" aria-current="page">Concluídas</li>' : '' ?>
                    <?= isset($search) && isset($done) ?
                        "<li class='breadcrumb-item'><a href='$doneurl'>Concluídas</a></li>
                        <li class='breadcrumb-item active' aria-current='page'>Pesquisa</li>" : '' ?>
                </ol>
            </nav>
            <?php if (count($jobs) == 0) : ?>
                <h3 class="alert alert-warning text-center"><?= isset($done) || isset($search) ? 'Não existem tarefas para esta pesquisa' : 'Não existem tarefas' ?></h3>
            <?php else : ?>
                <table class="table table-hover">
                    <thead class="table table-dark">
                        <tr>
                            <th>Tarefa</th>
                            <th class="text-center">Data de Criação</th>
                            <th class="text-center">Data de Finalização</th>
                            <th class="text-end">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job) : ?>
                            <tr>
                                <td><?= $job->JOB ?></td>
                                <td class="text-center"><?= date("d/m/Y", strtotime($job->DATETIME_CREATED)) ?></td>
                                <td class="text-center"><?= isset($job->DATETIME_FINISHED) ? date("d/m/Y", strtotime($job->DATETIME_FINISHED)) : 'Não finalizada' ?></td>
                                <td class="text-end">
                                    <?php if (empty($job->DATETIME_FINISHED)) : ?>
                                        <a href="<?= site_url('todocontroller/jobdone/' . $job->ID_JOB) ?>" class="btn btn-light btn-sm mx-1 text-success" role="finish" title="Finalizar Tarefa"><i class="fa fa-crosshairs"></i></a>
                                        <a class="btn btn-light btn-sm mx-1 text-primary" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit('<?= $job->ID_JOB ?>', '<?= $job->JOB ?>')"><i class="fa fa-pencil"></i></a>
                                    <?php else : ?>
                                        <button class="btn btn-light btn-sm mx-1 text-success" disabled><i class="fa fa-check"></i></button>
                                        <button class="btn btn-light btn-sm mx-1 text-primary" disabled><i class="fa fa-pencil"></i></button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-light btn-sm mx-1 text-danger" data-bs-toggle="modal" title="Excluír Tarefa" role="delete" data-bs-target="#deleteModal" onclick="fillModalDelete(<?= $job->ID_JOB ?>)"><i class="fa fa-trash"></i></button>
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
                        <p>Mostrando <strong><?= count($jobs) ?></strong> de <strong><?= $alljobs ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-sm-auto col-lg-auto col-md-auto">
            <div class="card text-bg-light mb-3" style="max-width: 18rem;">
                <div class="card-header">Tarefas</div>
                <div class="card-body">
                    <h6 class="card-title">Totais: <strong><?= $alltasks ?></strong></h6>
                    <h6 class="card-title">Concluídas: <strong><a style="text-decoration: none;" class="text-success" href="<?= site_url('main/done') ?>"><?= $alldone ?></a></strong></h6>
                    <h6 class="card-title">Não Concluídas: <strong class="text-warning"><?= $notdone ?></strong></h6>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endSection() ?>
