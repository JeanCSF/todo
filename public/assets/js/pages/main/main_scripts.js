var msg = document.querySelector('#msgInfo');
var alerta = document.querySelector('#alerta');
const toastElement = document.querySelector('#basicToast');

const btnSubmitTaskModal = document.querySelector('#btnSubmitTaskModal');
const taskModalLabel = document.querySelector('#taskModalLabel');

const btnReplySubmit = document.querySelector('#btnReply');

const newPost = document.querySelector("#newPost");
var jobModalTitle = document.querySelector('#job_name');
var jobModalText = document.querySelector('#job_desc');
var jobModalPrivacy = document.querySelector('#job_privacy_select');
var frmPostModal = document.querySelector('#frmPostModal');
var formReplyModal = document.querySelector('#formReplyModal');

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

formReplyModal.addEventListener('submit', (e) => {
    const replyModalText = document.querySelector('#reply_content');
    const replyToEditId = btnReplySubmit.getAttribute('data-reply-id');
    console.log(replyToEditId);
    editReply(session_user_id, replyToEditId, replyModalText.value);
    e.preventDefault();
    document.querySelector('#closeReplyModal').click();
});

document.addEventListener("DOMContentLoaded", () => {
    deleteContent();
    loadChats();
});

const createElement = (elementName, attributes) => {
    const element = document.createElement(elementName);
    const attributesAsArray = Object.entries(attributes);

    attributesAsArray.forEach(([key, value]) => element.setAttribute(key, value));

    return element;
}

function toggleChat() {
    const chat = document.querySelector('#chats');
    const chatsContainer = document.querySelector('#chatsContainer');
    const icon = chatsContainer.querySelector('#toggleChatIcon');

    icon.classList.toggle('fa-angles-up');
    icon.classList.toggle('fa-angles-down');

    chat.classList.toggle('active-chat');

    chatsContainer.classList.toggle('position-absolute');
    chatsContainer.classList.toggle('pe-4');
}

