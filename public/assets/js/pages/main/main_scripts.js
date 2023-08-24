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
function fillModalLikes(content_id, type) {
    var likesContainer = document.querySelector("#likesModalContainer");
    let Likes = [];
    likesContainer.innerHTML = '';
    $.ajax({
        url: BASEURL + '/show_likes',
        type: "GET",
        data: {
            content_id: content_id,
            type: type
        },
        headers: {
            'token': 'ihgfedcba987654321'
        },
        error: function (xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    }).done(function (response) {
        Likes = response;
        Likes.forEach(function (like) {
            likesContainer.innerHTML += `
                    <div class="d-flex justify-content-between align-center my-2" id="like${like.like_id}">
                        <a href="${BASEURL + '/user/' + like.user}" class="nav-link">
                            <img class="rounded-circle me-3" height="48" width="48" src="${!like.profile_pic ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + like.user + '/' + like.profile_pic}" alt="Profile pic">
                            <span class="fw-bold">${like.name}</span>
                        </a>
                        <span class="text-muted fst-italic p-3" style="font-size: 10px;">${like.datetime_liked}</span>
                    </div>
                `;

        })
    })
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