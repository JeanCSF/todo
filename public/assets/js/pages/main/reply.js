var mainContainer = document.querySelector("#replyContainer");
var commentsContainer = document.querySelector("#repliesContainer");
var newComment = document.querySelector("#newReply");
var comment = document.querySelector('#reply_comment');
var frmComment = document.querySelector('#frmReply');
mainContainer.innerHTML = '';
commentsContainer.innerHTML = '';
newComment.innerHTML = '';

frmComment.addEventListener('submit', function (e) {
    commentContent(session_user_id, reply_id, comment.value, 'REPLY');
    e.preventDefault();
    comment.value = '';

});

document.addEventListener("DOMContentLoaded", function () {
    loadReply(reply_id);
});

async function loadReply(reply_id) {
    mainContainer.innerHTML = '';
    const response = await fetch(`${BASEURL}/api/job/reply/${reply_id}`, {
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
        const Reply = Data.reply;
        const replyComments = Data.reply_comments;
        const postElement = createPostElement(Reply, 'REPLY');
        mainContainer.appendChild(postElement);
        replyComments.forEach(reply => {
            const repliesElement = createPostElement(reply, 'REPLY');
            commentsContainer.appendChild(repliesElement);
        });

    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}