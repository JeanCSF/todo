const hoverPostElements = document.querySelectorAll('.post');
const hoverLinkElements = document.querySelectorAll('.dropdown-item');

const btnSubmitTaskModal = document.querySelector("#btnSubmitTaskModal");
const taskModalLabel = document.querySelector("#taskModalLabel");

const newPost = document.querySelector("#newPost");
var jobModalTitle = document.querySelector('#job_name');
var jobModalText = document.querySelector('#job_desc');
var jobModalPrivacy = document.querySelector('#job_privacy_select');
var frmPostModal = document.querySelector('#frmPostModal');

frmPostModal.addEventListener('submit', function (e) {
    const dataType = btnSubmitTaskModal.getAttribute('data-type');
    const jobToEditId = btnSubmitTaskModal.getAttribute('data-job-id');
    if (dataType === 'new') {
        createJob(session_user_id, jobModalTitle.value, jobModalText.value, jobModalPrivacy.value);
        e.preventDefault();
        jobModalTitle.value = '';
        jobModalText.value = '';
        document.querySelector('#closeTaskModal').click();
    } else {
        editJob(session_user_id, jobToEditId, jobModalTitle.value, jobModalText.value, jobModalPrivacy.value);
        e.preventDefault();
        document.querySelector('#closeTaskModal').click();
    }


});

document.querySelector("#themeToggle").addEventListener("click", () => {
    if (document.documentElement.getAttribute('data-bs-theme') == 'dark') {
        document.documentElement.setAttribute('data-bs-theme', 'light')
        localStorage.setItem('theme', 'light');

        hoverPostElements.forEach(function (element) {
            element.classList.remove('dark-hover');
            element.classList.add('light-hover');
        });

        hoverLinkElements.forEach(function (element) {
            element.classList.remove('dark-hover');
            element.classList.add('light-hover');
        });
    }
    else {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        localStorage.setItem('theme', 'dark');

        hoverPostElements.forEach(function (element) {
            element.classList.remove('light-hover');
            element.classList.add('dark-hover');
        });

        hoverLinkElements.forEach(function (element) {
            element.classList.remove('light-hover');
            element.classList.add('dark-hover');
        });
    }
});

