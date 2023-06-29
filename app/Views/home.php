<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>


<div class="d-flex justify-content-end">

</div>
<button type="button" class="btn text-primary bg-transparent border-0" data-bs-toggle="modal" data-bs-target="#taskModal" title="Adicionar Tarefa" role="new task" onclick="fillModalNewJob()"><i class="fa fa-pen-to-square fs-2"></i><i class="fa fa-circle-plus"></i></button>
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

<div class="row d-flex">
    <div class="col-3 d-flex flex-column justify-content-between">
        <div class="text-center">
            <a style="text-decoration: none;" href="<?= base_url('userscontroller/profile/' . base64_encode($_SESSION['USER_ID'])) ?>" class="link-secondary fs-4">
                <img class="rounded-circle border border-light-subtle" height="128" width="128" src="<?= !empty($_SESSION['IMG']) ? base_url('../../assets/img/profiles_pics/' . $_SESSION['USER'] . '/' . $_SESSION['IMG']) : base_url('/assets/logo.png') ?>" alt=""> <br><?= $_SESSION['USER'] ?>
            </a>
        </div>
        <div id="userActions">
            <?php if (isset($_SESSION['USER_ID'])) : ?>
                <?php if ($_SESSION['SU'] == 1) : ?>
                    <a href="<?= base_url('userscontroller/users/') ?>"><i class="fa fa-users"></i> Usuários</a>
                <?php endif; ?>
                <a href="<?= base_url('logincontroller/logout') ?>"><strong><i class="fa fa-right-from-bracket"></i></strong> Logout</a>
            <?php endif; ?>
        </div>
    </div>
    <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
    <div class="col-6 border border-dark">
        <?php if (count($jobs) == 0) : ?>
            <div class="row mt-2">
                <p class="fs-3 alert alert-warning text-center"><?= isset($search) ? 'Não existem tarefas para esta pesquisa' : 'Não existem tarefas' ?></p>
            </div>
        <?php else : ?>
            <?php foreach ($jobs as $job) : ?>
                <div class="row d-flex justify-content-between post post-hr">
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
                        <p class="p-2"><?= $job->JOB ?><p>
                    <?php if (!empty($job->DATETIME_FINISHED)) : ?>
                        <div class="text-end">
                            <p>Finalizada - <?= date("d/m/Y", strtotime($job->DATETIME_FINISHED)) ?> <i class='fa fa-check-double'></i></p>
                        </div>
                    <?php endif; ?>
                    <hr>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
    <div class="col-3">
        <form class="d-flex" role="search">
            <input class="form-control me-1" type="search" name="search" aria-label="Search" />
            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-search"></i></button>
        </form>
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