<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('section') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/chat.css') ?>">
<main class="d-flex flex-column justify-content-between">
    <div class="messages-container">
        <div class="message">
            <p>L</p>
        </div>
        <div class="message">
            <p>Lorem ipsum dolor, sit amet </p>
        </div>
        <div class="message my-message">
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. </p>
        </div>
        <div class="message">
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Tempore rerum reiciendis voluptate eligendi. </p>
        </div>
        <div class="message">
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Tempore rerum reiciendis voluptate eligendi. Sequi aliquid repellat aut in at praesentium ipsa explicabo eveniet error animi eaque, labore ab deleniti cupiditate.</p>
        </div>
    </div>
    <form class="row" id="frmMessage">
        <div class="d-flex">
            <textarea class="msg-input form-control" placeholder="Mensagem" name="message" id="message" rows="1"></textarea>
            <button type="submit" class="msg-submit"><i class="fa fa-location-arrow"></i></button>
        </div>
    </form>
</main>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script defer src="<?= base_url('assets/js/pages/main/chat.js') ?>"></script>
<?= $this->endSection() ?>