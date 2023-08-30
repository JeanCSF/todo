let Posts = [];
var currentPage = 1;
var isLoading = false;
var hasMoreData = true;
var mainContainer = document.querySelector("#postContainer");

document.addEventListener("DOMContentLoaded", function () {
    loadPosts(currentPage);
});

$(window).scroll(debounce(function () {
    if (hasMoreData && !isLoading && $(window).scrollTop() + $(window).height() >= $(document).height() - 1000) {
        loadMorePosts(currentPage);
    }
}, 500));

function createUserOptionsDropdown(response) {
    const dropdownDiv = createElements('div', {
        class: 'dropdown'
    });

    if (session_user_id == response.job.user_id) {
        const dropdownToggle = createElements('button', {
            class: 'bg-transparent border-o',
            type: 'button',
            'data-bs-toggle': 'dropdown',
            'aria-expanded': 'false'
        });
        dropdownToggle.textContent = '<i class="fa fa-ellipsis"></i>'

        const dropdownMenu = createElements('ul', {
            class: 'dropdown-menu'
        });

        const item1 = document.createElement('li');
        const linkItem1 = createElements('a', {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#privacyModal',
            class: 'dropdown-item'
        });
        linkItem1.addEventListener('click', () => fillModalPrivacy(response.job.job_id));
        linkItem1.textContent = `Privacidade ${response.job.job_privacy == 1 ? '<i class="fa fa-earth-americas"></i>' : '<i class="fa fa-lock"></i>'}`;
        item1.appendChild(linkItem1);
        dropdownMenu.appendChild(item1);

        if (!response.job.job_finished) {
            const item2 = document.createElement('li');
            const linkItem2 = createElements('a', {
                class: 'dropdown-item',
                href: `${BASEURL}/todocontroller/jobdone/${response.job.job_id}`,
                role: 'finish',
                title: 'Finalizar Tarefa',
            });
            linkItem2.textContent = 'Finalizar <i class="fa fa-crosshairs text-success"></i>';
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
            linkItem3.addEventListener('click', () => fillModalEdit(`'${response.job.job_id}', '${response.job.job_title}', '${response.job.job}'`));
            linkItem3.textContent = 'Editar <i class="fa fa-pencil text-primary"></i>';
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
        linkItem4.addEventListener('click', () => fillModalDelete(`${response.job.job_id}, 1`));
        linkItem4.textContent = 'Excluír <i class="fa fa-trash text-danger"></i>';
        item4.appendChild(linkItem4);
        dropdownMenu.appendChild(item4);

        dropdownDiv.appendChild(dropdownToggle);
        dropdownDiv.appendChild(dropdownMenu);


    } else {
        const dropdownDiv = document.createElement('p');
        dropdownDiv.textContent = '<p> </p>';
    }



    return dropdownDiv;
}

function createPostElement(response) {
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
    const dropdown = createUserOptionsDropdown(response);
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
    const jobTextContent = createElements('span', {
        id: 'jobTextContent',
        class: 'job-text'
    });
    jobTextContent.textContent = response.job
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
        class: response.user_liked ? 'fa fa-heart' : 'fa-regular fa-heart'
    });
    likeIcon.addEventListener('click', () => likeContent(session_user_id, response.job_id, 'POST'));
    likeButton.appendChild(likeIcon);
    const likesCount = createElements('span', {
        id: `likes${response.job_id}`,
        class: 'ms-1 fst-italic text-muted fw-bold fs-6',
        'data-bs-toggle': 'modal',
        'data-bs-target': '#likesModal',
        title: 'Likes',
        role: 'button'
    });
    likesCount.addEventListener('click', () => fillModalLikes(response.job_id, 'POST'));
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

async function loadPosts(page) {
    if (isLoading || !hasMoreData) {
        return;
    }

    try {
        const response = await fetch(`${BASEURL}/all_jobs?page=${page}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'token': 'ihgfedcba987654321'
            },
        });

        if (!response.ok) {
            throw new Error(`Erro na requisição? ${response.statusText}`);
        }

        const Posts = await response.json();
        if (Posts.length === 0) {
            hasMoreData = false;
        } else {
            Posts.forEach(post => {
                const postElement = createPostElement(post);
                mainContainer.appendChild(postElement);
                textSlice();
            });
        }
    } catch (error) {
        console.error("Erro na requisição: ", error);
    }
    isLoading = false;
}

function loadMorePosts(page) {
    if (isLoading || !hasMoreData) {
        return;
    }
    isLoading = true;
    page++;
    $.ajax({
        url: BASEURL + '/all_jobs',
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
            Posts = response;
            Posts.forEach(function (post) {
                mainContainer.innerHTML += `
                        <div class="post-container post">
                            <div class="user-img">
                                <a href="${BASEURL + '/user/' + post.user}">
                                    <img height="48" width="48" src="${!post.profile_pic ? BASEURL + '/assets/avatar.webp' : BASEURL + '/assets/img/profiles_pics/' + post.user + '/' + post.profile_pic}" alt="Profile pic">
                                </a>
                            </div>
                            <div class="user-info">
                                <a href="${BASEURL + '/user/' + post.user}" class="user-name">${post.name} &#8226; <span class="text-muted fst-italic">@${post.user}</span></a>
                                <span>
                                    ${session_user_id == post.user_id ?
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
                                <span class="fst-italic text-center d-block fs-5 job-title" style="${!post.job_finished ? "" : "text-decoration: line-through;"}">${post.job_title}</span>
                                <span onclick="postPage(${post.job_id})" class="job-text">${post.job}</span>
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
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#comingSoonModal" title="Compartilhar" role="button"><i class="fa fa-arrow-up-from-bracket"></i><span class="ms-1 fst-italic text-muted"> </span></a>
                            </div>
                        </div>
                    `;
                textSlice();
                currentPage = page;
            });
        };
    });
    isLoading = false;
}

[document.querySelector("#header_job_name"), document.querySelector("#header_job_desc"), document.querySelector("#privacy_select")].forEach(item => {
    item.addEventListener("focus", event => {
        document.querySelector("#privacy_select").removeAttribute("hidden")
    })
})
document.querySelector("#privacy_select").addEventListener("focusout", event => {
    setTimeout(() => {
        document.querySelector("#privacy_select").setAttribute("hidden", true)
    }, 500)
})

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
                        <span id="likes${response.job.job_id}" class="ms-1 fst-italic text-muted fw-bold fs-6" data-bs-toggle="modal" data-bs-target="#likesModal" title="Likes" role="button" onclick="fillModalLikes(${response.job.job_id}, 'POST')">${response.job.job_likes}</span>
                `;
        });
    });
}