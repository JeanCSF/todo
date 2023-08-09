<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('section') ?>
<style>
    .reply-textarea {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        max-width: 180px !important;
    }
</style>
    <article id="postContainer" class="row mt-2">
    </article>
    <hr>
    <div id="post_comment_div" class="header-post mb-2">
        <img class="rounded-circle border border-light-subtle float-start" height="48" width="48" src="<?= !empty($_SESSION['IMG']) ? base_url('../../assets/img/profiles_pics/' . $_SESSION['USER'] . '/' . $_SESSION['IMG']) : base_url('/assets/logo.png') ?>" alt="Profile pic">
        <form method="post" class="d-flex justify-content-between align-items-center" id="frmComment">
            <div class="ms-3 text-center">
                <textarea oninput="auto_grow(this)" id="post_comment" name="post_comment" class="reply-textarea" placeholder="Comente esta tarefa" required autocomplete="off"></textarea>
            </div>
            <div class="pb-4">
                <button type="submit" class="btn btn-primary fw-bolder">Comentar</button>
            </div>
        </form>
    </div>
    <hr>
    <article id="newComment" class="row">
    </article>
    <article id="commentsContainer" class="row">
    </article>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script defer src="<?= base_url('assets/pages/main/post.js') ?>"></script>
<script>
    const BASEURL = '<?= base_url() ?>';
    var session_user_id = '<?= $_SESSION['USER_ID'] ?>';
    var session_profile_pic = '<?= $_SESSION['IMG'] ?>';
    var session_user = '<?= $_SESSION['USER'] ?>'
    var job_id = '<?= $job_id ?>';
    var mainContainer = document.querySelector("#postContainer");
    var commentsContainer = document.querySelector("#commentsContainer");
    var newComment = document.querySelector("#newComment");
    var comment = document.querySelector('#post_comment');
    var frmComment = document.querySelector('#frmComment');
    mainContainer.innerHTML = '';
    commentsContainer.innerHTML = '';
    newComment.innerHTML = '';
</script>
<?= $this->endSection() ?>