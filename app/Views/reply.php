<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('section') ?>

<article id="replyContainer" class="row mt-2">
</article>
<hr>
<div id="reply_comment_div" class="header-post mb-2">
    <img class="rounded-circle border border-light-subtle float-start" height="48" width="48" src="<?= !empty($_SESSION['IMG']) ? base_url('../../assets/img/profiles_pics/' . $_SESSION['USER'] . '/' . $_SESSION['IMG']) : base_url('/assets/logo.png') ?>" alt="Profile pic">
    <form method="post" class="d-flex justify-content-between align-items-center" id="frmReply">
        <div class="ms-3 text-center">
            <textarea oninput="auto_grow(this)" id="reply_comment" name="reply_comment" placeholder="Responder Comentário" required autocomplete="off" style="width: 600px;"></textarea>
        </div>
        <div class="pb-4">
            <button type="submit" class="btn btn-primary fw-bolder">Responder</button>
        </div>
    </form>
</div>
<hr>
<article id="newReply" class="row">
</article>
<article id="repliesContainer" class="row">
</article>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script defer src="<?= base_url('assets/pages/main/reply.js') ?>"></script>
<script>
    const BASEURL = '<?= base_url() ?>';
    var session_user_id = '<?= $_SESSION['USER_ID'] ?>';
    var session_profile_pic = '<?= $_SESSION['IMG'] ?>';
    var session_user = '<?= $_SESSION['USER'] ?>'
    var reply_id = '<?= $reply_id ?>';
    var mainContainer = document.querySelector("#replyContainer");
    var commentsContainer = document.querySelector("#repliesContainer");
    var newComment = document.querySelector("#newReply");
    var comment = document.querySelector('#reply_comment');
    var frmComment = document.querySelector('#frmReply');
    mainContainer.innerHTML = '';
    commentsContainer.innerHTML = '';
    newComment.innerHTML = '';
</script>
<?= $this->endSection() ?>