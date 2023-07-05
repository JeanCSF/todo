<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>
<div class="header-post mb-2">
    <form action="<?= base_url('todocontroller/newjobsubmit') ?>" method="post">
        <input type="text" id="job_name" name="job_name" placeholder="Tarefa" required autocomplete="off"><br>
        <input type="text" id="job_desc" name="job_desc" placeholder="Sobre a tarefa" required autocomplete="off">
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Publicar</button>
        </div>
    </form>
</div>
<?php if (count($jobs) == 0) : ?>
    <div class="row mt-2">
        <p class="fs-3 alert alert-warning text-center"><?= isset($search) ? 'Não existem tarefas para esta pesquisa' : 'Não existem tarefas' ?></p>
    </div>
<?php else : ?>
    <?php foreach ($jobs as $job) : ?>
        <div class="row d-flex justify-content-between post">
            <div class="d-flex justify-content-between">
                <p>
                    <a style="text-decoration: none;" href="<?= base_url('userscontroller/profile/' . base64_encode($job->USER_ID)) ?>" class="link-secondary fs-4">
                        <img class="rounded-circle border border-light-subtle" height="64" width="64" src="<?= !empty($job->PROFILE_PIC) ? base_url('../../assets/img/profiles_pics/' . $job->USER . '/' . $job->PROFILE_PIC) : base_url('/assets/logo.png') ?>" alt=""> <br><?= $job->USER ?>
                    </a>
                    <br>
                    <span style="font-size: 12px;" class="fst-italic text-muted"><?= date("d/m/Y", strtotime($job->DATETIME_CREATED)) ?></span>
                </p>
                <p <?= !empty($job->DATETIME_FINISHED) ? "style='text-decoration: line-through;'" : "" ?> class="fs-3"><?= $job->JOB_TITLE ?></p>
                <?php if ($_SESSION['USER_ID'] == $job->USER_ID) : ?>
                    <div class="dropdown">
                        <button class="nav-link bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis"></i>
                        </button>
                        <ul class="dropdown-menu post-it-dropdown">
                            <li><a data-bs-toggle="modal" data-bs-target="#privacyModal" class="dropdown-item" onclick="fillModalPrivacy(`<?= $job->ID_JOB ?>`)">Privacidade <?= $job->PRIVACY == 1 ? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>' ?></a></li>
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
            <p class="p-2"><?= $job->JOB ?>
            <p>
                <?php if (!empty($job->DATETIME_FINISHED)) : ?>
            <div class="text-end">
                <p>Finalizada - <?= date("d/m/Y", strtotime($job->DATETIME_FINISHED)) ?> <i class='fa fa-check-double'></i></p>
            </div>
        <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>