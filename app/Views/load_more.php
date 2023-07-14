<?php if (count($jobs) == 0) : ?>
    <div class="row mt-2">
        <p class="fs-3 alert alert-warning text-center"><?= isset($search) ? 'Não existem tarefas para esta pesquisa' : 'Não existem tarefas' ?></p>
    </div>
<?php else : ?>
    <?php foreach ($jobs as $job) : ?>
        <article class="row post">
            <div class="post-container">
                <div class="user-img">
                    <a href="<?= base_url('profile/' . base64_encode($job->USER_ID)) ?>">
                        <img height="48" width="48" src="<?= !empty($_SESSION['IMG']) ? base_url('../../assets/img/profiles_pics/' . $_SESSION['USER'] . '/' . $_SESSION['IMG']) : base_url('/assets/logo.png') ?>" alt="Profile pic">
                    </a>
                </div>
                <div class="user-info">
                    <a href="<?= base_url('profile/' . base64_encode($job->USER_ID)) ?>" class="user-name"><?= $job->NAME ?> &#8226; <span class="text-muted fst-italic">@<?= $job->USER ?></span></a>
                    <span>
                        <?php if ($_SESSION['USER_ID'] == $job->USER_ID) : ?>
                            <div class="dropdown">
                                <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                    </span>
                </div>
                <div class="user-post-text">
                    <span class="fst-italic text-center d-block fs-5" style="<?= isset($job->DATETIME_FINISHED) ? "text-decoration: line-through;" : "" ?>"><?= $job->JOB_TITLE ?></span>
                    <span><?= nl2br($job->JOB) ?></span>
                </div>
                <div class="user-post-footer fst-italic text-muted mt-3">
                    <p><?= date("d/m/Y", strtotime($job->DATETIME_CREATED)) ?></p>
                    <p><?= !empty($job->DATETIME_FINISHED) ? date("d/m/Y", strtotime($job->DATETIME_FINISHED)) . " <i class='fa fa-check-double'></i>" : "" ?></p>
                </div>
                <div class="post-actions">
                    <a href="#" role="button"><span class="fst-italic text-muted">{elapsed_time}</span><br><i class="fa-regular fa-heart"></i></a>
                    <a href="#" role="button"><span class="fst-italic text-muted">{elapsed_time}</span><br><i class="fa-regular fa-comment"></i></a>
                    <a href="#" role="button"><span class="fst-italic text-muted"> </span><br><i class="fa fa-arrow-up-from-bracket"></i></a>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>