<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('conteudo') ?>
<!-- Login Modal -->
<!-- <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="loginModalLabel">Login</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div> -->
<!-- Login Modal -->


<div class="container-fluid pt-5 pb-5">
    <h3 class="text-center">Tome Nota</h3>
    <h5 class="text-center">Anote suas tarefas!</h5>
    <div class="text-center mt-5">
        <p>Se já possui conta faça login <a href="<?php echo site_url('userscontroller/login'); ?>">aqui.</a></p> 
        <p>Se ainda não tem conta, crie uma <a href="<?= site_url('userscontroller/signup'); ?>">aqui.</a></p>
    </div>
</div>

<?= $this->endSection() ?>