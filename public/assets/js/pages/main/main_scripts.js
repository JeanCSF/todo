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
        } else if (type == 'home') {
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

function createUserOptionsDropdown(response, type) {
    const dropdownDiv = createElements('div', {
        class: 'dropdown'
    });

    if (session_user_id === response.user_id) {
        const dropdownToggle = createElements('button', {
            class: 'bg-transparent border-0',
            type: 'button',
            'data-bs-toggle': 'dropdown',
            'aria-expanded': 'false'
        });
        dropdownToggle.innerHTML = '<i class="fa fa-ellipsis"></i>'

        const dropdownMenu = createElements('ul', {
            class: 'dropdown-menu'
        });

        const item1 = document.createElement('li');
        const linkItem1 = createElements('a', {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#privacyModal',
            class: 'dropdown-item'
        });
        linkItem1.addEventListener('click', () => fillModalPrivacy(response.job_id));
        linkItem1.innerHTML = `Privacidade ${response.job.job_privacy == 1 ? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>'}`;
        item1.appendChild(linkItem1);
        dropdownMenu.appendChild(item1);

        if (!response.job_finished) {
            const item2 = document.createElement('li');
            const linkItem2 = createElements('a', {
                class: 'dropdown-item',
                href: `${BASEURL}/todocontroller/jobdone/${response.job_id}`,
                role: 'finish',
                title: 'Finalizar Tarefa',
            });
            linkItem2.innerHTML = 'Finalizar <i class="fa fa-crosshairs text-success"></i>';
            item2.appendChild(linkItem2);
            dropdownMenu.appendChild(item2);

            const item3 = document.createElement('li');
            const linkItem3 = createElements('a', {
                class: 'dropdown-item',
                'data-bs-toggle': 'modal',
                'data-bs-target': '#taskModal',
                title: 'Editar Tarefa',
                role: 'edit',
            });
            item3.addEventListener('click', () => fillModalEdit(response.job_id, response.job_title, response.job))
            linkItem3.innerHTML = 'Editar <i class="fa fa-pencil text-primary"></i>';
            item3.appendChild(linkItem3)
            dropdownMenu.appendChild(item3);

        }

        const item4 = document.createElement('li');
        const linkItem4 = createElements('a', {
            class: 'dropdown-item',
            'data-bs-toggle': 'modal',
            'data-bs-target': '#deleteModal',
            title: 'Excluír Tarefa',
            role: 'delete',
        });
        linkItem4.addEventListener('click', () => fillModalDelete(response.job_id, 'home'));
        linkItem4.innerHTML = 'Excluír <i class="fa fa-trash text-danger"></i>';
        item4.appendChild(linkItem4);
        dropdownMenu.appendChild(item4);

        dropdownDiv.appendChild(dropdownToggle);
        dropdownDiv.appendChild(dropdownMenu);


    } else {
        const dropdownDiv = document.createElement('p');
        dropdownDiv.innerHTML = '<p> </p>';
    }



    return dropdownDiv;
}

function createPostElement(response, type) {
    if (type === 'POST') {
        const container = createElements('div', {
            class: 'post-container post',
            id: `post${response.job_id}`
        });


        const profilePicContainer = createElements('div', {
            class: 'user-img'
        });
        const imgLink = createElements('a', {
            href: `${BASEURL}/user/${response.user}`,
        });
        const profilePic = createElements('img', {
            height: 48,
            width: 48,
            src: !response.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profiles_pics/${response.user}/${response.profile_pic}`,
            alt: 'Profile Pic'
        });
        imgLink.appendChild(profilePic);
        profilePicContainer.appendChild(imgLink);

        const userInfo = createElements('div', {
            class: 'user-info'
        });
        const profileLink = createElements('a', {
            href: `${BASEURL}/user/${response.user}`,
            class: 'user-name',
        });
        profileLink.innerHTML = `${response.name} &#8226;`;
        const userName = createElements('span', {
            class: 'text-muted fst-italic'
        });
        userName.textContent = `@${response.user}`;
        profileLink.appendChild(userName);
        userInfo.appendChild(profileLink);


        const dropdownSpan = document.createElement('span');
        const dropdown = createUserOptionsDropdown(response, 'POST');
        dropdownSpan.appendChild(dropdown);
        userInfo.appendChild(dropdownSpan);


        const userPostText = createElements('div', {
            class: 'user-post-text'
        });
        const jobTitle = createElements('span', {
            id: 'jobTitle',
            class: 'fst-italic text-center d-block fs-5 job-title',
            style: `${!response.job_finished ? "" : "text-decoration: line-through;"}`
        });
        jobTitle.addEventListener('click', () => postPage(response.job_id))
        jobTitle.textContent = response.job_title;
        const jobTextContent = createElements('span', {
            id: 'jobTextContent',
            class: 'job-text'
        });
        jobTextContent.addEventListener('click', () => postPage(response.job_id))
        jobTextContent.innerHTML = response.job;
        userPostText.appendChild(jobTitle);
        userPostText.appendChild(jobTextContent);


        const userPostFooter = createElements('div', {
            class: 'user-post-footer fst-italic text-muted mt-3'
        });
        const jobCreated = document.createElement('p');
        jobCreated.textContent = response.job_created
        const jobFinished = document.createElement('p');
        jobFinished.innerHTML = !response.job_finished ? "" : response.job_finished + " <i class='fa fa-check-double'></i>"
        userPostFooter.appendChild(jobCreated);
        userPostFooter.appendChild(jobFinished);


        const postActions = createElements('div', {
            class: 'post-actions',
            id: `postActions_${response.job_id}`
        });
        const likeButton = createElements('a', {
            id: `likeButton${response.job_id}`,
            href: 'javascript:void(0)',
            role: 'button',
        });
        const likeIcon = createElements('i', {
            id: `likeIcon${response.job_id}`,
            class: response.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart',
            'onclick': `likeContent(${session_user_id}, ${response.job_id}, '${response.type}')`
        });
        likeButton.appendChild(likeIcon);
        const likesCount = createElements('span', {
            id: `likesCount${response.job_id}`,
            class: 'ms-1 fst-italic text-muted fw-bold fs-6',
            'data-bs-toggle': 'modal',
            'data-bs-target': '#likesModal',
            title: 'Likes',
            role: 'button'
        });
        likesCount.addEventListener('click', () => fillModalLikes(response.job_id, response.type));
        likesCount.textContent = response.job_likes;
        likeButton.appendChild(likesCount);
        const commentButton = createElements('a', {
            id: `commentButton${response.job_id}`,
            href: 'javascript:void(0)',
            role: 'button'
        });
        commentButton.addEventListener('click', () => postPage(response.job_id))
        const commentIcon = createElements('i', {
            class: 'fa-regular fa-comment'
        });
        commentButton.appendChild(commentIcon);
        const commentsCount = createElements('span', {
            class: 'ms-1 fst-italic text-muted fw-bold fs-6'
        });
        commentsCount.textContent = response.job_num_comments;
        commentButton.appendChild(commentsCount)
        const shareButton = createElements('a', {
            href: 'javascript:void(0)',
            role: 'button',
            'data-bs-toggle': 'modal',
            'data-bs-target': '#comingSoonModal',
            title: 'Compartilhar',
        });
        const shareIcon = createElements('i', {
            class: 'fa fa-arrow-up-from-bracket'
        });
        shareButton.appendChild(shareIcon);
        const shareCounts = createElements('span', {
            class: 'ms-1 fst-italic text-muted'
        });
        shareCounts.textContent = ' ';
        shareButton.appendChild(shareCounts);
        postActions.appendChild(likeButton);
        postActions.appendChild(commentButton);
        postActions.appendChild(shareButton);


        container.appendChild(profilePicContainer);
        container.appendChild(userInfo);
        container.appendChild(userPostText);
        container.appendChild(userPostFooter);
        container.appendChild(postActions);

        return container;
    }
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

async function likeContent(user_id, content_id, type_content) {
    const paramsObj = {
        user_id,
        content_id,
        type_content
    };
    try {
        const response = await fetch(`${BASEURL}/like_content`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'token': 'ihgfedcba987654321',
            },
            body: JSON.stringify(paramsObj),
        });
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }
        if (response.ok && type_content === 'POST') {
            const jobResponse = await fetch(`${BASEURL}/job/${content_id}`, {
                method: 'GET',
                headers: {
                    'token': 'ihgfedcba987654321'
                }
            });

            if (!jobResponse.ok) {
                throw new Error(`Erro na requisição: ${jobResponse.statusText}`);
            }

            const jobData = await jobResponse.json();

            const likeIcon = document.querySelector(`#likeIcon${jobData.job.job_id}`)
            if (!jobData.job.user_liked) {
                likeIcon.classList.remove('fa');
                likeIcon.classList.add('fa-regular');
            } else {
                likeIcon.classList.remove('fa-regular');
                likeIcon.classList.add('fa');
            }

            const likesCount = document.querySelector(`#likesCount${jobData.job.job_id}`);
            likesCount.textContent = ''
            likesCount.textContent = jobData.job.job_likes;

        }
        if (response.ok && type_content === 'REPLY') {
            const jobResponse = await fetch(`${BASEURL}/comment/${content_id}`, {
                method: 'GET',
                headers: {
                    'token': 'ihgfedcba987654321'
                }
            });

            if (!jobResponse.ok) {
                throw new Error(`Erro na requisição: ${jobResponse.statusText}`);
            }

            const jobData = await jobResponse.json();

            const likeIcon = document.querySelector(`#likeIcon${jobData.job.job_id}`)
            if (!jobData.job.user_liked) {
                likeIcon.classList.remove('fa');
                likeIcon.classList.add('fa-regular');
            } else {
                likeIcon.classList.remove('fa-regular');
                likeIcon.classList.add('fa');
            }

            const likesCount = document.querySelector(`#likesCount${jobData.job.job_id}`);
            likesCount.textContent = ''
            likesCount.textContent = jobData.job.job_likes;

        }

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