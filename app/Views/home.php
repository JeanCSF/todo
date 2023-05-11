<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>


<div class="ms-5 me-5 d-flex justify-content-between">
    <h3>
        <?php if (isset($done)) : ?>
            Concluídas
        <?php else : ?>
            Todas as tarefas
        <?php endif; ?>
    </h3>
    <form class="d-flex me-5 col-6" role="search">
        <input class="form-control me-1" type="search" name="search" aria-label="Search" />
        <input type="submit" class="btn btn-outline-primary me-5" value="Pesquisar" />
    </form>
    <button type="button" class="btn text-primary bg-transparent border-0" data-bs-toggle="modal" data-bs-target="#taskModal" title="Adicionar Tarefa" role="new task" onclick="fillModalNewJob()"><i class="fa fa-file-circle-plus fs-2"></i></button>
</div>
<hr>
<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item <?= isset($search) ? '' : 'active' ?>" <?= isset($search) ? '' : 'aria-current="page"' ?>>
                <?= isset($search) ? "<a class='text-decoration-none link-secondary'  href='" . base_url('/') . "'>Home</a>" : "Home" ?></li>

            <?= isset($search) ?
                "<li class='breadcrumb-item active' aria-current='page'>Pesquisa</li>" : '' ?>
        </ol>
    </nav>
</div>
<div class="container mt-1 bg-light bg-gradient">
    <div class="row">
        <div class="col-lg-auto col-md-auto col-sm-auto">
            <?php if (count($jobs) == 0) : ?>
                <h3 class="alert alert-warning text-center"><?= isset($search) ? 'Não existem tarefas para esta pesquisa' : 'Não existem tarefas' ?></h3>
            <?php else : ?>
                <div class="row">
                    <?php foreach ($jobs as $job) : ?>
                        <div class="row d-flex justify-content-between post-it ms-2 my-2">
                            <div class="d-flex justify-content-between">
                                <p>
                                    <a style="text-decoration: none;" href="<?= base_url('userscontroller/profile/' . base64_encode($job->USER_ID)) ?>" class="link-secondary fs-4">
                                        <img class="rounded-circle" height="64" width="64" src="<?= !empty($job->PROFILE_PIC) ? base_url('../../assets/img/profiles_pics/' . $job->PROFILE_PIC) : base_url('/assets/logo.png') ?>" alt=""> <?= $job->USER ?>
                                    </a>
                                    <br>
                                    <span style="font-size: 12px;" class="fst-italic text-muted"><?= date("d/m/Y", strtotime($job->DATETIME_CREATED)) ?></span>
                                </p>
                                <p <?= !empty($job->DATETIME_FINISHED) ? "style='text-decoration: line-through;'" : "" ?>><?= $job->JOB_TITLE ?></p>
                                <?php if ($_SESSION['USER_ID'] == $job->USER_ID) : ?>
                                    <div class="dropdown">
                                        <button class="nav-link bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu post-it-dropdown">
                                            <?php if (empty($job->DATETIME_FINISHED)) : ?>
                                                <li><a class="dropdown-item" href="<?= site_url('todocontroller/jobdone/' . $job->ID_JOB) ?>" role="finish" title="Finalizar Tarefa">Finalizar <i class="fa fa-crosshairs text-success"></i></a></li>
                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit(`<?= $job->ID_JOB ?>`,  `<?= $job->JOB_TITLE ?>`, `<?= $job->JOB ?>`)">Editar <i class="fa fa-pencil text-primary"></i></a></li>
                                            <?php endif; ?>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDelete(<?= $job->ID_JOB ?>)">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                        </ul>
                                    </div>
                                <?php else : ?>
                                    <p> </p>
                                <?php endif; ?>
                            </div>
                            <hr>
                            <div class="mx-2 p-1 text-justify" style="height:25%;">
                                <h6><?= $job->JOB ?></h6>
                            </div>
                            <hr>
                            <?php if (!empty($job->DATETIME_FINISHED)) : ?>
                                <div class="text-end">
                                    <p>Finalizada - <?= date("d/m/Y", strtotime($job->DATETIME_FINISHED)) ?> <i class='fa fa-check-double'></i></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                </div>
        </div>
    </div>
</div>
<div class="mt-3" id="pager">
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
<?= $this->endSection() ?>