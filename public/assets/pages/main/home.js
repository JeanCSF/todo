document.addEventListener("DOMContentLoaded", function () {
    loadPosts(currentPage);
    $('#btnDeletar').on('click', function () {
        var id = document.getElementById("btnDeletar").getAttribute('data-delete', id);
        $.ajax({
            url: BASEURL + '/job_delete/' + id,
            type: 'delete',
            headers: {
                'token': 'ihgfedcba987654321'
            },
            success: function (data) {
                msg = document.querySelector('#msgInfo');
                alerta = document.querySelector('#alerta');
                if (!data.error) {
                    alerta.classList.add('alert-success');
                    msg.textContent = data.message;
                    document.querySelector('#post' + id).remove();
                    document.querySelector('#closeDeleteModal').click();
                } else {
                    alerta.classList.add('alert-danger');
                    msg.textContent = data.error;
                }
                new bootstrap.Toast(document.querySelector('#basicToast')).show();

            }
        });
    });
});

function profilePage(user) {
    window.location.href = BASEURL + '/user/' + user;
}

function postPage(job_id) {
    window.location.href = BASEURL + '/post/' + job_id;
}

function debounce(func, delay) {
    let timer;
    return function () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            func.apply(this, arguments);
        }, delay);
    };
}

$(window).scroll(debounce(function () {
    if (hasMoreData && !isLoading && $(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
        loadMorePosts(currentPage);
    }
}, 500));



function loadPosts(page) {
    if (isLoading || !hasMoreData) {
        return;
    }
    $.ajax({
        url: BASEURL + '/all_jobs',
        type: "GET",
        data: {
            page: page
        },
        headers: {
            'token': 'ihgfedcba987654321'
        },
        error: function (xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    }).done(function (response) {
        if (response.length === 0) {
            hasMoreData = false;
        } else {
            Posts = response;
            Posts.forEach(function (post) {
                mainContainer.innerHTML += `
                        <div class="post-container post" id="post${post.job_id}">
                            <div class="user-img">
                                <a href="${BASEURL + '/user/' + post.user}">
                                    <img height="48" width="48" src="${!post.profile_pic ? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + post.user + '/' + post.profile_pic}" alt="Profile pic">
                                </a>
                            </div>
                            <div class="user-info">
                                <a href="${BASEURL + '/user/' + post.user}" class="user-name">${post.name} &#8226; <span class="text-muted fst-italic">@${post.user}</span></a>
                                <span>
                                    ${session_user_id == post.user_id ?
                        `<div class="dropdown">
                                            <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis"></i>
                                            </button>
                                            <ul class="dropdown-menu post-it-dropdown">
                                                <li><a data-bs-toggle="modal" data-bs-target="#privacyModal" class="dropdown-item" onclick="fillModalPrivacy(${post.job_id})">Privacidade ${post.job_privacy == 1 ? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>'}</a></li>
                                                    ${!post.job_finished ?
                            `<li><a class="dropdown-item" href="${BASEURL + '/todocontroller/jobdone/' + post.job_id}" role="finish" title="Finalizar Tarefa">Finalizar <i class="fa fa-crosshairs text-success"></i></a></li>
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit('${post.job_id}', '${post.job_title}', '${post.job}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>`
                            : ``}                                        
                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDelete(${post.job_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                            </ul>
                                        </div>`
                        :
                        `<p> </p>`
                    }
                                </span>
                            </div>
                            <div class="user-post-text" onclick="postPage(${post.job_id})">
                                <span class="fst-italic text-center d-block fs-5" style="${!post.job_finished ? "" : "text-decoration: line-through;"}">${post.job_title}</span>
                                <span id="jobTextContent">${post.job}</span>
                            </div>
                            <div class="user-post-footer fst-italic text-muted mt-3">
                                <p>${post.job_created}</p>
                                <p>${!post.job_finished ? "" : post.job_finished + " <i class='fa fa-check-double'></i>"}</p>
                            </div>
                            <div class="post-actions" id="postActions_${post.job_id}">
                                <a id="likeButton${post.job_id}" href="javascript:void(0)" role="button">
                                    <i class="${post.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeJob(${session_user_id},${post.job_id})"></i>
                                    <span id="likes${post.job_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${post.job_id}, 'POST')">${post.job_likes}</span>
                                </a>
                                <a href="javascript:void(0)" onclick="postPage(${post.job_id})" role="button">
                                    <i class="fa-regular fa-comment"></i>
                                    <span class="ms-1 fst-italic text-muted fw-bold fs-6">${post.job_num_comments}</span>
                                </a>
                                <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                            </div>
                        </div>
                    `;
            });
        };
    });
    isLoading = false;
}

function loadMorePosts(page) {
    if (isLoading || !hasMoreData) {
        return;
    }
    isLoading = true;
    page++;
    $.ajax({
        url: BASEURL + '/all_jobs',
        type: "GET",
        data: {
            page: page
        },
        headers: {
            'token': 'ihgfedcba987654321'
        },
        error: function (xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    }).done(function (response) {
        if (response.length === 0) {
            hasMoreData = false;
        } else {
            Posts = response;
            Posts.forEach(function (post) {
                mainContainer.innerHTML += `
                        <div class="post-container post">
                            <div class="user-img">
                                <a href="${BASEURL + '/user/' + post.user}">
                                    <img height="48" width="48" src="${!post.profile_pic ? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + post.user + '/' + post.profile_pic}" alt="Profile pic">
                                </a>
                            </div>
                            <div class="user-info">
                                <a href="${BASEURL + '/user/' + post.user}" class="user-name">${post.name} &#8226; <span class="text-muted fst-italic">@${post.user}</span></a>
                                <span>
                                    ${session_user_id == post.user_id ?
                        `<div class="dropdown">
                                            <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis"></i>
                                            </button>
                                            <ul class="dropdown-menu post-it-dropdown">
                                                <li><a data-bs-toggle="modal" data-bs-target="#privacyModal" class="dropdown-item" onclick="fillModalPrivacy(${post.job_id})">Privacidade ${post.job_privacy == 1 ? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>'}</a></li>
                                                    ${!post.job_finished ?
                            `<li><a class="dropdown-item" href="${BASEURL + '/todocontroller/jobdone/' + post.job_id}" role="finish" title="Finalizar Tarefa">Finalizar <i class="fa fa-crosshairs text-success"></i></a></li>
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit('${post.job_id}', '${post.job_title}', '${post.job}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>`
                            : ``}                                        
                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDelete(${post.job_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                            </ul>
                                        </div>`
                        :
                        `<p> </p>`
                    }
                                </span>
                            </div>
                            <div class="user-post-text" onclick="postPage(${post.job_id})">
                                <span class="fst-italic text-center d-block fs-5" style="${!post.job_finished ? "" : "text-decoration: line-through;"}">${post.job_title}</span>
                                <span id="jobTextContent">${post.job}</span>
                            </div>
                            <div class="user-post-footer fst-italic text-muted mt-3">
                                <p>${post.job_created}</p>
                                <p>${!post.job_finished ? "" : post.job_finished + " <i class='fa fa-check-double'></i>"}</p>
                            </div>
                            <div class="post-actions" id="postActions_${post.job_id}">
                                <a id="likeButton${post.job_id}" href="javascript:void(0)" role="button">
                                    <i class="${post.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeJob(${session_user_id},${post.job_id})"></i>
                                    <span id="likes${post.job_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${post.job_id}, 'POST')">${post.job_likes}</span>
                                </a>
                                <a href="javascript:void(0)" onclick="postPage(${post.job_id})" role="button">
                                    <i class="fa-regular fa-comment"></i>
                                    <span class="ms-1 fst-italic text-muted fw-bold fs-6">${post.job_num_comments}</span>
                                </a>
                                <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                            </div>
                        </div>
                    `;
                currentPage = page;
            });
        };
    });
    isLoading = false;
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

function likeJob(user_id, job_id) {
    var dataToSend = {
        user_id: user_id,
        job_id: job_id
    };
    $.ajax({
        url: BASEURL + '/like_job',
        type: "POST",
        headers: {
            'token': 'ihgfedcba987654321'
        },
        data: dataToSend,
        error: function (xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    }).done(function (resp) {
        var likeButton = document.querySelector(`#likeButton${job_id}`);
        likeButton.innerHTML = '';
        let Posts = [];
        $.ajax({
            url: BASEURL + '/job/' + job_id,
            type: "GET",
            headers: {
                'token': 'ihgfedcba987654321'
            },
            error: function (xhr, status, error) {
                console.error("Erro na requisição:", error);
            }
        }).done(function (response) {

            likeButton.innerHTML += `
                        <i id="likeButton${response.job.job_id}" class="${response.job.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeJob(${session_user_id},${response.job.job_id})"></i>
                        <span id="likes${response.job.job_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${response.job.job_id}, 'POST')">${response.job.job_likes}</span>
                `;
        });
    });
}