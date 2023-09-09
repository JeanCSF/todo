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
        loadPosts(currentPage, true);
    }
}

async function loadPosts(page, more = false) {
    if (isLoading || !hasMoreData) {
        return;
    }
    if (more) {
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
            if (!Posts) {
                hasMoreData = false;
            } else {
                Posts.forEach(post => {
                    const postElement = createPostElement(post, 'POST');
                    mainContainer.appendChild(postElement);
                    textSlice();
                });
                currentPage = page;
            }
        } catch (error) {
            console.error("Erro na requisição: ", error);
        }
        isLoading = false;
    } else {
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
            if (!Posts) {
                hasMoreData = false;
            } else {
                Posts.forEach(post => {
                    const postElement = createPostElement(post, 'POST');
                    mainContainer.appendChild(postElement);
                    textSlice();
                });
            }
        } catch (error) {
            console.error("Erro na requisição: ", error);
        }
        isLoading = false;
    }
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
