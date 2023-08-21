document.addEventListener("DOMContentLoaded", function () {
    $('#btnDeletar').on('click', function () {
        var id = document.getElementById("btnDeletar").getAttribute('data-delete', id);
        $.ajax({
            url: BASEURL + '/reply_delete/' + id,
            type: 'delete',
            headers: {
                'token': 'ihgfedcba987654321'
            },
            success: function (data) {
                msg = document.querySelector('#msgInfo');
                alerta = document.querySelector('#alerta');
                if (!data.error) {
                    mainContainer.innerHTML = '';
                    alerta.classList.add('alert-success');
                    msg.textContent = data.message;
                    document.querySelector('#closeDeleteModal').click();
                        setTimeout(() => {
                            window.history.go(-1);
                        }, 300)
                } else {
                    alerta.classList.add('alert-danger');
                    msg.textContent = data.error;
                }
                new bootstrap.Toast(document.querySelector('#basicToast')).show();

            }
        });
    });
});

frmComment.addEventListener('submit', function(e) {
    commentReply(session_user_id, reply_id, comment.value);
    e.preventDefault();
    comment.value = '';

});

function commentPage(comment_id) {
    window.location.href = BASEURL + '/reply/' + comment_id;
}

$.ajax({
    url: BASEURL + '/comment/' + reply_id,
    type: "GET",
    headers: {
        'token': 'ihgfedcba987654321'
    },
    error: function(xhr, status, error) {
        console.error("Erro na requisição:", error);
    }
}).done(function(response) {
    let Comments = [];
    Comments = response.reply_comments;
    mainContainer.innerHTML += `
                    <div class="post-container post">
                        <div class="user-img">
                            <a href="${BASEURL}/user/${response.reply.user}">
                                <img height="48" width="48" src="${!response.reply.profile_pic? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + response.reply.user + '/' + response.reply.profile_pic }" alt="Profile pic">
                            </a>
                        </div>
                        <div class="user-info">
                            <a href="${BASEURL}/user/${response.reply.user}" class="user-name">${response.reply.name} &#8226; <span class="text-muted fst-italic">@${response.reply.user}</span></a>
                            <span>
                                ${session_user_id == response.reply.user_id? 
                                    `<div class="dropdown">
                                        <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu post-it-dropdown">
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#replyModal" title="Editar Resposta" role="edit" onclick="fillModalEditReply('${response.reply.reply_id}', '${response.reply.reply}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Comentário" role="delete" onclick="fillModalDeleteReply(${response.reply.reply_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                        </ul>
                                    </div>` 
                                    :
                                `<p> </p>` 
                                    }
                            </span>
                        </div>
                        <div class="user-post-text">
                            <span id="replyContent${response.reply.reply_id}">${response.reply.reply.replace(/(?:\r\n|\r|\n)/g, '<br>')}</span>
                        </div>
                        <div class="user-post-footer fst-italic text-muted mt-3">
                            <p>${response.reply.reply_created}</p>
                        </div>
                        <div class="post-actions" id="postActions_${response.reply.reply_id}">
                            <a id="likeReplyButton${response.reply.reply_id}" href="javascript:void(0)" role="button" >
                                <i class="${response.reply.user_liked? 'fa fa-heart' : 'fa-regular fa-heart' }" onClick="likeComment(${session_user_id},${response.reply.reply_id})"></i>
                                <span id="likes${response.reply.reply_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${response.reply.reply_id}, 'REPLY')">${response.reply.reply_likes}</span>
                            </a>
                            <a href="javascript:void(0)" style="pointer-events: none;" role="button">
                                <i class="fa-regular fa-comment"></i>
                                <span class="ms-1 fst-italic text-muted fw-bold fs-6">${response.reply.reply_num_comments}</span>
                            </a>
                            <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                        </div>
                    </div>
            `;
    Comments.forEach(function(post) {
        commentsContainer.innerHTML += `
                <div class="post-container post p-2">
                        <div class="user-img">
                            <a href="${BASEURL}/user/${post.user}">
                                <img height="48" width="48" src="${!post.profile_pic? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + post.user + '/' + post.profile_pic }" alt="Profile pic">
                            </a>
                        </div>
                        <div class="user-info">
                            <a href="${BASEURL}/user/${post.user}" class="user-name">${post.name} &#8226; <span class="text-muted fst-italic">@${post.user}</span></a>
                            <span>
                                ${session_user_id == post.user_id? 
                                    `<div class="dropdown">
                                        <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu post-it-dropdown">
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#replyModal" title="Editar Resposta" role="edit" onclick="fillModalEditReply('${post.comment_id}', '${post.comment}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Comentário" role="delete" onclick="fillModalDeleteReply(${post.comment_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                        </ul>
                                    </div>` 
                                    :
                                `<p> </p>` 
                                    }
                            </span>
                        </div>
                        <div class="user-post-text" onclick="commentPage(${post.comment_id})">
                            <span id="replyCommentContent${post.comment_id}">${post.comment = post.comment.replace(/(?:\r\n|\r|\n)/g, '<br>')}</span>
                        </div>
                        <div class="user-post-footer fst-italic text-muted mt-3">
                            <p>${post.comment_created}</p>
                        </div>
                        <div class="post-actions" id="postActions_${post.comment_id}">
                            <a id="likeReplyButton${post.comment_id}" href="javascript:void(0)" role="button">
                                <i class="${post.user_liked? 'fa fa-heart' : 'fa-regular fa-heart' }" onClick="likeComment(${session_user_id},${post.comment_id})"></i>
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
        error: function(xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    }).done(function(resp) {
        var likeButton = document.querySelector(`#likeReplyButton${comment_id}`);
        likeButton.innerHTML = '';
        let Posts = [];
        $.ajax({
            url: BASEURL + '/comment/' + comment_id,
            type: "GET",
            headers: {
                'token': 'ihgfedcba987654321'
            },
            error: function(xhr, status, error) {
                console.error("Erro na requisição:", error);
            }
        }).done(function(response) {

            likeButton.innerHTML += `
                    <i id="likeReplyButton${response.reply.reply_id}" class="${response.reply.user_liked? 'fa fa-heart' : 'fa-regular fa-heart' }" onClick="likeComment(${session_user_id},${response.reply.reply_id})"></i>
                    <span id="likes${response.reply.reply_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${response.reply.reply_id}, 'REPLY')">${response.reply.reply_likes}</span>
            `;
        });
    });
}

function commentReply(user_id, reply_id, reply_comment) {
    var dataToSend = {
        user_id: user_id,
        reply_id: reply_id,
        reply_comment: reply_comment,
        reply: 1
    };
    $.ajax({
        url: BASEURL + '/comment_job',
        type: "POST",
        headers: {
            'token': 'ihgfedcba987654321'
        },
        data: dataToSend,
        error: function(xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    }).done(function(resp) {
        $.ajax({
            url: BASEURL + '/comment/' + reply_id,
            type: "GET",
            headers: {
                'token': 'ihgfedcba987654321'
            },
            error: function(xhr, status, error) {
                console.error("Erro na requisição:", error);
            }
        }).done(function(response) {
            newComment.innerHTML += `
            <div class="post-container post p-2">
                        <div class="user-img">
                            <a href="${BASEURL}/user/${response.reply_comments[0].user}">
                                <img height="48" width="48" src="${!response.reply_comments[0].profile_pic? BASEURL + '/assets/logo.png' : BASEURL + '/assets/img/profiles_pics/' + response.reply_comments[0].user + '/' + response.reply_comments[0].profile_pic }" alt="Profile pic">
                            </a>
                        </div>
                        <div class="user-info">
                            <a href="${BASEURL}/user/${response.reply_comments[0].user}" class="user-name">${response.reply_comments[0].name} &#8226; <span class="text-muted fst-italic">@${response.reply_comments[0].user}</span></a>
                            <span>
                                ${session_user_id == response.reply_comments[0].user_id? 
                                    `<div class="dropdown">
                                        <button class="bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu post-it-dropdown">
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#taskModal" title="Editar Comentário" role="edit" onclick="fillModalEditReply('${response.reply_comments[0].comment_id}', '${response.reply_comments[0].comment}')">Editar <i class="fa fa-pencil text-primary"></i></a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Excluír Comentário" role="delete" onclick="fillModalDeleteReply(${response.reply_comments[0].comment_id})">Excluír <i class="fa fa-trash text-danger"></i></a></li>
                                        </ul>
                                    </div>` 
                                    :
                                `<p> </p>` 
                                    }
                            </span>
                        </div>
                        <div class="user-post-text">
                            <span id="jobTextContent" onclick="commentPage(${response.reply_comments[0].comment_id})">${(response.reply_comments[0].comment.replace(/(?:\r\n|\r|\n)/g, '<br>'))}</span>
                        </div>
                        <div class="user-post-footer fst-italic text-muted mt-3">
                            <p>${response.reply_comments[0].comment_created}</p>
                        </div>
                        <div class="post-actions" id="postActions_${response.reply_comments[0].comment_id}">
                            <a id="likeReplyButton${response.reply_comments[0].comment_id}" href="javascript:void(0)" role="button">
                                <i class="${response.reply_comments[0].user_liked? 'fa fa-heart' : 'fa-regular fa-heart' }" onClick="likeComment(${session_user_id},${response.reply_comments[0].comment_id})"></i>
                                <span id="likes${response.reply_comments[0].comment_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${response.reply_comments[0].comment_id}, 'REPLY')">${response.reply_comments[0].comment_likes}</span>
                            </a>
                            <a href="javascript:void(0)" style="pointer-events: none;" role="button">
                                <i class="fa-regular fa-comment" onclick="commentPage(${response.reply_comments[0].comment_id})"></i>
                                <span class="ms-1 fst-italic text-muted fw-bold fs-6" onclick="commentPage(${response.reply_comments[0].comment_id})">${response.reply_comments[0].comment_num_comments}</span>
                            </a>
                            <a href="#" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                        </div>
                    </div>
            `;
        });
    });
}

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight) + "px";
}