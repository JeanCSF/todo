var mainContainer = document.querySelector("#postContainer");
var commentsContainer = document.querySelector("#commentsContainer");
var newComment = document.querySelector("#newComment");
var comment = document.querySelector('#post_comment');
var frmComment = document.querySelector('#frmComment');
mainContainer.innerHTML = '';
commentsContainer.innerHTML = '';
newComment.innerHTML = '';

frmComment.addEventListener('submit', function (e) {
    commentJob(session_user_id, job_id, comment.value);
    e.preventDefault();
    comment.value = '';

});

document.addEventListener("DOMContentLoaded", function () {
    loadPost(job_id);
});

async function loadPost(job_id) {
    mainContainer.innerHTML = '';
    const response = await fetch(`${BASEURL}/job/${job_id}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'token': 'ihgfedcba987654321'
        },
    });
    try {
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const Data = await response.json();
        const Post = Data.job;
        const Comments = Data.job_comments;
        const postElement = createPostElement(Post, 'POST');
        mainContainer.appendChild(postElement);
        Comments.forEach(reply => {
            const repliesElement = createPostElement(reply, 'REPLY');
            commentsContainer.appendChild(repliesElement);
        });

    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}

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
    let Comments = [];
    Comments = response.job_comments;
    mainContainer.innerHTML += `
                    <div class="post-container post">
                        <div class="user-img">
                            <a href="${BASEURL}/user/${response.job.user}">
                                <img height="48" width="48" src="${!response.job.profile_pic ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + response.job.user + '/' + response.job.profile_pic}" alt="Profile pic">
                            </a>
                        </div>
                        <div class="user-info">
                            <a href="${BASEURL}/user/${response.job.user}" class="user-name">${response.job.name} &#8226; <span class="text-muted fst-italic">@${response.job.user}</span></a>
                            <span>
                                ${session_user_id == response.job.user_id ?
            `<div class="dropdown">
                                        <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a data-bs-toggle="modal" data-bs-target="#privacyModal" class="dropdown-item" onclick="fillModalPrivacy(${response.job.job_id})">Privacidade ${response.job.job_privacy == 1 ? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>'}</a></li>
                                                ${!response.job.job_finished ?
                `<li><a class="dropdown-item" href="${BASEURL + '/todocontroller/jobdone/' + response.job.job_id}" role="finish" title="Finalizar Tarefa">Finalizar <i class="fa fa-crosshairs text-success"></i></a></li>
                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Tarefa" role="edit" onclick="fillModalEdit('${response.job.job_id}', '${response.job.job_title}', '${response.job.job}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>`
                : ``}                                        
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Tarefa" role="delete" onclick="fillModalDelete(${response.job.job_id},1)">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                        </ul>
                                    </div>`
            :
            `<p> </p>`
        }
                            </span>
                        </div>
                        <div class="user-post-text">
                            <span id="jobTitle" class="fst-italic text-center d-block fs-5 job-title" style="${!response.job.job_finished ? "" : "text-decoration: line-through;"}">${response.job.job_title}</span>
                            <span id="jobTextContent">${response.job.job}</span>
                        </div>
                        <div class="user-post-footer fst-italic text-muted mt-3">
                            <p>${response.job.job_created}</p>
                            <p>${!response.job.job_finished ? "" : response.job.job_finished + " <i class='fa fa-check-double'></i>"}</p>
                        </div>
                        <div class="post-actions" id="postActions_${response.job.job_id}">
                            <a id="likeButton${response.job.job_id}" href="javascript:void(0)" role="button" >
                                <i class="${response.job.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeJob(${session_user_id},${response.job.job_id})"></i>
                                <span id="likes${response.job.job_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${response.job.job_id}, 'POST')">${response.job.job_likes}</span>
                            </a>
                            <a href="javascript:void(0)" style="pointer-events: none;" role="button">
                                <i class="fa-regular fa-comment"></i>
                                <span class="ms-1 fst-italic text-muted fw-bold fs-6">${response.job.job_num_comments}</span>
                                </a>
                            <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                        </div>
                    </div>
            `;
    Comments.forEach(function (post) {
        commentsContainer.innerHTML += `
                <div class="post-container post p-2" id="reply${post.comment_id}">
                        <div class="user-img">
                            <a href="${BASEURL}/user/${post.user}">
                                <img height="48" width="48" src="${!post.profile_pic ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + post.user + '/' + post.profile_pic}" alt="Profile pic">
                            </a>
                        </div>
                        <div class="user-info">
                            <a href="${BASEURL}/user/${post.user}" class="user-name">${post.name} &#8226; <span class="text-muted fst-italic">@${post.user}</span></a>
                            <span>
                                ${session_user_id == post.user_id ?
                `<div class="dropdown">
                                        <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#replyModal" title="Editar Comentário" role="edit" onclick="fillModalEditReply('${post.comment_id}', '${post.comment}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Comentário" role="delete" onclick="fillModalDeleteReply(${post.comment_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                        </ul>
                                    </div>`
                :
                `<p> </p>`
            }
                            </span>
                        </div>
                        <div class="user-post-text" onclick="commentPage(${post.comment_id})">
                            <span id="jobReplyContent${post.comment_id}">${post.comment}</span>
                        </div>
                        <div class="user-post-footer fst-italic text-muted mt-3">
                            <p>${post.comment_created}</p>
                        </div>
                        <div class="post-actions" id="postActions_${post.comment_id}">
                            <a id="likeCommentButton${post.comment_id}" href="javascript:void(0)" role="button">
                                <i class="${post.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeComment(${session_user_id},${post.comment_id})"></i>
                                <span id="likes${post.comment_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${post.comment_id}, 'REPLY')">${post.comment_likes}</span>
                            </a>
                            <a href="javascript:void(0)" role="button" onclick="commentPage(${post.comment_id})">
                                <i class="fa-regular fa-comment"></i>
                                <span class="ms-1 fst-italic text-muted fw-bold fs-6">${post.comment_num_comments}</span>
                                </a>
                            <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                        </div>
                    </div>

            `;
    });
});

function commentJob(user_id, job_id, job_comment) {
    var dataToSend = {
        user_id: user_id,
        job_id: job_id,
        job_comment: job_comment,
        reply: 0
    };
    $.ajax({
        url: BASEURL + '/comment_job',
        type: "POST",
        headers: {
            'token': 'ihgfedcba987654321'
        },
        data: dataToSend,
        error: function (xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    }).done(function (resp) {
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
            newComment.innerHTML += `
            <div class="post-container post p-2" id="reply${response.job_comments[0].comment_id}">
                        <div class="user-img">
                            <a href="${BASEURL}/user/${response.job_comments[0].user}">
                                <img height="48" width="48" src="${!response.job_comments[0].profile_pic ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + response.job_comments[0].user + '/' + response.job_comments[0].profile_pic}" alt="Profile pic">
                            </a>
                        </div>
                        <div class="user-info">
                            <a href="${BASEURL}/user/${response.job_comments[0].user}" class="user-name">${response.job_comments[0].name} &#8226; <span class="text-muted fst-italic">@${response.job_comments[0].user}</span></a>
                            <span>
                                ${session_user_id == response.job_comments[0].user_id ?
                    `<div class="dropdown">
                                        <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Comentário" role="edit" onclick="fillModalEdit('${response.job_comments[0].comment_id}', '${response.job_comments[0].comment}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Comentário" role="delete" onclick="fillModalDeleteReply(${response.job_comments[0].comment_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                        </ul>
                                    </div>`
                    :
                    `<p> </p>`
                }
                            </span>
                        </div>
                        <div class="user-post-text">
                            <span id="jobTextContent" onclick="commentPage(${response.job_comments[0].comment_id})">${response.job_comments[0].comment}</span>
                        </div>
                        <div class="user-post-footer fst-italic text-muted mt-3">
                            <p>${response.job_comments[0].comment_created}</p>
                        </div>
                        <div class="post-actions" id="postActions_${response.job_comments[0].comment_id}">
                            <a id="likeCommentButton${response.job_comments[0].comment_id}" href="javascript:void(0)" role="button">
                                <i class="${response.job_comments[0].user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'}" onClick="likeComment(${session_user_id},${response.job_comments[0].comment_id})"></i>
                                <span id="likes${response.job_comments[0].comment_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${response.job_comments[0].comment_id}, 'REPLY')">${response.job_comments[0].comment_likes}</span>
                            </a>
                            <a href="javascript:void(0)" role="button">
                                <i class="fa-regular fa-comment"></i>
                                <span class="ms-1 fst-italic text-muted fw-bold fs-6" onclick="commentPage(${response.job_comments[0].comment_id})">${response.job_comments[0].comment_num_comments}</span>
                            </a>
                            <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                        </div>
                    </div>
            `;
        });
    });
}