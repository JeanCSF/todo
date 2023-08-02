<?php

use App\Models\Likes;

?>

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
        <div class="mt-1 ms-3">
            <input type="text" id="header_job_name" name="header_job_name" placeholder="Tarefa" required autocomplete="off"><br>
            <textarea oninput="auto_grow(this)" class="ms-3" type="text" id="header_job_desc" name="header_job_desc" placeholder="Sobre a tarefa" required autocomplete="off"></textarea>
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
    <?php foreach ($jobs as $job) :
        $likes = new Likes();
        $total_likes = $likes->select('LIKE_ID')->where('ID_JOB', $job->ID_JOB)->countAllResults();
        $check_like = $likes->where('ID_JOB', $job->ID_JOB)->where('USER_ID', $_SESSION['USER_ID'])->countAllResults();
    ?>
        <article class="row post">
            <div class="post-container">
                <div class="user-img">
                    <a href="<?= base_url('profile/' . base64_encode($job->USER_ID)) ?>">
                        <img height="48" width="48" src="<?= !empty($job->PROFILE_PIC) ? base_url('../../assets/img/profiles_pics/' . $job->USER . '/' . $job->PROFILE_PIC) : base_url('/assets/logo.png') ?>" alt="Profile pic">
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
                    <a href="#" role="button" on>
                        <i class="<?= (!empty($check_like)) ? 'fa fa-heart' : 'fa-regular fa-heart' ?>"></i>
                        <span id="likes<?= $job->ID_JOB ?>" class="ms-1 fst-italic text-muted"></span>
                    </a>
                    <a href="#" role="button"><i class="fa-regular fa-comment"></i><span class="ms-1 fst-italic text-muted">{elapsed_time}</span></a>
                    <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
<div id="main"></div>

<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    function getLikes(id_job) {
        var likesSpan = document.querySelector("#likes" + id_job);
        var url = '<?= base_url('job_likes') ?>';
        var Likes = [];
        var dataToSend = {
            term: id_job
        };
        $.ajax({
            url: url,
            type: "POST",
            data: dataToSend,
            error: function(xhr, status, error) {
                console.error("Erro na requisição:", error);
            }
        }).done(function(response) {
            likesSpan.innerHTML = response.likes;
        });
    }
    document.addEventListener("DOMContentLoaded", function() {
        var BASEURL = '<?= base_url() ?>';
        var page = 1;
        var isDataLoading = true;
        var isLoading = false;
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500) {
                if (isLoading == false) {
                    isLoading = true;
                    page++;
                    if (isDataLoading) {
                        load_more(page);
                    }
                }
            }
        });

        function load_more(page) {
            $.ajax({
                url: BASEURL + '/posts?page=' + page,
                type: 'GET',
                dataType: 'html',
            }).done(function(data) {
                isLoading = false;
                if (data.length == 0) {
                    isDataLoading = false;
                    $('#loader').hide();
                    return;
                }
                $('#loader').hide();
                $('#main').append(data).show('slow');
            }).fail(function(jqXHR, ajaxOptions, thrownError) {
                console.log('No response');
            });
        }
        [document.querySelector("#header_job_name"), document.querySelector("#header_job_desc"), document.querySelector("#privacy_select")].forEach(item => {
            item.addEventListener("focus", event => {
                document.querySelector("#privacy_select").removeAttribute("hidden")
            })
        })
        document.querySelector("#privacy_select").addEventListener("focusout", event => {
            setTimeout(() => {
                document.querySelector("#privacy_select").setAttribute("hidden", true)
            }, 500)
        })

        function auto_grow(element) {
            element.style.height = "5px";
            element.style.height = (element.scrollHeight) + "px";
        }
    });
</script>
<?= $this->endSection() ?>