<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('section') ?>

<article id="postContainer" class="row mt-2">
</article>
<hr>
<div id="post_comment_div" class="header-post mb-2">
    <img class="rounded-circle border border-light-subtle float-start" height="48" width="48" src="<?= !empty($_SESSION['IMG']) ? base_url('../../assets/img/profiles_pics/' . $_SESSION['USER'] . '/' . $_SESSION['IMG']) : base_url('/assets/logo.png') ?>" alt="Profile pic">
    <form method="post" class="d-flex justify-content-between align-items-center" id="frmComment">
        <div class="ms-3 text-center">
            <textarea oninput="auto_grow(this)" id="post_comment" name="post_comment" placeholder="Comente esta tarefa" required autocomplete="off" style="width: 600px;"></textarea>
        </div>
        <div class="pb-4">
            <button type="submit" class="btn btn-primary fw-bolder">Comentar</button>
        </div>
    </form>
</div>
<hr>
<article id="post-comments">
    nada ainda
</article>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    var session_user_id = '<?= $_SESSION['USER_ID'] ?>';
    var session_profile_pic = '<?= $_SESSION['IMG'] ?>';
    var session_user = '<?= $_SESSION['USER'] ?>'
    const BASEURL = '<?= base_url() ?>';
    var job_id = '<?= $job_id ?>';
    var mainContainer = document.querySelector("#postContainer");
    mainContainer.innerHTML = '';

    var comment = document.querySelector('#post_comment');
    var frmComment = document.querySelector('#frmComment');
    frmComment.addEventListener('submit', function(e) {
        commentJob(session_user_id, job_id, comment.value);
        e.preventDefault();
        comment.value = '';

    });

    $.ajax({
        url: BASEURL + '/job/' + job_id,
        type: "GET",
        headers: {
            'token': 'ihgfedcba987654321'
        },
        error: function(xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    }).done(function(response) {
        mainContainer.innerHTML += `
                        <div class="post-container post">
                            <div class="user-img">
                                <a href="${BASEURL}/profile/${btoa(response.user_id)}">
                                    <img height="48" width="48" src="${!response.profile_pic? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + response.user + '/' + response.profile_pic }" alt="Profile pic">
                                </a>
                            </div>
                            <div class="user-info">
                                <a href="${BASEURL}/profile/${btoa(response.user_id)}" class="user-name">${response.name} &#8226; <span class="text-muted fst-italic">@${response.user}</span></a>
                                <span>
                                    ${session_user_id == response.user_id? 
                                        `<div class="dropdown">
                                            <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis"></i>
                                            </button>
                                            <ul class="dropdown-menu post-it-dropdown">
                                                <li><a data-bs-toggle="modal" data-bs-target="#privacyModal" class="dropdown-item" onclick="fillModalPrivacy(${response.job_id})">Privacidade ${response.job_privacy == 1? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>' }</a></li>
                                                    ${!response.job_finished?
                                                    `<li><a class="dropdown-item" href="${BASEURL + '/todocontroller/jobdone/' + response.job_id}" role="finish" title="Finalizar Tarefa">Finalizar <i class="fa fa-crosshairs text-success"></i></a></li>
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit('${response.job_id}', '${response.job_title}', '${response.job}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>`
                                                    : ``}                                        
                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDelete(${response.job_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                            </ul>
                                        </div>` 
                                        :
                                    `<p> </p>` 
                                        }
                                </span>
                            </div>
                            <div class="user-post-text">
                                <span class="fst-italic text-center d-block fs-5" style="${!response.job_finished? "" : "text-decoration: line-through;" }">${response.job_title}</span>
                                <span id="jobTextContent">${response.job = response.job.replace(/(?:\r\n|\r|\n)/g, '<br>')}</span>
                            </div>
                            <div class="user-post-footer fst-italic text-muted mt-3">
                                <p>${response.job_created}</p>
                                <p>${!response.job_finished? "" : response.job_finished + " <i class='fa fa-check-double'></i>" }</p>
                            </div>
                            <div class="post-actions" id="postActions_${response.job_id}">
                                <a id="likeButton${response.job_id}" href="javascript:void(0)" role="button" onClick="likeJob(${session_user_id},${response.job_id})">
                                    <i class="${response.user_liked? 'fa fa-heart' : 'fa-regular fa-heart' }"></i>
                                    <span id="likes${response.job_id}" class="ms-1 fst-italic text-muted">${response.job_likes}</span>
                                </a>
                                <a href="javascript:void(0)" style="pointer-events: none;" role="button"><i class="fa-regular fa-comment"></i><span class="ms-1 fst-italic text-muted">${response.job_num_comments}</span></a>
                                <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                            </div>
                        </div>
                `;
    });

    function likeJob(user_id, job_id) {
        var dataToSend = {
            user_id: user_id,
            job_id: job_id
        };
        $.ajax({
            url: '<?= base_url('like_job') ?>',
            type: "POST",
            headers: {
                'token': 'ihgfedcba987654321'
            },
            data: dataToSend,
            error: function(xhr, status, error) {
                console.error("Erro na requisição:", error);
            }
        }).done(function(resp) {
            var likeButton = document.querySelector(`#likeButton${job_id}`);
            var session_user_id = '<?= $_SESSION['USER_ID'] ?>';
            const BASEURL = '<?= base_url() ?>';
            likeButton.innerHTML = '';
            let Posts = [];
            $.ajax({
                url: BASEURL + '/job/' + job_id,
                type: "GET",
                headers: {
                    'token': 'ihgfedcba987654321'
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição:", error);
                }
            }).done(function(response) {

                likeButton.innerHTML += `
                        <i id="likeButton${response.job_id}" class="${response.user_liked? 'fa fa-heart' : 'fa-regular fa-heart' }"></i>
                        <span id="likes${response.job_id}" class="ms-1 fst-italic text-muted">${response.job_likes}</span>
                `;
            });
        });
    }

    function commentJob(user_id, job_id, job_comment) {
        var dataToSend = {
            user_id: user_id,
            job_id: job_id,
            job_comment: job_comment
        };
        $.ajax({
            url: '<?= base_url('comment_job') ?>',
            type: "POST",
            headers: {
                'token': 'ihgfedcba987654321'
            },
            data: dataToSend,
            error: function(xhr, status, error) {
                console.error("Erro na requisição:", error);
            }
        }).done(function(resp) {
            var postComments = document.querySelector(`#post-comments`);
            var session_user_id = '<?= $_SESSION['USER_ID'] ?>';
            const BASEURL = '<?= base_url() ?>';
            postComments.innerHTML = '';
            let Posts = [];
            $.ajax({
                url: BASEURL + '/job/' + job_id,
                type: "GET",
                headers: {
                    'token': 'ihgfedcba987654321'
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição:", error);
                }
            }).done(function(response) {
                postComments.innerHTML += `
     
                `;
            });
        });
    }

    function auto_grow(element) {
        element.style.height = "5px";
        element.style.height = (element.scrollHeight) + "px";
    }
</script>
<?= $this->endSection() ?>