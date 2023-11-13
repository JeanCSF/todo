<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('section') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/chat.css') ?>">
<?php if (!isset($user)) : ?>
    <style>
        main {
            margin: 0;
            display: flex;
            place-items: center;
            min-width: 320px;
            min-height: 100vh;
            justify-content: center;
        }
    </style>
<?php endif; ?>
<main class="<?= isset($user) ? 'd-flex flex-column justify-content-between' : '' ?>">
    <?php if (isset($user)) : ?>
        <div class="messages-container" id="messagesContainer">
        </div>
        <form class="row" id="frmMessage">
            <div class="d-flex">
                <textarea class="msg-input form-control" placeholder="Mensagem" name="message" id="message" rows="1"></textarea>
                <button type="submit" class="msg-submit" onClick=""><i class="fa fa-location-arrow"></i></button>
            </div>
        </form>
    <?php else : ?>
        <p>Selecione um dos contatos ao lado para visualizar as mensagens</p>
    <?php endif; ?>
</main>

<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    var chat_user_name = '<?= isset($user) ? $user : '' ?>'
</script>
<script defer src="<?= base_url('assets/js/pages/main/chat.js') ?>"></script>
<?= $this->endSection() ?>