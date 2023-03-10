<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('conteudo') ?>
<style>
</style>
<div class="container mt-1">
    <div class="row">
        <div class="col-lg-8 col-md-auto col-sm-auto offset-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item <?= isset($userData) ? '' : 'active' ?>" <?= isset($userData) ? '' : 'aria-current="page"' ?>>
                        <?= isset($userData) ? "<a  href='" . base_url('/') . "' >Home</a>" : "Home" ?></li>

                    <?php if (isset($userData)) : ?>
                        <li class='breadcrumb-item active' aria-current='page'>Perfil - <?= $_SESSION['NAME'] ?> </li>
                    <?php endif; ?>
                </ol>
            </nav>
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="https://placehold.co/400x600" class="img-fluid rounded-start" alt="Profile pic">
                        <p class="card-text"><small class="text-muted text-nowrap">Data do cadastro: <?= date('d/m/Y', strtotime($userData->DATETIME_CREATED)) ?></small></p>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $userData->NAME ?></h5>
                            <ul class="list-group">
                                <li class="col list-group-item">
                                    <ul class="list-group list-group-horizontal row">
                                        <?=var_dump($userTasks)?>
                                        <li style="list-style-type: none;"><strong>Usuário: </strong><?= $userData->USER ?></li>
                                        <li class="mt-1" style="list-style-type: none;"><strong>Email: </strong><?= $userData->EMAIL ?></li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="mt-5 card-footer bg-transparent">
                                <?php if ($_SESSION['SU'] == 1) : ?>
                                    <p class="card-text"><small class="text-muted">E-mail confirmado? <?= ($userData->ACTIVATION == 0) ? 'Não' : 'Sim' ?></small></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>