function createUserOptionsDropdown(response, type) {
    if (type === 'POST') {
        const dropdownDiv = createElement('div', {
            class: 'dropdown'
        });

        if (session_user_id == response.user_id) {
            const dropdownToggle = createElement('button', {
                class: 'bg-transparent border-0',
                type: 'button',
                'data-bs-toggle': 'dropdown',
                'aria-expanded': 'false',
                title: 'Opções da Tarefa',
                role: 'menu'
            });
            dropdownToggle.innerHTML = '<i class="fa fa-ellipsis"></i>'

            const dropdownMenu = createElement('ul', {
                class: 'dropdown-menu'
            });

            const item1 = document.createElement('li');
            const linkItem1 = createElement('a', {
                'data-bs-toggle': 'modal',
                'data-bs-target': '#privacyModal',
                class: 'dropdown-item',
                title: 'Mudar Privacidade',
                role: 'menuitem',
                href: 'javascript:void(0)'
            });
            linkItem1.addEventListener('click', () => fillModalPrivacy(response.job_id));
            linkItem1.innerHTML = `Privacidade ${response.job.job_privacy == 1 ? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>'}`;
            item1.appendChild(linkItem1);
            dropdownMenu.appendChild(item1);

            if (!response.job_finished) {
                const item2 = document.createElement('li');
                const linkItem2 = createElement('a', {
                    class: 'dropdown-item',
                    href: 'javascript:void(0)',
                    role: 'menuitem',
                    title: 'Finalizar Tarefa',
                });
                item2.addEventListener('click', () => finishJob(response.job_id));
                linkItem2.innerHTML = 'Finalizar <i class="fa fa-crosshairs text-success"></i>';
                item2.appendChild(linkItem2);
                dropdownMenu.appendChild(item2);

                const item3 = document.createElement('li');
                const linkItem3 = createElement('a', {
                    class: 'dropdown-item',
                    'data-bs-toggle': 'modal',
                    'data-bs-target': '#taskModal',
                    title: 'Editar Tarefa',
                    role: 'menuitem',
                    href: 'javascript:void(0)'
                });
                item3.addEventListener('click', () => fillModalEdit(response.job_id, response.job_title, response.job));
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
                role: 'menuitem',
                href: 'javascript:void(0)'
            });
            linkItem4.addEventListener('click', () => fillModalDelete(response.job_id, 'POST'));
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

        if (session_user_id == response.user_id) {
            const dropdownToggle = createElement('button', {
                class: 'bg-transparent border-0',
                type: 'button',
                'data-bs-toggle': 'dropdown',
                'aria-expanded': 'false',
                title: 'Opções da Resposta',
                role: 'menu'
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
                title: 'Editar Resposta',
                role: 'menuitem',
                href: 'javascript:void(0)'
            });
            item3.addEventListener('click', () => fillModalEditReply(response.reply_id, response.reply))
            linkItem3.innerHTML = 'Editar <i class="fa fa-pencil text-primary"></i>';
            item3.appendChild(linkItem3)
            dropdownMenu.appendChild(item3);


            const item4 = document.createElement('li');
            const linkItem4 = createElement('a', {
                class: 'dropdown-item',
                'data-bs-toggle': 'modal',
                'data-bs-target': '#deleteModal',
                title: 'Excluír Resposta',
                role: 'menuitem',
                href: 'javascript:void(0)'
            });
            linkItem4.addEventListener('click', () => fillModalDelete(response.reply_id, 'REPLY'));
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

        const imgLink = createElement('a', {
            href: `${BASEURL}/user/${response.user}`,
        });
        const imgContainer = createElement('div', {
            class: 'user-img'
        });
        const profilePic = createElement('img', {
            height: 48,
            width: 48,
            'data-src': !response.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profile_imgs/${response.user}/${response.profile_pic}`,
            alt: 'Profile Pic',
            class: 'lazyload'
        });
        imgContainer.appendChild(profilePic);
        imgLink.appendChild(imgContainer);

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
            id: `commentsCount${response.job_id}`,
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


        container.appendChild(imgLink);
        container.appendChild(userInfo);
        container.appendChild(userPostText);
        container.appendChild(userPostFooter);
        container.appendChild(postActions);

        return container;
    }

    if (type === 'REPLY') {
        const container = createElement('div', {
            class: 'post-container post',
            id: `replyContent${response.reply_id}`
        });


        const profilePicContainer = createElement('div', {
            class: ''
        });
        const imgLink = createElement('a', {
            href: `${BASEURL}/user/${response.user}`,
        });
        const imgContainer = createElement('div', {
            class: 'user-img'
        });
        const profilePic = createElement('img', {
            height: 48,
            width: 48,
            'data-src': !response.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profile_imgs/${response.user}/${response.profile_pic}`,
            alt: 'Profile Pic',
            class: 'lazyload'
        });
        imgContainer.appendChild(profilePic);
        imgLink.appendChild(imgContainer);
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
            id: `replyCommentsCount${response.reply_id}`,
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
        src: !data.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profile_imgs/${data.user}/${data.profile_pic}`
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

async function loadChats() {
    const chatsContainer = document.querySelector("#chats");
    chatsContainer.innerHTML = '';

    const response = await fetch(`${BASEURL}/messages/get_chats/${session_user_id}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    });
    try {
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const Chats = await response.json();

        Chats.forEach(chat => {
            const chatsContent = createElement('div', {
                class: 'px-2 py-1 position-relative mb-3'
            });

            const chatFragment = createElement('a', {
                class: 'd-flex text-decoration-none text-reset',
                href: `${BASEURL}/messages/chat/${chat.user}?chatId=${chat.chat_id}`,
            });

            const chatProfilePic = createElement('img', {
                width: 52,
                height: 52,
                class: 'rounded-circle float-start',
                src: !chat.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profile_imgs/${chat.user}/${chat.profile_pic}`,
                alt: 'Profile pic'
            });
            chatFragment.appendChild(chatProfilePic);

            const chatTargetUser = createElement('p', {
                class: 'fst-italic fw-bold ms-4'
            });
            chatTargetUser.textContent = chat.user;
            chatFragment.appendChild(chatTargetUser);

            const messageTimeElapsed = createElement('p', {
                class: 'fst-italic text-muted position-absolute end-0 small me-1',
                title: chat.full_datetime_last_message
            });
            messageTimeElapsed.textContent = chat.time_elapsed_last_message;
            chatFragment.appendChild(messageTimeElapsed);

            const messageText = createElement('p', {
                class: 'position-absolute w-100 ms-4 ps-5 mt-4 chat-tab-message'
            });
            messageText.textContent = chat.last_message_user_id == session_user_id ? `Você: ${chat.last_message}` : chat.last_message;
            chatFragment.appendChild(messageText);

            chatsContent.appendChild(chatFragment);

            chatsContainer.appendChild(chatsContent);
        })
    } catch (error) {
        console.error("Erro na requisição", error)
    }
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
        const responseData = await response.json();
        if (!response.ok) {
            alerta.classList.add('alert-danger');
            msg.textContent = responseData.error;
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        alerta.classList.add('alert-success');
        msg.textContent = responseData.message;
        var toast = new bootstrap.Toast(toastElement);
        toast.show();
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

        const responseData = await response.json();
        if (!response.ok) {
            alerta.classList.add('alert-danger');
            msg.textContent = responseData.error;
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }
        alerta.classList.add('alert-success');
        msg.textContent = responseData.message;
        var toast = new bootstrap.Toast(toastElement);
        toast.show();

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
        newJobDesc.innerHTML = updatedJob.job;

    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}

async function finishJob(job_id) {
    try {
        const response = await fetch(`${BASEURL}/api/job/finish/${job_id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'token': 'ihgfedcba987654321',
            }
        });

        const responseData = await response.json();
        if (!response.ok) {
            alerta.classList.add('alert-danger');
            msg.textContent = responseData.error;
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }
        alerta.classList.add('alert-success');
        msg.textContent = responseData.message;
        var toast = new bootstrap.Toast(toastElement);
        toast.show();
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
        const mainJobsContainer = document.querySelector('#postContainer');
        const oldJobContainer = document.querySelector(`#post${job_id}`);
        const newJobContainer = createPostElement(updatedJob, 'POST');
        mainJobsContainer.replaceChild(newJobContainer, oldJobContainer);

    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}

async function editReply(user_id, reply_id, reply) {
    const paramsObj = {
        user_id: user_id,
        reply_id: reply_id,
        reply: reply,
    }
    console.log(paramsObj);
    try {
        const response = await fetch(`${BASEURL}/api/job/update_reply/${reply_id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'token': 'ihgfedcba987654321',
            },
            body: JSON.stringify(paramsObj),
        });

        const responseData = await response.json();
        if (!response.ok) {
            alerta.classList.add('alert-danger');
            msg.textContent = responseData.error;
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }
        alerta.classList.add('alert-success');
        msg.textContent = responseData.message;
        var toast = new bootstrap.Toast(toastElement);
        toast.show();

        const replyResponse = await fetch(`${BASEURL}/api/job/reply/${reply_id}`, {
            method: 'GET',
            headers: {
                'token': 'ihgfedcba987654321'
            }
        });

        if (!replyResponse.ok) {
            throw new Error(`Erro na requisição: ${replyResponse.statusText}`);
        }

        const replyData = await replyResponse.json();
        const updatedReply = replyData.reply;
        const replyContainer = document.querySelector(`#replyContent${reply_id}`);
        const newReply = replyContainer.querySelector('.job-text');

        newReply.innerHTML = updatedReply.reply;


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
    jobModalText.setAttribute('value', desc.replace(/<br\s*\/?>/gi, ''));
    jobModalText.textContent = desc.replace(/<br\s*\/?>/gi, '');

}

function fillModalEditReply(id, reply) {
    btnReplySubmit.setAttribute('data-reply-id', id);
    document.getElementById("btnReply").setAttribute('value', 'Atualizar');
    document.getElementById("reply_content").setAttribute('value', reply.replace(/<br\s*\/?>/gi, ''));
    document.getElementById("reply_content").textContent = reply.replace(/<br\s*\/?>/gi, '');


}

function fillModalDelete(id, type) {
    if (type === 'REPLY') {
        document.getElementById("modalTitle").textContent = "Deletar Resposta";
        document.getElementById("bodyMsg").textContent = "Deseja realmente deletar esta resposta?";
        document.getElementById("btnDeletar").setAttribute('data-delete', id);
        document.getElementById("btnDeletar").setAttribute('data-type', type);
    }
    document.getElementById("modalTitle").textContent = "Deletar Tarefa";
    document.getElementById("bodyMsg").textContent = "Deseja realmente deletar esta tarefa?";
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
            const jobInfo = jobData.job;
            const newComment = jobData.job_comments[0];
            const commentsCount = document.querySelector(`#commentsCount${content_id}`);
            const newJobCommentContainer = document.querySelector("#newComment");

            const newJobCommentElement = createPostElement(newComment, 'REPLY');
            newJobCommentContainer.appendChild(newJobCommentElement);
            commentsCount.textContent = jobInfo.job_num_comments;

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

function deleteContent() {
    document.querySelector("#btnDeletar").addEventListener('click', async () => {
        var id = document.getElementById("btnDeletar").getAttribute('data-delete', id);
        var type = document.getElementById("btnDeletar").getAttribute('data-type', type);
        var toast = new bootstrap.Toast(toastElement);
        var currentUrl = window.location.href;
        try {
            const response = await fetch(`${BASEURL}/api/job/delete/${id}?type=${type}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'token': 'ihgfedcba987654321',
                }
            });
            const responseData = await response.json();
            if (!response.ok) {
                alerta.classList.add('alert-danger');
                msg.textContent = responseData.error;
                throw new Error(`Erro na requisição: ${response.statusText}`);
            }

            if (type === 'POST' && currentUrl != `${BASEURL}/post/${id}`) {
                alerta.classList.add('alert-success');
                msg.textContent = responseData.message;
                document.querySelector(`#post${id}`).remove();
                document.querySelector('#closeDeleteModal').click();
                toast.show();
            } else if (type === 'POST' && currentUrl == `${BASEURL}/post/${id}`) {
                alerta.classList.add('alert-success');
                msg.textContent = responseData.message;
                document.querySelector('#closeDeleteModal').click();
                setTimeout(() => {
                    window.history.go(-1);
                }, 300);
                toast.show();
            } else {
                alerta.classList.add('alert-success');
                msg.textContent = responseData.message;
                document.querySelector(`#jobReplyContent${id}`).remove();
                document.querySelector('#closeDeleteModal').click();
                toast.show();

            }
        } catch (error) {
            console.error("Erro na requisição:", error);
        }
    });
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
