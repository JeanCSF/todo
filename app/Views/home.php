<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('conteudo') ?>


<header class="ms-5 me-5 d-flex justify-content-between">
    <h3>
        <?php if (isset($done)) : ?>
            Concluídas
        <?php else : ?>
            Todas as tarefas
        <?php endif; ?>
    </h3>
    <form class="d-flex me-5" role="search">
        <input class="form-control me-1" type="search" name="search" placeholder="Pesquisar" aria-label="Search">
        <input type="submit" class="btn btn-lg btn-outline-primary mt-1 me-5 fa fa-search">
    </form>
</header>
<hr>
<div class="container mt-1">
    <div class="row justify-content-md-center">
        <div class="col-lg-12 col-md-auto col-sm-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item <?= isset($done) || isset($search) ? '' : 'active' ?>" <?= isset($done) ? '' : 'aria-current="page"' ?>>
                        <?= isset($done) || isset($search) ? "<a  href='$baseurl'>Home</a>" : "Home" ?></li>

                    <?= isset($search) && empty($done) ?
                        "<li class='breadcrumb-item active' aria-current='page'>Pesquisa</li>" : '' ?>

                    <?= isset($done) && empty($search) ? '<li class="breadcrumb-item active" aria-current="page">Concluídas</li>' : '' ?>
                    <?= isset($search) && isset($done) ?
                        "<li class='breadcrumb-item'><a href='$doneurl'>Concluídas</a></li>
                        <li class='breadcrumb-item active' aria-current='page'>Pesquisa</li>" : '' ?>
                </ol>
            </nav>
            <?php if (count($jobs) == 0) : ?>
                <h3 class="alert alert-warning text-center"><?= isset($done) || isset($search) ? 'Não existem tarefas para esta pesquisa' : 'Não existem tarefas' ?></h3>
            <?php else : ?>
                <div class="row">
                    <?php foreach ($jobs as $job) : ?>
                        <div class="col-3 card ms-1 mt-1">
                            <div class="mt-1 d-flex justify-content-between">
                                <p><?= $job->USER ?></p>
                                <p><?= date("d/m/Y", strtotime($job->DATETIME_CREATED)) ?></p>
                            </div>
                            <hr>
                            <div class="p-2 text-center">
                                <h4><?= $job->JOB ?></h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="row" id="pager">
                        <div class="col mt-1">
                            <?php
                            if ($pager) {
                                echo $pager->links();
                            }
                            ?>
                        </div>
                        <div class="col text-end">
                            <p>Mostrando <strong><?= count($jobs) ?></strong> de <strong><?= $alljobs ?></strong></p>
                        </div>
                    </div>
                <?php endif; ?>
                </div>
        </div>
    </div>
    <?= $this->endSection() ?>