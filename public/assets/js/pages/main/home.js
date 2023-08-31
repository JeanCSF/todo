let Posts = [];
var currentPage = 1;
var isLoading = false;
var hasMoreData = true;
var mainContainer = document.querySelector("#postContainer");

document.addEventListener("DOMContentLoaded", function () {
    loadPosts(currentPage);
});

window.addEventListener('scroll', debounce(onScroll, 500));

function onScroll() {
    if (hasMoreData && !isLoading && window.scrollY + window.innerHeight >= document.body.scrollHeight - 100) {
        loadMorePosts(currentPage);
    }
}

function createUserOptionsDropdown(response) {
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

async function loadMorePosts(page) {
    if (isLoading || !hasMoreData) {
        return;
    }
    isLoading = true;
    page++;
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
                currentPage = page;
            });
        }
    } catch (error) {
        console.error("Erro na requisição: ", error);
    }
    isLoading = false;
}

[document.querySelector("#header_job_name"), document.querySelector("#header_job_desc"), document.querySelector("#privacy_select")].forEach(item => {
    item.addEventListener("focus", event => {
        document.querySelector("#privacy_select").removeAttribute("hidden")
    })
});

document.querySelector("#privacy_select").addEventListener("focusout", event => {
    setTimeout(() => {
        document.querySelector("#privacy_select").setAttribute("hidden", true)
    }, 500)
});
