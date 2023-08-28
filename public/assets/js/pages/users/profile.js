var currentPage = 1;
var isLoading = false;
var hasMoreData = true;
var headerContainer = document.querySelector("#headerContainer");
var postsContainer = document.querySelector("#postsContainer");
var loadMoreButton = document.querySelector("#loadMore");
var profileViewsContainer = document.querySelector("#profileViewsModalContainer");

document.addEventListener("DOMContentLoaded", function () {
    headerContent(currentPage, profile_user);
    tasksTab(currentPage, profile_user);

    if (session_user_id != profile_user_id) {
        saveVisitForProfile(profile_user_id, session_user_id)
    }

    postsContainer.innerHTML = '';
});

function saveVisitForProfile(profile_user_id, session_user_id) {
    $.ajax({
        url: BASEURL + '/save_visit',
        type: "POST",
        data: {
            user_id: profile_user_id,
            visitor_id: session_user_id
        },
        headers: {
            'token': 'ihgfedcba987654321'
        },
        error: function (xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    });
}

async function fillModalVisits(profile_id) {
    var visitsContainer = document.querySelector("#profileViewsModalContainer");
    visitsContainer.innerHTML = '';

    const paramsObj = {
        profile_id
    };

    const response = await fetch(`${BASEURL}/show_visits`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'token': 'ihgfedcba987654321'
        },
        body: JSON.stringify(paramsObj)
    });
    try {
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const Visits = await response.json();

        Visits.forEach(visit => {
            const visitElement = createVisitElement(visit);
            visitsContainer.appendChild(visitElement);
        })
    } catch (error) {
        console.error("Erro na requisição", error)
    }
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
                    <span id="likes${response.reply.reply_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${response.reply.reply_id}, 'REPLY')">${response.reply.reply_likes}</span>
            `;
        });
    });
}

function headerContent(page, user) {
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
        User = response.user_info;
        headerContainer.innerHTML += `
            <div class="banner">
                <div class="profile-img">
                    <img class="img fluid rounded-circle" height="200" width="200" src="${!User.profile_pic ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + User.user + '/' + User.profile_pic}" alt="Profile pic"">
                    <p class="fst-italic fw-bold text-muted">@${User.user}</p>
                </div>
            </div>
            ${session_user_id == User.user_id ?
                `<div class="text-end">
                    <button class="btn border-0" data-bs-toggle="modal" data-bs-target="#profileViewsModal" title="Visitas" role="button" onclick="fillModalVisits(${profile_user_id})"><i class="fa fa-eye"></i></button>
                </div>`
                : ''}
            `;

    });
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
                                    <img height="48" width="48" src="${!User.profile_pic ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + User.user + '/' + User.profile_pic}" alt="Profile pic">
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
                                            <ul class="dropdown-menu">
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
    hasMoreData = true;
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
        User = response.user_info;
        postsContainer.innerHTML = '';
        if (response.user_jobs === null) {
            hasMoreData = false;
            postsContainer.innerHTML = `<p class='text-center'>${User.user} não postou nada ainda!</p>`;
            loadMoreButton.innerHTML = '';
        } else {
            loadMoreButton.innerHTML = `
            <div class="text-center">
                <a href="javascript:void(0)" class="nav-link fw-bold link-primary" onclick="loadMoreTasks(currentPage, profile_user)">Carregar mais tarefas...</a>
            </div>`;
            Posts = response.user_jobs
            Posts.forEach(function (post) {
                postsContainer.innerHTML += `
                            <div class="post-container post">
                                <div class="user-img">
                                    <a href="${BASEURL}/user/${User.user}">
                                        <img height="48" width="48" src="${!User.profile_pic ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + User.user + '/' + User.profile_pic}" alt="Profile pic">
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
                                                <ul class="dropdown-menu">
                                                    <li><a data-bs-toggle="modal" data-bs-target="#privacyModal" class="dropdown-item" onclick="fillModalPrivacy(${post.job_id})">Privacidade ${post.job_privacy == 1 ? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>'}</a></li>
                                                        ${!post.job_finished ?
                            `<li><a class="dropdown-item" href="${BASEURL + '/todocontroller/jobdone/' + post.job_id}" role="finish" title="Finalizar Tarefa">Finalizar <i class="fa fa-crosshairs text-success"></i></a></li>
                                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit('${post.job_id}', '${post.job_title}', '${post.job}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>`
                            : ``}                                        
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDelete(${post.job_id},1)">Excluír <i class="fa fa-trash text-danger"></i></a></li>
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

function repliesTab(page, user_id) {
    hasMoreData = true;
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
            postsContainer.innerHTML = `<p class='text-center'>${User.user} não respondeu ninguém ainda!</p>`;
            loadMoreButton.innerHTML = '';
        } else {
            loadMoreButton.innerHTML = `
            <div class="text-center">
                <a href="javascript:void(0)" class="nav-link fw-bold link-primary" onclick="loadMoreTasks(currentPage, profile_user)">Carregar mais tarefas...</a>
            </div>`;
            Posts = response.replies
            Posts.forEach(function (post) {
                postsContainer.innerHTML += `
                            <div class="post-container post">
                                <div class="user-img">
                                    <a href="${BASEURL}/user/${User.user}">
                                        <img height="48" width="48" src="${!User.profile_pic ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + User.user + '/' + User.profile_pic}" alt="Profile pic">
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
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEditReply('${post.reply_id}', '${post.reply}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDeleteReply(${post.reply_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                                </ul>
                                            </div>`
                        :
                        `<p> </p>`
                    }
                                    </span>
                                </div>
                                <div class="user-post-text" onclick="commentPage(${post.reply_id})">
                                    <span id="jobTextContent">${post.reply}</span>
                                </div>
                                <div class="user-post-footer fst-italic text-muted mt-3">
                                    <p>${post.datetime_replied}</p>
                                </div>
                                <div class="post-actions" id="replyActions_${post.reply_id}">
                                    <a id="likeCommentButton${post.reply_id}" href="javascript:void(0)" role="button">
                                        <i class="${post.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeComment(${session_user_id},${post.reply_id})"></i>
                                        <span id="likes${post.reply_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${post.reply_id}, 'REPLY')">${post.reply_likes}</span>
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
    hasMoreData = true;
    document.querySelector("#tasksTab").classList.remove("active");
    document.querySelector("#repliesTab").classList.remove("active");
    document.querySelector("#likesTab").classList.add("active");
    postsContainer.innerHTML = '';
    if (isLoading || !hasMoreData) {
        return;
    }
    $.ajax({
        url: BASEURL + '/user_likes/' + user_id,
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
            postsContainer.innerHTML = `<p class='text-center'>${User.user} ainda não curtiu nada!</p>`;
            loadMoreButton.innerHTML = '';
        } else {
            loadMoreButton.innerHTML = `
            <div class="text-center">
                <a href="javascript:void(0)" class="nav-link fw-bold link-primary" onclick="loadMoreLikes(currentPage, profile_user)">Carregar mais curtidas...</a>
            </div>`;
            Posts = response
            Posts.forEach(function (post) {
                if (post.type == 'POST') {
                    postsContainer.innerHTML += `
                            <div class="post-container post">
                                <div class="user-img">
                                    <a href="${BASEURL}/user/${post.content_liked_user}">
                                        <img height="48" width="48" src="${!post.content_liked_user_img ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + post.content_liked_user + '/' + post.content_liked_user_img}" alt="Profile pic">
                                    </a>
                                </div>
                                <div class="user-info">
                                    <a href="${BASEURL}/user/${post.content_liked_user}" class="user-name">${post.content_liked_user_name} &#8226; <span class="text-muted fst-italic">@${post.content_liked_user}</span></a>
                                    <span>
                                        ${session_user_id == post.content_liked_user_id ?
                            `<div class="dropdown">
                                                <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a data-bs-toggle="modal" data-bs-target="#privacyModal" class="dropdown-item" onclick="fillModalPrivacy(${post.content_id})">Privacidade ${post.content_liked_privacy == 1 ? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>'}</a></li>
                                                        ${!post.content_liked_finished ?
                                `<li><a class="dropdown-item" href="${BASEURL + '/todocontroller/jobdone/' + post.content_id}" role="finish" title="Finalizar Tarefa">Finalizar <i class="fa fa-crosshairs text-success"></i></a></li>
                                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit('${post.content_id}', '${post.content_liked_title}', '${post.content_liked_text}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>`
                                : ``}                                        
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDelete(${post.content_id}, 1)">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                                </ul>
                                            </div>`
                            :
                            `<p> </p>`
                        }
                                    </span>
                                </div>
                                <div class="user-post-text" onclick="postPage(${post.content_id})">
                                    <span class="fst-italic text-center d-block fs-5" style="${!post.content_liked_finished ? "" : "text-decoration: line-through;"}">${post.content_liked_title}</span>
                                    <span id="jobTextContent">${post.content_liked_text}</span>
                                </div>
                                <div class="user-post-footer fst-italic text-muted mt-3">
                                    <p>${post.content_liked_created}</p>
                                    <p>${!post.content_liked_finished ? "" : post.content_liked_finished + " <i class='fa fa-check-double'></i>"}</p>
                                </div>
                                <div class="post-actions" id="postActions_${post.content_id}">
                                    <a id="likeButton${post.content_id}" href="javascript:void(0)" role="button">
                                        <i class="${post.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeJob(${session_user_id},${post.content_id})"></i>
                                        <span id="likes${post.content_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${post.content_id}, 'POST')">${post.content_liked_num_likes}</span>
                                    </a>
                                    <a href="javascript:void(0)" onclick="postPage(${post.content_id})" role="button">
                                        <i class="fa-regular fa-comment"></i>
                                        <span class="ms-1 fst-italic text-muted fw-bold fs-6">${post.content_liked_num_comments}</span>
                                        </a>
                                    <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                                </div>
                            </div>
                        `;
                } else {
                    postsContainer.innerHTML += `
                                <div class="post-container post">
                                    <div class="user-img">
                                        <a href="${BASEURL}/user/${post.content_liked_user}">
                                            <img height="48" width="48" src="${!post.profile_pic ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + post.content_liked_user + '/' + post.content_liked_user_img}" alt="Profile pic">
                                        </a>
                                    </div>
                                    <div class="user-info">
                                        <a href="${BASEURL}/user/${post.content_liked_user}" class="user-name">${post.content_liked_user_name} &#8226; <span class="text-muted fst-italic">@${post.content_liked_user}</span></a>
                                        <span>
                                            ${session_user_id == post.content_liked_user_id ?
                            `<div class="dropdown">
                                                    <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-ellipsis"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEditReply('${post.content_id}', '${post.content_liked_text}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>
                                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDeleteReply(${post.content_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                                    </ul>
                                                </div>`
                            :
                            `<p> </p>`
                        }
                                        </span>
                                    </div>
                                    <div class="user-post-text" onclick="commentPage(${post.content_id})">
                                        <span id="jobTextContent">${post.content_liked_text}</span>
                                    </div>
                                    <div class="user-post-footer fst-italic text-muted mt-3">
                                        <p>${post.content_liked_created}</p>
                                    </div>
                                    <div class="post-actions" id="replyActions_${post.content_id}">
                                        <a id="likeCommentButton${post.content_id}" href="javascript:void(0)" role="button">
                                            <i class="${post.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeComment(${session_user_id},${post.content_id})"></i>
                                            <span id="likes${post.content_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${post.content_id}, 'REPLY')">${post.content_liked_num_likes}</span>
                                        </a>
                                        <a href="javascript:void(0)" onclick="commentPage(${post.content_id})" role="button">
                                            <i class="fa-regular fa-comment"></i>
                                            <span class="ms-1 fst-italic text-muted fw-bold fs-6">${post.content_liked_num_comments}</span>
                                            </a>
                                        <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                                    </div>
                                </div>
                            `;

                }
            });
        };
    });
    isLoading = false;
}