document.querySelector("#themeToggleButton").addEventListener("click", () => {
    if (document.documentElement.getAttribute('data-bs-theme') == 'dark') {
        document.documentElement.setAttribute('data-bs-theme', 'light')
        localStorage.setItem('theme', 'light');

        hoverPostElements.forEach(function (element) {
            element.classList.remove('dark-hover');
            element.classList.add('light-hover');
        });

        hoverLinkElements.forEach(function (element) {
            element.classList.remove('dark-hover');
            element.classList.add('light-hover');
        });
    }
    else {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        localStorage.setItem('theme', 'dark');

        hoverPostElements.forEach(function (element) {
            element.classList.remove('light-hover');
            element.classList.add('dark-hover');
        });

        hoverLinkElements.forEach(function (element) {
            element.classList.remove('light-hover');
            element.classList.add('dark-hover');
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const theme = localStorage.getItem('theme');
    if (theme === 'dark') {
        hoverPostElements.forEach(function (e) {
            e.classList.add('dark-hover');
        });

        hoverLinkElements.forEach(function (element) {
            element.classList.add('dark-hover');
        });
    } else {
        hoverPostElements.forEach(function (e) {
            e.classList.add('light-hover');
        });

        hoverLinkElements.forEach(function (element) {
            element.classList.add('light-hover');
        });
    }

    $('#btnDeletar').on('click', function () {
        var id = document.getElementById("btnDeletar").getAttribute('data-delete', id);
        var type = document.getElementById("btnDeletar").getAttribute('data-type', type);
        if (type == "null") {
            $.ajax({
                url: BASEURL + '/api/job/reply_delete/' + id,
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
                url: BASEURL + '/api/job/job_delete/' + id,
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
                url: BASEURL + '/api/job/job_delete/' + id,
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

const createElement = (elementName, attributes) => {
    const element = document.createElement(elementName);
    const attributesAsArray = Object.entries(attributes);

    attributesAsArray.forEach(([key, value]) => element.setAttribute(key, value));

    return element;
}

function createUserOptionsDropdown(response, type) {
    if (type === 'POST') {
        const dropdownDiv = createElement('div', {
            class: 'dropdown'
        });

        if (session_user_id === response.user_id) {
            const dropdownToggle = createElement('button', {
                class: 'bg-transparent border-0',
                type: 'button',
                'data-bs-toggle': 'dropdown',
                'aria-expanded': 'false'
            });
            dropdownToggle.innerHTML = '<i class="fa fa-ellipsis"></i>'

            const dropdownMenu = createElement('ul', {
                class: 'dropdown-menu'
            });

            const item1 = document.createElement('li');
            const linkItem1 = createElement('a', {
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
                const linkItem2 = createElement('a', {
                    class: 'dropdown-item',
                    href: `${BASEURL}/todocontroller/jobdone/${response.job_id}`,
                    role: 'finish',
                    title: 'Finalizar Tarefa',
                });
                linkItem2.innerHTML = 'Finalizar <i class="fa fa-crosshairs text-success"></i>';
                item2.appendChild(linkItem2);
                dropdownMenu.appendChild(item2);

                const item3 = document.createElement('li');
                const linkItem3 = createElement('a', {
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
            const linkItem4 = createElement('a', {
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

    if (type === 'REPLY') {
        const dropdownDiv = createElement('div', {
            class: 'dropdown'
        });

        if (session_user_id === response.user_id) {
            const dropdownToggle = createElement('button', {
                class: 'bg-transparent border-0',
                type: 'button',
                'data-bs-toggle': 'dropdown',
                'aria-expanded': 'false'
            });
            dropdownToggle.innerHTML = '<i class="fa fa-ellipsis"></i>'

            const dropdownMenu = createElement('ul', {
                class: 'dropdown-menu'
            });

            const item3 = document.createElement('li');
            const linkItem3 = createElement('a', {
                class: 'dropdown-item',
                'data-bs-toggle': 'modal',
                'data-bs-target': '#replyModal',
                title: 'Editar Tarefa',
                role: 'edit',
            });
            item3.addEventListener('click', () => fillModalEditReply(response.comment_id, response.comment))
            linkItem3.innerHTML = 'Editar <i class="fa fa-pencil text-primary"></i>';
            item3.appendChild(linkItem3)
            dropdownMenu.appendChild(item3);


            const item4 = document.createElement('li');
            const linkItem4 = createElement('a', {
                class: 'dropdown-item',
                'data-bs-toggle': 'modal',
                'data-bs-target': '#deleteModal',
                title: 'Excluír Tarefa',
                role: 'delete',
            });
            linkItem4.addEventListener('click', () => fillModalDeleteReply(response.comment_id, 'REPLY'));
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

}

function createPostElement(response, type) {
    if (type === 'POST') {
        const container = createElement('div', {
            class: 'post-container post',
            id: `post${response.job_id}`
        });


        const profilePicContainer = createElement('div', {
            class: 'user-img'
        });
        const imgLink = createElement('a', {
            href: `${BASEURL}/user/${response.user}`,
        });
        const profilePic = createElement('img', {
            height: 48,
            width: 48,
            src: !response.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profiles_pics/${response.user}/${response.profile_pic}`,
            alt: 'Profile Pic'
        });
        imgLink.appendChild(profilePic);
        profilePicContainer.appendChild(imgLink);

        const userInfo = createElement('div', {
            class: 'user-info'
        });
        const profileLink = createElement('a', {
            href: `${BASEURL}/user/${response.user}`,
            class: 'user-name',
        });
        profileLink.innerHTML = `${response.name} &#8226;`;
        const userName = createElement('span', {
            class: 'text-muted fst-italic'
        });
        userName.textContent = `@${response.user}`;
        profileLink.appendChild(userName);
        userInfo.appendChild(profileLink);


        const dropdownSpan = document.createElement('span');
        const dropdown = createUserOptionsDropdown(response, 'POST');
        dropdownSpan.appendChild(dropdown);
        userInfo.appendChild(dropdownSpan);


        const userPostText = createElement('div', {
            class: 'user-post-text'
        });
        const jobTitle = createElement('span', {
            class: 'fst-italic text-center d-block fs-5 job-title',
            style: `${!response.job_finished ? "" : "text-decoration: line-through;"}`
        });
        jobTitle.addEventListener('click', () => postPage(response.job_id))
        jobTitle.textContent = response.job_title;
        const jobTextContent = createElement('span', {
            class: 'job-text'
        });
        jobTextContent.addEventListener('click', () => postPage(response.job_id))
        jobTextContent.innerHTML = response.job;
        userPostText.appendChild(jobTitle);
        userPostText.appendChild(jobTextContent);


        const userPostFooter = createElement('div', {
            class: 'user-post-footer fst-italic text-muted mt-3'
        });
        const jobCreated = document.createElement('p');
        jobCreated.textContent = response.job_created
        const jobFinished = document.createElement('p');
        jobFinished.innerHTML = !response.job_finished ? "" : response.job_finished + " <i class='fa fa-check-double'></i>"
        userPostFooter.appendChild(jobCreated);
        userPostFooter.appendChild(jobFinished);


        const postActions = createElement('div', {
            class: 'post-actions',
            id: `postActions_${response.job_id}`
        });
        const likeButton = createElement('a', {
            id: `likeButton${response.job_id}`,
            href: 'javascript:void(0)',
            role: 'button',
        });
        const likeIcon = createElement('i', {
            id: `likeIcon${response.job_id}`,
            class: response.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart',
            'onclick': `likeContent(${session_user_id}, ${response.job_id}, '${response.type}')`
        });
        likeButton.appendChild(likeIcon);
        const likesCount = createElement('span', {
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
        const commentButton = createElement('a', {
            id: `commentButton${response.job_id}`,
            href: 'javascript:void(0)',
            role: 'button'
        });
        commentButton.addEventListener('click', () => postPage(response.job_id))
        const commentIcon = createElement('i', {
            class: 'fa-regular fa-comment'
        });
        commentButton.appendChild(commentIcon);
        const commentsCount = createElement('span', {
            class: 'ms-1 fst-italic text-muted fw-bold fs-6'
        });
        commentsCount.textContent = response.job_num_comments;
        commentButton.appendChild(commentsCount)
        const shareButton = createElement('a', {
            href: 'javascript:void(0)',
            role: 'button',
            'data-bs-toggle': 'modal',
            'data-bs-target': '#comingSoonModal',
            title: 'Compartilhar',
        });
        const shareIcon = createElement('i', {
            class: 'fa fa-arrow-up-from-bracket'
        });
        shareButton.appendChild(shareIcon);
        const shareCounts = createElement('span', {
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

    if (type === 'REPLY') {
        const container = createElement('div', {
            class: 'post-container post',
            id: `jobReplyContent${response.reply_id}`
        });


        const profilePicContainer = createElement('div', {
            class: 'user-img'
        });
        const imgLink = createElement('a', {
            href: `${BASEURL}/user/${response.user}`,
        });
        const profilePic = createElement('img', {
            height: 48,
            width: 48,
            src: !response.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profiles_pics/${response.user}/${response.profile_pic}`,
            alt: 'Profile Pic'
        });
        imgLink.appendChild(profilePic);
        profilePicContainer.appendChild(imgLink);

        const userInfo = createElement('div', {
            class: 'user-info'
        });
        const profileLink = createElement('a', {
            href: `${BASEURL}/user/${response.user}`,
            class: 'user-name',
        });
        profileLink.innerHTML = `${response.name} &#8226;`;
        const userName = createElement('span', {
            class: 'text-muted fst-italic'
        });
        userName.textContent = `@${response.user}`;
        profileLink.appendChild(userName);
        userInfo.appendChild(profileLink);


        const dropdownSpan = document.createElement('span');
        const dropdown = createUserOptionsDropdown(response, 'REPLY');
        dropdownSpan.appendChild(dropdown);
        userInfo.appendChild(dropdownSpan);


        const userReplyText = createElement('div', {
            class: 'user-post-text'
        });

        const jobReplyContent = createElement('span', {
            class: 'job-text'
        });
        jobReplyContent.addEventListener('click', () => commentPage(response.reply_id))
        jobReplyContent.innerHTML = response.reply;
        userReplyText.appendChild(jobReplyContent);


        const userPostFooter = createElement('div', {
            class: 'user-post-footer fst-italic text-muted mt-3'
        });
        const replyCreated = document.createElement('p');
        replyCreated.textContent = response.datetime_replied
        userPostFooter.appendChild(replyCreated);


        const replyActions = createElement('div', {
            class: 'post-actions',
            id: `replyActions_${response.reply_id}`
        });
        const likeButton = createElement('a', {
            id: `likeReplyButton${response.reply_id}`,
            href: 'javascript:void(0)',
            role: 'button',
        });
        const likeIcon = createElement('i', {
            id: `likeReplyIcon${response.reply_id}`,
            class: response.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart',
            'onclick': `likeContent(${session_user_id}, ${response.reply_id}, '${response.type}')`
        });
        likeButton.appendChild(likeIcon);
        const likesCount = createElement('span', {
            id: `replyLikesCount${response.reply_id}`,
            class: 'ms-1 fst-italic text-muted fw-bold fs-6',
            'data-bs-toggle': 'modal',
            'data-bs-target': '#likesModal',
            title: 'Likes',
            role: 'button'
        });
        likesCount.addEventListener('click', () => fillModalLikes(response.reply_id, response.type));
        likesCount.textContent = response.reply_likes;
        likeButton.appendChild(likesCount);
        const commentButton = createElement('a', {
            id: `commentReplyButton${response.reply_id}`,
            href: 'javascript:void(0)',
            role: 'button'
        });
        commentButton.addEventListener('click', () => commentPage(response.reply_id))
        const commentIcon = createElement('i', {
            class: 'fa-regular fa-comment'
        });
        commentButton.appendChild(commentIcon);
        const commentsCount = createElement('span', {
            class: 'ms-1 fst-italic text-muted fw-bold fs-6'
        });
        commentsCount.textContent = response.reply_num_comments;
        commentButton.appendChild(commentsCount)
        const shareButton = createElement('a', {
            href: 'javascript:void(0)',
            role: 'button',
            'data-bs-toggle': 'modal',
            'data-bs-target': '#comingSoonModal',
            title: 'Compartilhar',
        });
        const shareIcon = createElement('i', {
            class: 'fa fa-arrow-up-from-bracket'
        });
        shareButton.appendChild(shareIcon);
        const shareCounts = createElement('span', {
            class: 'ms-1 fst-italic text-muted'
        });
        shareCounts.textContent = ' ';
        shareButton.appendChild(shareCounts);
        replyActions.appendChild(likeButton);
        replyActions.appendChild(commentButton);
        replyActions.appendChild(shareButton);


        container.appendChild(profilePicContainer);
        container.appendChild(userInfo);
        container.appendChild(userReplyText);
        container.appendChild(userPostFooter);
        container.appendChild(replyActions);

        return container;
    }
}

function createProfileLink(data) {
    const profileLink = createElement('a', {
        href: `${BASEURL}/user/${data.user}`,
        class: 'nav-link'
    });

    const profilePic = createProfilePic(data);
    const profileName = createElement('span', {
        class: 'fw-bold'
    });
    profileName.textContent = data.name;

    profileLink.appendChild(profilePic);
    profileLink.appendChild(profileName);

    return profileLink;
}

function createProfilePic(data) {
    return createElement('img', {
        class: 'rounded-circle me-3',
        height: 48,
        width: 48,
        alt: 'Profile Pic',
        src: !data.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profiles_pics/${data.user}/${data.profile_pic}`
    });
}

function createTimestampElement(data) {
    return createElement('span', {
        class: 'text-muted fst-italic p-3',
        style: 'font-size: 10px;',
        title: data.full_datetime_liked
    }, data.datetime_liked);
}

function createLikeElement(like) {
    const container = createElement('div', {
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

    const container = createElement('div', {
        class: 'd-flex justify-content-between my-2',
        id: `like_${visit.user}`
    });

    const profileLink = createProfileLink(visit);
    const timestamp = createTimestampElement(visit);

    container.appendChild(profileLink);
    container.appendChild(timestamp);

    return container;
}

async function createJob(user_id, job_title, job, job_privacy) {
    const paramsObj = {
        user_id: user_id,
        job_title: job_title,
        job: job,
        job_privacy: job_privacy,
    }
    try {
        const response = await fetch(`${BASEURL}/api/job/create`, {
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

        const jobResponse = await fetch(`${BASEURL}/api/user/show/${session_user}?page=${1}`, {
            method: 'GET',
            headers: {
                'token': 'ihgfedcba987654321'
            }
        });

        if (!jobResponse.ok) {
            throw new Error(`Erro na requisição: ${jobResponse.statusText}`);
        }

        const jobData = await jobResponse.json();
        const newJob = jobData.user_jobs[0];
        const newJobContainer = document.querySelector("#newPost");
        const newJobElement = createPostElement(newJob, 'POST');
        newJobContainer.appendChild(newJobElement);

    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}

async function editJob(user_id, job_id, job_title, job, job_privacy) {
    const paramsObj = {
        user_id: user_id,
        job_title: job_title,
        job: job,
        job_privacy: job_privacy,
    }
    try {
        const response = await fetch(`${BASEURL}/api/job/update/${job_id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'token': 'ihgfedcba987654321',
            },
            body: JSON.stringify(paramsObj),
        });
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const jobResponse = await fetch(`${BASEURL}/api/job/show/${job_id}`, {
            method: 'GET',
            headers: {
                'token': 'ihgfedcba987654321'
            }
        });

        if (!jobResponse.ok) {
            throw new Error(`Erro na requisição: ${jobResponse.statusText}`);
        }

        const jobData = await jobResponse.json();
        const updatedJob = jobData.job;
        const jobContainer = document.querySelector(`#post${job_id}`);
        const newJobTittle = jobContainer.querySelector('.job-title');
        const newJobDesc = jobContainer.querySelector('.job-text'); 
        newJobTittle.textContent = updatedJob.job_title;
        newJobDesc.textContent = updatedJob.job;

    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}

async function fillModalLikes(content_id, type) {
    var likesContainer = document.querySelector("#likesModalContainer");
    likesContainer.innerHTML = '';
    const paramsObj = {
        content_id,
        type
    };

    const response = await fetch(`${BASEURL}/api/job/likes`, {
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
    taskModalLabel.textContent = "Atualizar Tarefa";
    btnSubmitTaskModal.setAttribute('value', 'Atualizar');
    btnSubmitTaskModal.setAttribute('data-job-id', id);
    btnSubmitTaskModal.setAttribute('data-type', 'edit')
    jobModalTitle.setAttribute('value', job);
    jobModalText.setAttribute('value', desc);
    jobModalText.textContent =  desc;

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
    jobModalTitle.value = '';
    jobModalText.value = '';
    taskModalLabel.textContent = "Adicionar Tarefa";
    btnSubmitTaskModal.setAttribute('value', 'Gravar');
    btnSubmitTaskModal.setAttribute('data-type', 'new');
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
        const response = await fetch(`${BASEURL}/api/job/like`, {
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
        if (type_content === 'POST') {
            const jobResponse = await fetch(`${BASEURL}/api/job/show/${content_id}`, {
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

        } else if (type_content === 'REPLY') {
            const replyResponse = await fetch(`${BASEURL}/api/job/reply/${content_id}`, {
                method: 'GET',
                headers: {
                    'token': 'ihgfedcba987654321'
                }
            });

            if (!replyResponse.ok) {
                throw new Error(`Erro na requisição: ${replyResponse.statusText}`);
            }

            const replyData = await replyResponse.json();

            const likeIcon = document.querySelector(`#likeReplyIcon${replyData.reply.reply_id}`)
            if (!replyData.reply.user_liked) {
                likeIcon.classList.remove('fa');
                likeIcon.classList.add('fa-regular');
            } else {
                likeIcon.classList.remove('fa-regular');
                likeIcon.classList.add('fa');
            }

            const likesCount = document.querySelector(`#replyLikesCount${replyData.reply.reply_id}`);
            likesCount.textContent = ''
            likesCount.textContent = replyData.reply.reply_likes;

        }


    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}

async function commentContent(user_id, content_id, comment, type_content) {
    const paramsObj = {
        user_id: user_id,
        content_id: content_id,
        comment: comment,
        type_content: type_content
    }
    try {
        const response = await fetch(`${BASEURL}/api/job/comment`, {
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

        if (type_content === 'POST') {
            const jobResponse = await fetch(`${BASEURL}/api/job/show/${content_id}`, {
                method: 'GET',
                headers: {
                    'token': 'ihgfedcba987654321'
                }
            });

            if (!jobResponse.ok) {
                throw new Error(`Erro na requisição: ${jobResponse.statusText}`);
            }

            const jobData = await jobResponse.json();
            const newComment = jobData.job_comments[0];
            const newJobCommentContainer = document.querySelector("#newComment");
            const newJobCommentElement = createPostElement(newComment, 'REPLY');
            newJobCommentContainer.appendChild(newJobCommentElement);

        } else if (type_content === 'REPLY') {
            const replyResponse = await fetch(`${BASEURL}/api/job/reply/${content_id}`, {
                method: 'GET',
                headers: {
                    'token': 'ihgfedcba987654321'
                }
            });

            if (!replyResponse.ok) {
                throw new Error(`Erro na requisição: ${replyResponse.statusText}`);
            }

            const replyData = await replyResponse.json();
            const newComment = replyData.reply_comments[0];
            const newReplyCommentContainer = document.querySelector("#newReply");
            const newReplyCommentElement = createPostElement(newComment, 'REPLY');
            newReplyCommentContainer.appendChild(newReplyCommentElement);
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
