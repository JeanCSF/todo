<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('section') ?>
<div id="post_div" class="header-post mb-2" style="<?= isset($search) ? 'visibility:hidden;' : '' ?>" tabindex="0">
    <img class="mt-2 rounded-circle border border-light-subtle float-start" height="48" width="48" src="<?= !empty($_SESSION['IMG']) ? base_url('../../assets/img/profiles_pics/' . $_SESSION['USER'] . '/' . $_SESSION['IMG']) : base_url('/assets/logo.png') ?>" alt="Profile pic">
    <form action="<?= base_url('todocontroller/newjobsubmit') ?>" method="post" class="ms-5">
        <select name="privacy_select" id="privacy_select" class="ms-3 mt-3" title="Visualização" hidden>
            <option value="1">Todos &#xf57d;</option>
            <option value="0">Somente eu &#xf023;</option>
        </select>
        <br>
        <div class="mt-1 ms-3">
            <input type="text" id="header_job_name" name="header_job_name" placeholder="Tarefa" required autocomplete="off"><br>
            <textarea oninput="auto_grow(this)" class="ms-3" type="text" id="header_job_desc" name="header_job_desc" placeholder="Sobre a tarefa" required autocomplete="off"></textarea>
        </div>
        <div class="text-end mt-1">
            <button type="submit" class="btn btn-sm btn-primary">Publicar</button>
        </div>
    </form>
</div>
<article id="postContainer" class="row">
</article>
<div id="main"></div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script defer src="<?= base_url('assets/pages/main/home.js') ?>"></script>
<script>
    let Posts = [];
    var currentPage = 1;
    var isLoading = false;
    var hasMoreData = true;
    const BASEURL = '<?= base_url() ?>';
    var session_user_id = '<?= $_SESSION['USER_ID'] ?>';
    var mainContainer = document.querySelector("#postContainer");
</script>
<?= $this->endSection() ?>