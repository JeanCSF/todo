<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>
<style>
    .assn-text {
        font-family: 'Friz Quadrata Std', sans-serif;
        text-align: center;
        font-weight: bolder;
        font-style: italic;
    }
</style>
<div class="container-fluid">
        <h3 class="text-center fw-bolder">ASSN</h3>
        <p class="assn-text text-center fs-5">ANTI<br>SOCIAL<br>SOCIAL<br>NETWORK</p>
        <div class="text-center">
            <p>Se já possui conta faça login <a href="login">aqui.</a></p>
            <p>Se ainda não tem conta, crie uma <a href="signup">aqui.</a></p>
        </div>
</div>

<?= $this->endSection() ?>