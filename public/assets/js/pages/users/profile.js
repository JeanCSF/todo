var currentPage = 1;
var isLoading = false;
var hasMoreData = true;
var headerContainer = document.querySelector("#headerContainer");
var postsContainer = document.querySelector("#postsContainer");
var loadMoreButton = document.querySelector("#loadMore");
var profileViewsContainer = document.querySelector("#profileViewsModalContainer");

document.addEventListener("DOMContentLoaded", function () {
    headerContent(currentPage, profile_user);

    if (session_user_id != profile_user_id) {
        saveVisitForProfile(profile_user_id, session_user_id)
    }

});

async function saveVisitForProfile(profile_user_id, session_user_id) {


    const paramsObj = {
        profile_user_id,
        session_user_id
    };

    const response = await fetch(`${BASEURL}/api/user/save_visit`, {
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
    } catch (error) {
        console.error("Erro na requisição", error)
    }
    // $.ajax({
    //     url: BASEURL + '/api/user/save_visit',
    //     type: "POST",
    //     data: {
    //         user_id: profile_user_id,
    //         visitor_id: session_user_id
    //     },
    //     headers: {
    //         'token': 'ihgfedcba987654321'
    //     },
    //     error: function (xhr, status, error) {
    //         console.error("Erro na requisição:", error);
    //     }
    // });
}

async function fillModalVisits(profile_id) {
    var visitsContainer = document.querySelector("#profileViewsModalContainer");
    visitsContainer.innerHTML = '';

    const paramsObj = {
        profile_id
    };

    const response = await fetch(`${BASEURL}/api/user/visits`, {
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

        const Visits = await response.json();

        Visits.forEach(visit => {
            const visitElement = createVisitElement(visit);
            visitsContainer.appendChild(visitElement);
        })
    } catch (error) {
        console.error("Erro na requisição", error)
    }
}

async function headerContent(page, user) {
    headerContainer.innerHTML = '';

    const response = await fetch(`${BASEURL}/api/user/show/${user}?page=${page}`, {
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
        const User = Data.user_info;

        const container = createElement('div', {
            class: 'banner'
        });

        const profilePicContainer = createElement('div', {
            class: 'profile-img'
        });
        const profilePic = createElement('img', {
            class: 'img fluid rounded-circle',
            width: 200,
            height: 200,
            src: !User.profile_pic ? `${BASEURL}/assets/avatar.webp` : `${BASEURL}/assets/img/profile_imgs/${User.user}/${User.profile_pic}`,
            alt: 'Profile pic'
        });
        const profileUserContainer = createElement('div', {
            class: 'd-flex justify-content-between align-items-center'
        });

        const profileUser = createElement('p', {
            class: 'fst-italic fw-bold text-muted me-5 mt-3'
        });
        profileUser.textContent = `@${User.user}`;

        const profileMessage = createElement('a', {
            href: `${BASEURL}/messages/chat/${User.user}`,
            class: `ms-3 btn btn-sm btn-outline-primary rounded-5 ${User.user === session_user ? 'd-none' : 'd-block'}`
        });
        profileMessage.innerHTML = 'Mensagem &#x2709;'

        profileUserContainer.appendChild(profileUser);
        profileUserContainer.appendChild(profileMessage);

        profilePicContainer.appendChild(profilePic);
        profilePicContainer.appendChild(profileUserContainer);

        if (session_user_id === User.user_id) {
            const visitsButtonDiv = createElement('div', {
                class: 'position-relative'
            })
            const visitsButton = createElement('button', {
                class: 'btn border-0 position-absolute z-3',
                'data-bs-toggle': 'modal',
                'data-bs-target': '#profileViewsModal',
                title: 'Visitas',
                role: 'button',
                'onclick': fillModalVisits(User.user_id)
            });
            const visitsIcon = createElement('i', {
                class: 'fa fa-eye'
            });
            visitsButton.appendChild(visitsIcon);
            visitsButtonDiv.appendChild(visitsButton);

            headerContainer.appendChild(visitsButtonDiv);
        }

        container.appendChild(profilePicContainer);
        headerContainer.appendChild(container);

    } catch (error) {
        console.error("Erro na requisição: ", error);
    }
}

async function tasksTab(page, user, more = false) {
    if (isLoading || !hasMoreData) {
        return;
    }
    document.querySelector("#likesTab").classList.remove("active");
    document.querySelector("#repliesTab").classList.remove("active");
    document.querySelector("#tasksTab").classList.add("active");

    window.addEventListener('scroll', debounce(onScroll, 500));

    function onScroll() {
        if (hasMoreData && !isLoading && window.scrollY + window.innerHeight >= document.body.scrollHeight - 100) {
            tasksTab(currentPage, user, true);
        }
    }

    if (more) {
        isLoading = true;
        page++;
        try {
            const response = await fetch(`${BASEURL}/api/user/show/${user}?page=${page}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'token': 'ihgfedcba987654321'
                },
            });

            if (!response.ok) {
                throw new Error(`Erro na requisição? ${response.statusText}`);
            }

            const Data = await response.json();
            const Posts = Data.user_jobs;
            if (!Posts) {
                hasMoreData = false;
            } else {
                Posts.forEach(post => {
                    const postElement = createPostElement(post, 'POST');
                    postsContainer.appendChild(postElement);
                    textSlice();
                });
                currentPage = page;
            }
        } catch (error) {
            console.error("Erro na requisição: ", error);
        }
        isLoading = false;
    } else {
        postsContainer.innerHTML = '';
        try {
            const response = await fetch(`${BASEURL}/api/user/show/${user}?page=${page}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'token': 'ihgfedcba987654321'
                },
            });

            if (!response.ok) {
                throw new Error(`Erro na requisição? ${response.statusText}`);
            }

            const Data = await response.json();
            const Posts = Data.user_jobs;
            if (!Posts) {
                hasMoreData = false;
                postsContainer.innerHTML = `<p class='text-center'>${Data.user_info.user} não publicou nada ainda!</p>`;
            } else {
                Posts.forEach(post => {
                    const postElement = createPostElement(post, 'POST');
                    postsContainer.appendChild(postElement);
                    textSlice();
                });
            }
        } catch (error) {
            console.error("Erro na requisição: ", error);
        }
        isLoading = false;
    }
}

async function repliesTab(page, user_id, more = false) {
    if (isLoading || !hasMoreData) {
        return;
    }
    document.querySelector("#likesTab").classList.remove("active");
    document.querySelector("#tasksTab").classList.remove("active");
    document.querySelector("#repliesTab").classList.add("active");

    window.addEventListener('scroll', debounce(onScroll, 500));

    function onScroll() {
        if (hasMoreData && !isLoading && window.scrollY + window.innerHeight >= document.body.scrollHeight - 100) {
            repliesTab(currentPage, user_id, true);
        }
    }

    if (more) {
        isLoading = true;
        page++;
        try {
            const response = await fetch(`${BASEURL}/api/user/replies/${user_id}?page=${page}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'token': 'ihgfedcba987654321'
                },
            });

            if (!response.ok) {
                throw new Error(`Erro na requisição? ${response.statusText}`);
            }

            const Data = await response.json();
            const Replies = Data.replies;
            if (!Replies) {
                hasMoreData = false;
            } else {
                Replies.forEach(reply => {
                    const postElement = createPostElement(reply, 'REPLY');
                    postsContainer.appendChild(postElement);
                    textSlice();
                });
                currentPage = page;
            }
        } catch (error) {
            console.error("Erro na requisição: ", error);
        }
        isLoading = false;
    } else {
        postsContainer.innerHTML = '';
        try {
            const response = await fetch(`${BASEURL}/api/user/replies/${user_id}?page=${page}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'token': 'ihgfedcba987654321'
                },
            });

            if (!response.ok) {
                throw new Error(`Erro na requisição? ${response.statusText}`);
            }

            const Data = await response.json();
            const Replies = Data.replies;
            if (!Replies) {
                hasMoreData = false;
                postsContainer.innerHTML = `<p class='text-center'>${Replies.user} não respondeu ninguém ainda!</p>`;
            } else {
                Replies.forEach(reply => {
                    const postElement = createPostElement(reply, 'REPLY');
                    postsContainer.appendChild(postElement);
                    textSlice();
                });
            }
        } catch (error) {
            console.error("Erro na requisição: ", error);
        }
        isLoading = false;
    }
}

