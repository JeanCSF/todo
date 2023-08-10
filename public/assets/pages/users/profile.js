// MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
// var observer = new MutationObserver(function (mutations, observer) {
//     saveContent();
// });

// observer.observe(document, {
//     subtree: true,
//     attributes: true,
//     childList: true,
//     attributeOldValue: true,
//     characterData: true,
//     characterDataOldValue: true
// });


document.addEventListener("DOMContentLoaded", function () {
    let content = localStorage.getItem("html");
    if (!content) {
        headerContainer.innerHTML = '';
        postsContainer.innerHTML = '';
        loadAll(currentPage, profile_user);
        document.body.innerHTML = content;
    } else {
        document.querySelector("#tasksTab").classList.add("active");
        headerContainer.innerHTML = '';
        postsContainer.innerHTML = '';
        loadAll(currentPage, profile_user);
    }
});

function saveContent() {
    localStorage.setItem("html", document.body.innerHTML)
}

function postPage(job_id) {
    window.location.href = BASEURL + '/post/' + job_id;
}

function commentPage(reply_id) {
    window.location.href = BASEURL + '/reply/' + reply_id;
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
                        <span id="likes${response.job.job_id}" class="ms-1 fst-italic text-muted fw-bold fs-6">${response.job.job_likes}</span>
                `;
        });
    });
}

function likeComment(user_id, comment_id) {
    var dataToSend = {
        user_id: user_id,
        comment_id: comment_id
    };
    $.ajax({
        url: BASEURL + '/like_comment',
        type: "POST",
        headers: {
            'token': 'ihgfedcba987654321'
        },
        data: dataToSend,
        error: function (xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    }).done(function (resp) {
        var likeButton = document.querySelector(`#likeCommentButton${comment_id}`);
        likeButton.innerHTML = '';
        let Posts = [];
        $.ajax({
            url: BASEURL + '/comment/' + comment_id,
            type: "GET",
            headers: {
                'token': 'ihgfedcba987654321'
            },
            error: function (xhr, status, error) {
                console.error("Erro na requisição:", error);
            }
        }).done(function (response) {

            likeButton.innerHTML += `
                    <i id="likeCommentButton${response.reply.reply_id}" class="${response.reply.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeComment(${session_user_id},${response.reply.reply_id})"></i>
                    <span id="likes${response.reply.reply_id}" class="ms-1 fst-italic text-muted fw-bold fs-6">${response.reply.reply_likes}</span>
            `;
        });
    });
}

