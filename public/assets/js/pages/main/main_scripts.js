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

function createLikeElement(like) {
    const container = document.createElement('div');
    container.className = 'd-flex justify-content-between my-2';
    container.id = `like_${like.user}`;

    const profileLink = document.createElement('a');
    profileLink.href = `${BASEURL}/user/${like.user}`;
    profileLink.className = 'nav-link';

    const profilePic = document.createElement('img');
    profilePic.className = 'rounded-circle me-3';
    profilePic.height = 48;
    profilePic.width = 48;
    profilePic.alt = 'Profile pic';
    profilePic.src = !like.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profiles_pics/${like.user}/${like.profile_pic}`;

    const profileName = document.createElement('span');
    profileName.className = 'fw-bold';
    profileName.textContent = like.name;

    const timestamp = document.createElement('span');
    timestamp.className = 'text-muted fst-italic p-3';
    timestamp.style.fontSize = '10px';
    timestamp.textContent = like.datetime_liked;
    timestamp.title = like.full_datetime_liked

    profileLink.appendChild(profilePic);
    profileLink.appendChild(profileName);

    container.appendChild(profileLink);
    container.appendChild(timestamp);

    return container;
}

function createVisitElement(visit) {
    const container = document.createElement('div');
    container.className = 'd-flex justify-content-between my-2';
    container.id = `profileView${visit.view_id}_${visit.user}`;

    const profileLink = document.createElement('a');
    profileLink.href = `${BASEURL}/user/${visit.user}`;
    profileLink.className = 'nav-link';

    const profilePic = document.createElement('img');
    profilePic.className = 'rounded-circle me-3';
    profilePic.height = 48;
    profilePic.width = 48;
    profilePic.alt = 'Profile pic';
    profilePic.src = !visit.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profiles_pics/${visit.user}/${visit.profile_pic}`;

    const profileName = document.createElement('span');
    profileName.className = 'fw-bold';
    profileName.textContent = visit.name;

    const timestamp = document.createElement('span');
    timestamp.className = 'text-muted fst-italic p-3';
    timestamp.style.fontSize = '10px';
    timestamp.textContent = visit.datetime_visited;
    timestamp.title = visit.full_datetime_visited;

    profileLink.appendChild(profilePic);
    profileLink.appendChild(profileName);

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

function commentPage(comment_id) {
    window.location.href = BASEURL + '/reply/' + comment_id;
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
