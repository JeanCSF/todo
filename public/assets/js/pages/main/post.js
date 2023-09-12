var mainContainer = document.querySelector("#postContainer");
var commentsContainer = document.querySelector("#commentsContainer");
var newComment = document.querySelector("#newComment");
var comment = document.querySelector('#post_comment');
var frmComment = document.querySelector('#frmComment');
mainContainer.innerHTML = '';
commentsContainer.innerHTML = '';
newComment.innerHTML = '';

frmComment.addEventListener('submit', function (e) {
    commentContent(session_user_id, job_id, comment.value, 'POST');
    e.preventDefault();
    comment.value = '';

});

document.addEventListener("DOMContentLoaded", function () {
    loadPost(job_id);
});

async function loadPost(job_id) {
    mainContainer.innerHTML = '';
    const response = await fetch(`${BASEURL}/api/job/show/${job_id}`, {
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
        const Post = Data.job;
        const Comments = Data.job_comments;
        const postElement = createPostElement(Post, 'POST');
        mainContainer.appendChild(postElement);
        Comments.forEach(reply => {
            const repliesElement = createPostElement(reply, 'REPLY');
            commentsContainer.appendChild(repliesElement);
        });

    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}