function loadAll(page, user) {
    document.addEventListener("click", function () {
        saveContent();
    })
    document.querySelector("#likesTab").classList.remove("active");
    document.querySelector("#repliesTab").classList.remove("active");
    headerContainer.innerHTML = '';
    postsContainer.innerHTML = '';
    document.querySelector("#tasksTab").classList.add("active");
    if (isLoading || !hasMoreData) {
        loadMoreButton.innerHTML = '';
        return;
    } else {
        loadMoreButton.innerHTML += `
            <div class="text-center">
                <a href="javascript:void(0)" class="nav-link fw-bold link-primary" onclick="loadMoreTasks(currentPage, profile_user)">Carregar mais tarefas...</a>
            </div>`;
    }
    $.ajax({
        url: BASEURL + '/profile/' + user,
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
            User = response.user_info;
            headerContainer.innerHTML += `
            <div class="banner">
                <div class="profile-img">
                    <img class="img fluid rounded-circle" height="200" width="200" src="${!User.profile_pic ? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + User.user + '/' + User.profile_pic}" alt="Profile pic"">
                    <p class="fst-italic fw-bold text-muted">@${User.user}</p>
                    <p class="fst-italic fw-light text-muted">Since 97</p>
                </div>
            </div>
            `;
            Posts = response.user_jobs
            Posts.forEach(function (post) {
                postsContainer.innerHTML += `
                        <div class="post-container post">
                            <div class="user-img">
                                <a href="${BASEURL}/user/${User.user}">
                                    <img height="48" width="48" src="${!User.profile_pic ? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + User.user + '/' + User.profile_pic}" alt="Profile pic">
                                </a>
                            </div>
                            <div class="user-info">
                                <a href="${BASEURL}/user/${User.user}" class="user-name">${User.name} &#8226; <span class="text-muted fst-italic">@${User.user}</span></a>
                                <span>
                                    ${session_user_id == User.user_id ?
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
                                <span id="jobTextContent">${post.job = post.job.replace(/(?:\r\n|\r|\n)/g, '<br>')}</span>
                            </div>
                            <div class="user-post-footer fst-italic text-muted mt-3">
                                <p>${post.job_created}</p>
                                <p>${!post.job_finished ? "" : post.job_finished + " <i class='fa fa-check-double'></i>"}</p>
                            </div>
                            <div class="post-actions" id="postActions_${post.job_id}">
                                <a id="likeButton${post.job_id}" href="javascript:void(0)" role="button">
                                    <i class="${post.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeJob(${session_user_id},${post.job_id})"></i>
                                    <span id="likes${post.job_id}" class="ms-1 fst-italic text-muted fw-bold fs-6">${post.job_likes}</span>
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

function loadMoreTasks(page, user) {
    loadMoreButton.innerHTML = '';
    if (isLoading || !hasMoreData) {
        loadMoreButton.innerHTML = '';
        return;
    } else {
        loadMoreButton.innerHTML += `
            <div class="text-center">
                <a href="javascript:void(0)" class="nav-link fw-bold link-primary" onclick="loadMoreTasks(currentPage, profile_user)">Carregar mais tarefas...</a>
            </div>`;
    }
    page++;
    $.ajax({
        url: BASEURL + '/profile/' + user,
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
            Posts = response.user_jobs
            Posts.forEach(function (post) {
                postsContainer.innerHTML += `
                        <div class="post-container post">
                            <div class="user-img">
                                <a href="${BASEURL}/user/${User.user}">
                                    <img height="48" width="48" src="${!User.profile_pic ? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + User.user + '/' + User.profile_pic}" alt="Profile pic">
                                </a>
                            </div>
                            <div class="user-info">
                                <a href="${BASEURL}/user/${User.user}" class="user-name">${User.name} &#8226; <span class="text-muted fst-italic">@${User.user}</span></a>
                                <span>
                                    ${session_user_id == User.user_id ?
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
                                <span id="jobTextContent">${post.job = post.job.replace(/(?:\r\n|\r|\n)/g, '<br>')}</span>
                            </div>
                            <div class="user-post-footer fst-italic text-muted mt-3">
                                <p>${post.job_created}</p>
                                <p>${!post.job_finished ? "" : post.job_finished + " <i class='fa fa-check-double'></i>"}</p>
                            </div>
                            <div class="post-actions" id="postActions_${post.job_id}">
                                <a id="likeButton${post.job_id}" href="javascript:void(0)" role="button">
                                    <i class="${post.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeJob(${session_user_id},${post.job_id})"></i>
                                    <span id="likes${post.job_id}" class="ms-1 fst-italic text-muted fw-bold fs-6">${post.job_likes}</span>
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

function tasksTab(page, user) {
    document.querySelector("#likesTab").classList.remove("active");
    document.querySelector("#repliesTab").classList.remove("active");
    document.querySelector("#tasksTab").classList.add("active");
    if (isLoading || !hasMoreData) {
        return;
    }
    $.ajax({
        url: BASEURL + '/profile/' + user,
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
            Posts = response.user_jobs
            Posts.forEach(function (post) {
                postsContainer.innerHTML += `
                        <div class="post-container post">
                            <div class="user-img">
                                <a href="${BASEURL}/user/${User.user}">
                                    <img height="48" width="48" src="${!User.profile_pic ? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + User.user + '/' + User.profile_pic}" alt="Profile pic">
                                </a>
                            </div>
                            <div class="user-info">
                                <a href="${BASEURL}/user/${User.user}" class="user-name">${User.name} &#8226; <span class="text-muted fst-italic">@${User.user}</span></a>
                                <span>
                                    ${session_user_id == User.user_id ?
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
                                <span id="jobTextContent">${post.job = post.job.replace(/(?:\r\n|\r|\n)/g, '<br>')}</span>
                            </div>
                            <div class="user-post-footer fst-italic text-muted mt-3">
                                <p>${post.job_created}</p>
                                <p>${!post.job_finished ? "" : post.job_finished + " <i class='fa fa-check-double'></i>"}</p>
                            </div>
                            <div class="post-actions" id="postActions_${post.job_id}">
                                <a id="likeButton${post.job_id}" href="javascript:void(0)" role="button">
                                    <i class="${post.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeJob(${session_user_id},${post.job_id})"></i>
                                    <span id="likes${post.job_id}" class="ms-1 fst-italic text-muted fw-bold fs-6">${post.job_likes}</span>
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

function repliesTab(page, user_id) {
    document.querySelector("#likesTab").classList.remove("active");
    document.querySelector("#tasksTab").classList.remove("active");
    document.querySelector("#repliesTab").classList.add("active");
    postsContainer.innerHTML = '';
    if (isLoading || !hasMoreData) {
        return;
    }
    $.ajax({
        url: BASEURL + '/user_comments/' + user_id,
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
            Posts = response.replies
            Posts.forEach(function (post) {
                postsContainer.innerHTML += `
                        <div class="post-container post">
                            <div class="user-img">
                                <a href="${BASEURL}/user/${User.user}">
                                    <img height="48" width="48" src="${!User.profile_pic ? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + User.user + '/' + User.profile_pic}" alt="Profile pic">
                                </a>
                            </div>
                            <div class="user-info">
                                <a href="${BASEURL}/user/${User.user}" class="user-name">${User.name} &#8226; <span class="text-muted fst-italic">@${User.user}</span></a>
                                <span>
                                    ${session_user_id == User.user_id ?
                        `<div class="dropdown">
                                            <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis"></i>
                                            </button>
                                            <ul class="dropdown-menu post-it-dropdown">
                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEditReply('${post.reply_id}', '${post.reply}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>
                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDelete(${post.reply_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                            </ul>
                                        </div>`
                        :
                        `<p> </p>`
                    }
                                </span>
                            </div>
                            <div class="user-post-text" onclick="commentPage(${post.reply_id})">
                                <span id="jobTextContent">${post.reply = post.reply.replace(/(?:\r\n|\r|\n)/g, '<br>')}</span>
                            </div>
                            <div class="user-post-footer fst-italic text-muted mt-3">
                                <p>${post.datetime_replied}</p>
                            </div>
                            <div class="post-actions" id="replyActions_${post.reply_id}">
                                <a id="likeCommentButton${post.reply_id}" href="javascript:void(0)" role="button">
                                    <i class="${post.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeComment(${session_user_id},${post.reply_id})"></i>
                                    <span id="likes${post.reply_id}" class="ms-1 fst-italic text-muted fw-bold fs-6">${post.reply_likes}</span>
                                </a>
                                <a href="javascript:void(0)" onclick="commentPage(${post.reply_id})" role="button">
                                    <i class="fa-regular fa-comment"></i>
                                    <span class="ms-1 fst-italic text-muted fw-bold fs-6">${post.reply_num_comments}</span>
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

function likesTab(page, user_id) {
    document.querySelector("#tasksTab").classList.remove("active");
    document.querySelector("#repliesTab").classList.remove("active");
    document.querySelector("#likesTab").classList.add("active");
    postsContainer.innerHTML = '';
    if (isLoading || !hasMoreData) {
        return;
    }
}