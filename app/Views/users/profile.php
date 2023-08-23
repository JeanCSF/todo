<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>
<style>
    :root {
        --maingrey: rgb(247, 247, 247);
        --hovergrey: rgb(231, 231, 231);
        --primary: #0D6EFD;
        --white: #F2F2F2;
        --black: #212121;
        --transparent: rgba(0, 0, 0, 0);
        --link-text: 1.5rem;
    }

    .profile-img {
        margin-block: .3rem;
        position: absolute;
        top: 40%;
    }

    .banner {
        background-color: lightsteelblue;
        position: relative;
        min-height: 200px;
        z-index: -1;
    }

    .tabs {
        margin-top: 20%;
        border-bottom: 3px solid var(--hovergrey);
    }

    .tabs .active {
        border-bottom: 5px solid var(--primary);
        border-radius: 25%;
    }

    @media screen and (max-width: 768px) {
        .banner {
            min-height: 128px;
        }

        .profile-img img {
            max-width: 128px;
            max-height: 128px;
            display: flex;
        }

        .tabs {
            margin-top: 43%;
        }
    }
</style>
<div id="headerContainer" class="row mt-1">
</div>
<div class="tabs row">
    <div class="text-center d-flex justify-content-evenly m-0 p-0">
        <a href="javascript:void(0)" class="nav-link fw-bold" id="tasksTab" onclick="tasksTab(1, profile_user)">Tarefas</a>
        <a href="javascript:void(0)" class="nav-link fw-bold" id="repliesTab" onclick="repliesTab(1, profile_user_id)">Respostas</a>
        <a href="javascript:void(0)" class="nav-link fw-bold" id="likesTab" onclick="likesTab(1, profile_user_id)">Curtidas</a>
    </div>
</div>

<div id="postsContainer" class="row mt-2 profile-posts">

</div>
<div id="loadMore" class="row mt-2">
</div>
<div class="bottom-space"> </div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script defer src="<?= base_url('assets/js/pages/users/profile.js') ?>"></script>
<script>
    var profile_user = '<?= $user ?>';
    var profile_user_id = '<?= $user_id ?>';
</script>
<?= $this->endSection() ?>