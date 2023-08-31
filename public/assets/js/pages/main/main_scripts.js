document.querySelector("#themeToggle").addEventListener("click", function () {
    let body = document.querySelector("#body");
    let theme = body.getAttribute("data-bs-theme")
    if (theme === 'light') {
        body.setAttribute("data-bs-theme", 'dark')
    }
    else {
        body.setAttribute("data-bs-theme", 'light')
    }
});

document.addEventListener("DOMContentLoaded", function () {
    $('#btnDeletar').on('click', function () {
        var id = document.getElementById("btnDeletar").getAttribute('data-delete', id);
        var type = document.getElementById("btnDeletar").getAttribute('data-type', type);
        if (type == "null") {
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
                        alerta.classList.add('alert-success');
                        msg.textContent = data.message;
                        document.querySelector('#reply' + id).remove();
                        document.querySelector('#closeDeleteModal').click();
                    } else {
                        alerta.classList.add('alert-danger');
                        msg.textContent = data.error;
                    }
                    new bootstrap.Toast(document.querySelector('#basicToast')).show();
                }
            });
        } else if(type == 'home'){
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
        } else {
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
        }
    });
});

const createElements = (elementName, attributes) => {
    const element = document.createElement(elementName);
    const attributesAsArray = Object.entries(attributes);

    attributesAsArray.forEach(([key, value]) => element.setAttribute(key, value));

    return element;
}

function createProfileLink(data) {
    const profileLink = createElements('a', {
        href: `${BASEURL}/user/${data.user}`,
        class: 'nav-link'
    });

    const profilePic = createProfilePic(data);
    const profileName = createElements('span', {
        class: 'fw-bold'
    });
    profileName.textContent = data.name;

    profileLink.appendChild(profilePic);
    profileLink.appendChild(profileName);

    return profileLink;
}

