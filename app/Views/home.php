<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>
<div id="post_div" class="header-post mb-2" style="<?= isset($search) ? 'visibility:hidden;' : '' ?>" tabindex="0">
    <img class="mt-2 rounded-circle border border-light-subtle float-start" height="48" width="48" src="<?= !empty($_SESSION['IMG']) ? base_url('../../assets/img/profiles_pics/' . $_SESSION['USER'] . '/' . $_SESSION['IMG']) : base_url('/assets/logo.png') ?>" alt="Profile pic">
    <form action="<?= base_url('todocontroller/newjobsubmit') ?>" method="post" class="ms-5">
        <select name="privacy_select" id="privacy_select" class="ms-3 mt-3" title="Visualização" hidden>
            <option value="1">Todos &#xf57d;</option>
            <option value="0">Somente eu &#xf023;</option>
        </select>
        <br>
        <div class="row mt-1 ms-3">
            <input type="text" id="header_job_name" name="header_job_name" placeholder="Tarefa" required autocomplete="off"><br>
            <textarea class="ms-3" type="text" id="header_job_desc" name="header_job_desc" placeholder="Sobre a tarefa" required autocomplete="off"></textarea>
        </div>
        <div class="text-end mt-1">
            <button type="submit" class="btn btn-sm btn-primary">Publicar</button>
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
                    <a style="text-decoration: none;" href="<?= site_url('userscontroller/profile/' . base64_encode($job->USER_ID)) ?>" class="link-secondary fs-4">
                        <img class="rounded-circle border border-light-subtle" height="48" width="48" src="<?= !empty($job->PROFILE_PIC) ? base_url('../../assets/img/profiles_pics/' . $job->USER . '/' . $job->PROFILE_PIC) : base_url('/assets/logo.png') ?>" alt="Profile pic"> <?= $job->USER ?>
                    </a>
                    <br>
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
            <p class="ms-5"><?= $job->JOB ?></p>
            <div class="d-flex justify-content-between">
                <p style="font-size: 12px;" class="fst-italic text-muted ms-2"><?= date("d/m/Y", strtotime($job->DATETIME_CREATED)) ?></p>
                <?php if (!empty($job->DATETIME_FINISHED)) : ?>
                    <p style="font-size: 12px;" class="fst-italic text-muted ms-2">Finalizada - <?= date("d/m/Y", strtotime($job->DATETIME_FINISHED)) ?> <i class='fa fa-check-double'></i></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
<script>
    
</script>