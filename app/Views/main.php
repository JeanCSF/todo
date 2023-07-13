<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>

<div class="container-fluid">
    <h3 class="text-center">Tome Nota</h3>
    <h5 class="text-center">Anote suas tarefas!</h5>
    <div class="text-center mt-5">
        <p>Se já possui conta faça login <a href="login">aqui.</a></p> 
        <p>Se ainda não tem conta, crie uma <a href="signup">aqui.</a></p>
    </div>
</div>

<?= $this->endSection() ?>