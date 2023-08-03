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
<article id="postContainer" class="row">

</article>
<div id="main"></div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    let Posts = [];
    var currentPage = 1;
    var isLoading = false;
    var hasMoreData = true;
    const BASEURL = '<?= base_url() ?>';
    var mainContainer = document.querySelector("#postContainer");
    var session_user_id = '<?= $_SESSION['USER_ID'] ?>';
    $(window).scroll(function() {
        if (hasMoreData && !isLoading && $(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadMorePosts(currentPage)
        }
    });

    function loadMorePosts(page) {
        if (isLoading || !hasMoreData) {
            return;
        }
        $.ajax({
            url: '<?= base_url('all_jobs') ?>',
            type: "GET",
            data: {
                page: page
            },
            headers: {
                'token': 'ihgfedcba987654321'
            },
            error: function(xhr, status, error) {
                console.error("Erro na requisição:", error);
            }
        }).done(function(response) {
            if (response.length === 0) {
                hasMoreData = false; // Não há mais dados a serem carregados
            } else {
                Posts = response;
                Posts.forEach(function(post) {
                    mainContainer.innerHTML += `
                    <div class="post-container post">
                        <div class="user-img">
                            <a href="${BASEURL}/profile/${btoa(post.user_id)}">
                                <img height="48" width="48" src="${!post.profile_pic? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + post.user + '/' + post.profile_pic }" alt="Profile pic">
                            </a>
                        </div>
                        <div class="user-info">
                    <a href="${BASEURL}/profile/${btoa(post.user_id)}" class="user-name">${post.name} &#8226; <span class="text-muted fst-italic">@${post.user}</span></a>
                    <span>
                        ${session_user_id == post.user_id? 
                            `<div class="dropdown">
                                <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu post-it-dropdown">
                                    <li><a data-bs-toggle="modal" data-bs-target="#privacyModal" class="dropdown-item" onclick="fillModalPrivacy(${post.job_id})">Privacidade ${post.job_privacy == 1? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>' }</a></li>
                                        ${!post.job_finished?
                                        `<li><a class="dropdown-item" href="${BASEURL + '/todocontroller/jobdone/' + post.job_id}" role="finish" title="Finalizar Tarefa">Finalizar <i class="fa fa-crosshairs text-success"></i></a></li>
                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit(${post.job_id},  ${post.job_title}, ${post.job})">Editar <i class="fa fa-pencil text-primary"></i></a></li>`
                                        : ``}                                        
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDelete(${post.job_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                </ul>
                            </div>` 
                            :
                        `<p> </p>` 
                            }
                    </span>
                    </div>
                    <div class="user-post-text">
                        <span class="fst-italic text-center d-block fs-5" style="${!post.job_finished? "" : "text-decoration: line-through;" }">${post.job_title}</span>
                        <span id="jobTextContent">${post.job = post.job.replace(/(?:\r\n|\r|\n)/g, '<br>')}</span>
                    </div>
                    <div class="user-post-footer fst-italic text-muted mt-3">
                        <p>${post.job_created}</p>
                        <p>${!post.job_finished? "" : post.job_finished + " <i class='fa fa-check-double'></i>" }</p>
                    </div>
                    <div class="post-actions" id="postActions_${post.job_id}">
                        <a id="likeButton${post.job_id}" href="javascript:void(0)" role="button" onClick="likeJob(${session_user_id},${post.job_id})">
                            <i class="${post.user_liked? 'fa fa-heart' : 'fa-regular fa-heart' }"></i>
                            <span id="likes${post.job_id}" class="ms-1 fst-italic text-muted">${post.job_likes}</span>
                        </a>
                        <a href="#" role="button"><i class="fa-regular fa-comment"></i><span class="ms-1 fst-italic text-muted">{elapsed_time}</span></a>
                        <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                    </div>
                </div>
                `;
                    currentPage = page + 1;
                });
            };
            isLoading = false;
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
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        loadMorePosts(currentPage);
    });
</script>
<script>
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
</script>
<?= $this->endSection() ?>