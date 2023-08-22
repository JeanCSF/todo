<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>

<div class="container-fluid p-4 col-lg-8 offset-lg-2">
    <form action="<?= base_url('main/contact') ?>" method="post">
        <input type="text" name="contactName" id="contactName" class="form-control mb-3" placeholder="Nome" required />
        <input type="text" name="contactEmail" id="contactEmail" class="form-control mb-3" placeholder="Email" required />
        <textarea name="contactText" id="contactText" cols="30" rows="10" class="form-control" placeholder="Mensagem"></textarea>
        <div class="mt-2 text-center d-flex justify-content-between">
            <a href="javascript:history.go(-1)" class="btn btn-secondary">Cancelar</a>
            <input class="btn btn-primary" type="submit" value="Enviar">
        </div>
    </form>
</div>
<div class="bottom-space"> </div>

<?= $this->endSection() ?>