async function likesTab(page, user_id, more = false) {
    document.querySelector("#tasksTab").classList.remove("active");
    document.querySelector("#repliesTab").classList.remove("active");
    document.querySelector("#likesTab").classList.add("active");
    if (isLoading || !hasMoreData) {
        return;
    }

    window.addEventListener('scroll', debounce(onScroll, 500));

    function onScroll() {
        if (hasMoreData && !isLoading && window.scrollY + window.innerHeight >= document.body.scrollHeight - 100) {
            likesTab(currentPage, user_id, true);
        }
    }

    if (more) {
        isLoading = true;
        page++;
        try {
            const response = await fetch(`${BASEURL}/api/user/liked/${user_id}?page=${page}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'token': 'ihgfedcba987654321'
                },
            });

            if (!response.ok) {
                throw new Error(`Erro na requisição? ${response.statusText}`);
            }

            const Data = await response.json();
            const Likes = Data.likes;
            if (!Likes) {
                hasMoreData = false;
            } else {
                Likes.forEach(like => {
                    if (like.type === 'REPLY') {
                        const postElement = createPostElement(like, 'REPLY');
                        postsContainer.appendChild(postElement);
                        textSlice();
                    } else {
                        const postElement = createPostElement(like, 'POST');
                        postsContainer.appendChild(postElement);
                        textSlice();
                    }
                });
                currentPage = page;
            }
        } catch (error) {
            console.error("Erro na requisição: ", error);
        }
        isLoading = false;
    } else {
        postsContainer.innerHTML = '';
        try {
            const response = await fetch(`${BASEURL}/api/user/liked/${user_id}?page=${page}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'token': 'ihgfedcba987654321'
                },
            });

            if (!response.ok) {
                throw new Error(`Erro na requisição? ${response.statusText}`);
            }

            const Data = await response.json();
            const Likes = Data.likes;
            if (!Likes) {
                hasMoreData = false;
                postsContainer.innerHTML = `<p class='text-center'>${Replies.user} não curtiu nada ainda!</p>`;
            } else {
                Likes.forEach(like => {
                    if (like.type === 'REPLY') {
                        const postElement = createPostElement(like, 'REPLY');
                        postsContainer.appendChild(postElement);
                        textSlice();
                    } else {
                        const postElement = createPostElement(like, 'POST');
                        postsContainer.appendChild(postElement);
                        textSlice();
                    }
                });
            }
        } catch (error) {
            console.error("Erro na requisição: ", error);
        }
        isLoading = false;
    }
}

function loadContent() {
    const path = window.location.hash.substring(1);
    postsContainer = document.querySelector("#postsContainer");

    switch (path) {
        case 'tasks':
            tasksTab(currentPage, profile_user);
            break;
        case 'replies':
            repliesTab(currentPage, profile_user_id);
            break;
        case 'likes':
            likesTab(currentPage, profile_user_id);
            break;
        default:
            tasksTab(currentPage, profile_user);
    }
}

window.addEventListener('load', loadContent);

window.addEventListener('hashchange', loadContent);