function createProfilePic(data) {
    return createElements('img', {
        class: 'rounded-circle me-3',
        height: 48,
        width: 48,
        alt: 'Profile Pic',
        src: !data.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profiles_pics/${data.user}/${data.profile_pic}`
    });
}

function createTimestampElement(data) {
    return createElements('span', {
        class: 'text-muted fst-italic p-3',
        style: 'font-size: 10px;',
        title: data.full_datetime_liked
    }, data.datetime_liked);
}

function createLikeElement(like) {
    const container = createElements('div', {
        class: 'd-flex justify-content-between my-2',
        id: `like_${like.user}`
    });

    const profileLink = createProfileLink(like);
    const timestamp = createTimestampElement(like);

    container.appendChild(profileLink);
    container.appendChild(timestamp);

    return container;
}

function createVisitElement(visit) {

    const container = createElements('div', {
        class: 'd-flex justify-content-between my-2',
        id: `like_${visit.user}`
    });

    const profileLink = createProfileLink(visit);
    const timestamp = createTimestampElement(visit);

    container.appendChild(profileLink);
    container.appendChild(timestamp);

    return container;
}

async function fillModalLikes(content_id, type) {
    var likesContainer = document.querySelector("#likesModalContainer");
    likesContainer.innerHTML = '';
    const paramsObj = {
        content_id,
        type
    };

    const response = await fetch(`${BASEURL}/show_likes`, {
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

        const Likes = await response.json();

        Likes.forEach(like => {

            const likeElement = createLikeElement(like);
            likesContainer.appendChild(likeElement);
        });
    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}

function fillModalEdit(id, job, desc) {
    document.getElementById("form").setAttribute('action', BASEURL + 'todocontroller/editjobsubmit');
    document.getElementById("taskModalLabel").textContent = "Atualizar Tarefa";
    document.getElementById("btnSubmit").setAttribute('value', 'Atualizar');
    document.getElementById("id_job").setAttribute('value', id);
    document.getElementById("job_name").setAttribute('value', job);
    document.getElementById("job_desc").setAttribute('value', desc);
    document.getElementById("job_desc").textContent = '' + desc;

}

function fillModalEditReply(id, reply) {
    document.getElementById("btnSubmit").setAttribute('value', 'Atualizar');
    document.getElementById("reply_id").setAttribute('value', id);
    document.getElementById("reply_content").setAttribute('value', reply);
    document.getElementById("reply_content").textContent = reply;


}

function fillModalDelete(id, type) {
    document.getElementById("modalTitle").textContent = "Deletar Tarefa";
    document.getElementById("bodyMsg").textContent = "Deseja realmente deletar esta tarefa?";
    document.getElementById("btnDeletar").setAttribute('data-delete', id);
    document.getElementById("btnDeletar").setAttribute('data-type', type);
}

function fillModalDeleteReply(id, type = null) {
    document.getElementById("modalTitle").textContent = "Deletar Resposta";
    document.getElementById("bodyMsg").textContent = "Deseja realmente deletar esta resposta?";
    document.getElementById("btnDeletar").setAttribute('data-delete', id);
    document.getElementById("btnDeletar").setAttribute('data-type', type);

}

function fillModalDeleteUser(id) {
    document.getElementById("formDelete").setAttribute('action', BASEURL + 'userscontroller/delete');
    document.getElementById("modalTitle").textContent = "Deletar Usuário";
    document.getElementById("bodyMsg").textContent = "Deseja realmente deletar este usuário?";
    document.getElementById("id").setAttribute('value', id);

}

function fillModalNewJob() {
    document.getElementById("form").setAttribute('action', BASEURL + 'todocontroller/newjobsubmit');
    document.getElementById("taskModalLabel").textContent = "Adicionar Tarefa";
    document.getElementById("btnSubmit").setAttribute('value', 'Gravar');
    document.getElementById("id_job").setAttribute('value', '');
    document.getElementById("job_name").setAttribute('value', '');
    document.getElementById("job_desc").setAttribute('value', '');
    document.getElementById("job_desc").textContent = '';
}

function fillModalPlus(job, title) {
    document.getElementById("plusTaskModalDesc").textContent = job;
    document.getElementById("plusTaskModalTitle").textContent = title;
}

function fillModalPrivacy(id) {
    document.getElementById("privacy_id").setAttribute('value', id)
}

function postPage(job_id) {
    window.location.href = BASEURL + '/post/' + job_id;
}

function commentPage(comment_id) {
    window.location.href = BASEURL + '/reply/' + comment_id;
}

function profilePage(user) {
    window.location.href = BASEURL + '/user/' + user;
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

async function likeContent(user_id, content_id, type) {
    const paramsObj = {
        user_id,
        content_id,
        type
    };

    try {
        const response = await fetch(`${BASEURL}/like_content`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'token': 'ihgfedcba987654321'
            },
            body: JSON.stringify(paramsObj)
        });
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        var likeButton = document.querySelector(`#likeButton${content_id}`);
        likeButton.innerHTML = '';
        if (type === 'POST') {
            const jobResponse = await fetch(`${BASEURL}/job/${job_id}`, {
                method: 'GET',
                headers: {
                    'token': 'ihgfedcba987654321'
                }
            });

            if (!jobResponse.ok) {
                throw new Error(`Erro na requisição: ${jobResponse.statusText}`);
            }

            const jobData = await jobResponse.json();
            const likeIcon = document.createElement('i');
            likeIcon.id = `likeButton${jobData.job.job_id}`;
            likeIcon.className = jobData.job.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart';
            likeIcon.addEventListener('click', () => likeContent(user_id, jobData.job.job_id, jobData.job.type));

            const likesCount = document.createElement('span');
            likesCount.id = `likes${jobData.job.job_id}`;
            likesCount.className = 'ms-1 fst-italic text-muted fw-bold fs-6';
            likesCount.setAttribute('data-bs-toggle', 'modal');
            likesCount.setAttribute('data-bs-target', '#likesModal');
            likesCount.title = 'Likes';
            likesCount.role = 'button';
            likesCount.addEventListener('click', () => fillModalLikes(jobData.job.job_id, 'POST'));
            likesCount.textContent = jobData.job.job_likes;


            likeButton.appendChild(likeIcon);
            likeButton.appendChild(likesCount);
        }






        //     const likeIcon = document.createElement('i');
        //     likeIcon.id = `likeButton${response2.reply.reply_id}`;
        //     likeIcon.className = response2.reply.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart';
        //     likeIcon.addEventListener('click', () => likeContent(user_id, response2.reply.reply_id, response2.reply.type));

        //     const likesCount = document.createElement('span');
        //     likesCount.id = `likes${response2.reply.reply_id}`;
        //     likesCount.className = 'ms-1 fst-italic text-muted fw-bold fs-6';
        //     likesCount.setAttribute('data-bs-toggle', 'modal')
        //     likesCount.setAttribute('data-bs-target', '#likesModal')
        //     likesCount.title = 'Likes';
        //     likesCount.role = 'button';
        //     likesCount.addEventListener('click', () => fillModalLikes(response2.reply.reply_id, 'REPLY'))


        //     likeButton.appendChild(likeIcon);
        //     likeButton.appendChild(likesCount);


    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}

function autoGrow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight) + "px";
    element.style.maxWidth = "5px";
    element.style.maxWidth = (element.scrollHeight) + "px";
}

function textSlice() {
    let maxLength = 522;
    let jobsList = document.querySelectorAll(".user-post-text");
    jobsList.forEach(container => {
        let jobText = container.querySelector(".job-text");
        let originalText = jobText.textContent.trim();

        if (originalText.length > maxLength) {
            let truncatedText = originalText.slice(0, maxLength) + "...";
            jobText.textContent = truncatedText;
        }
    });
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