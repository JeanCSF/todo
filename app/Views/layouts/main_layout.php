<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1">
    <title>Tome Nota!</title>
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/main.css') ?>">
    <!-- FontAwesome 6.2.0 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- (Optional) Use CSS or JS implementation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="profileModalLabel">Foto do Perfil</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="p-4 mb-3">
                                <form action="<?= url_to('upload') ?>" id="formProfilePic" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <?php if (session()->has('errors')) : ?>
                                            <p class="text-danger"><?= session()->get('errors')['userfile'] ?></p>
                                        <?php endif; ?>
                                        <?php if (session()->has('uploaded')) : ?>
                                            <p class="text-success"><?= session()->get('uploaded') ?></p>
                                        <?php endif; ?>
                                        <input type="file" name="userfile" id="userfile" class="form-control-file">
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <input type="submit" value="Salvar Imagem" id="btnUpload" onclick="" class="btn btn-success">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile Modal -->

    <!-- Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="taskModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="p-4 mb-3">
                                <form action="" id="form" method="post">
                                    <div class="row mb-3">
                                        <input type="text" placeholder="Nome da tarefa" name="job_name" id="job_name" value="" class="form-control" autofocus required>
                                    </div>
                                    <div class="row mb-3">
                                        <textarea style="height: 150px;" name="job_desc" id="job_desc" value="" class="form-control" placeholder="Descrição" required></textarea>
                                    </div>
                                    <input type="hidden" name="id_job" id="id_job" value="">
                                    <input type="hidden" id="editar" value="">
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <input type="submit" value="" id="btnSubmit" onclick="" class="btn btn-success">
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Task Modal -->

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 id="bodyMsg"></h3>
                    <h5 id="tarefa"></h5>
                    <span class="text-danger">Esta ação é irreversível</span>
                </div>
                <form action="" id="formDelete" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" id="btnDeletar" value="Sim, Deletar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Modal -->

    <!-- Privacy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Privacidade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 mb-3">
                    <form action="<?= base_url('todocontroller/changeprivacy') ?>" id="formPrivacy" method="post">
                        <div class="row">
                            <div class="row mb-3 d-flex">
                                <div class="col-1">
                                    <input type="radio" name="privacyRb" id="privacyRb" value="<?= true ?>">
                                </div>
                                <div class="col-11">
                                    <label for="privacyRb">Visível para todos</label>
                                </div>
                            </div>
                            <div class="row mb-3 d-flex">
                                <div class="col-1">
                                    <input type="radio" name="privacyRb" id="privacyRb" value="<?= false ?>">
                                </div>
                                <div class="col-11">
                                    <label for="privacyRb">Somente eu</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="privacy_id" id="privacy_id" value="">
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <input type="submit" class="btn btn-primary" id="btnPrivacy" value="Salvar Alterações">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Privacy Modal -->

    <!-- Plus Task Modal -->
    <div class="modal fade" id="plusTaskModal" tabindex="-1" aria-labelledby="plusTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="plusTaskModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body col-10 offset-1 p-2">
                    <div class="row">
                        <p id="plusTaskModalDesc" class="text-justify"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Plus Task Modal -->


    <!-- Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="top: 10px; right: 10px; z-index: 9999;">
        <div id="basicToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="alert" style="margin-bottom: 0;" id="alerta">
                <span id="msgInfo" style="text-transform: capitalize;"></span>
                <button type="button" class="btn-close btn-close-black float-end" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <!-- Toast Notification -->

    <div class="row">
        <header class="d-flex flex-column justify-content-between mt-2 left-panel">
            <div id="userActions">
                <a href="<?= base_url('/') ?>" class="nav-link"><i class="fa fa-dashboard icon"></i></a>
                <a href="<?= base_url('/') ?>" class="nav-link"><i class="fa fa-home icon"></i> <span class="side-text">Home</span></a>
                <a href="about" class="nav-link"><i class="fa fa-circle-info icon"></i> <span class="side-text">Sobre</span></a>
                <a href="<?= base_url('explore') ?>" class="nav-link"><i class="fa fa-hashtag icon"></i> <span class="side-text">Explorar</span></a>
                <a href="<?= base_url('explore') ?>" class="nav-link"><i class="fa fa-hashtag icon"></i> <span class="side-text">Explorar</span></a>
                <a href="<?= base_url('explore') ?>" class="nav-link"><i class="fa fa-hashtag icon"></i> <span class="side-text">Explorar</span></a>
                <a href="<?= base_url('explore') ?>" class="nav-link"><i class="fa fa-hashtag icon"></i> <span class="side-text">Explorar</span></a>
                <a href="<?= base_url('explore') ?>" class="nav-link"><i class="fa fa-hashtag icon"></i> <span class="side-text">Explorar</span></a>
                <a href="<?= base_url('explore') ?>" class="nav-link"><i class="fa fa-hashtag icon"></i> <span class="side-text">Explorar</span></a>
                <a href="<?= base_url('explore') ?>" class="nav-link"><i class="fa fa-hashtag icon"></i> <span class="side-text">Explorar</span></a>
                <a href="<?= base_url('explore') ?>" class="nav-link"><i class="fa fa-hashtag icon"></i> <span class="side-text">Explorar</span></a>
                <?php if (isset($_SESSION['USER_ID'])) : ?>
                    <?php if ($_SESSION['SU'] == 1) : ?>
                        <a href="<?= base_url('userscontroller/users/') ?>"><i class="fa fa-users icon"></i> <span class="side-text">Usuários</span></a>
                    <?php endif; ?>
                    <a href="logout"><i class="fa fa-right-from-bracket icon"></i> <span class="side-text">Logout</span></a>
                    <a id="sidebarTask" type="button" data-bs-toggle="modal" data-bs-target="#taskModal" title="Adicionar Tarefa" role="new task" onclick="fillModalNewJob()"></a>

                    <div class="avatarDiv">
                        <a style="text-decoration: none;" href="<?= base_url('profile/' . base64_encode($_SESSION['USER_ID'])) ?>" class="link-secondary fs-4">
                            <img class="rounded-circle border border-light-subtle" height="48" width="48" src="<?= !empty($_SESSION['IMG']) ? base_url('../../assets/img/profiles_pics/' . $_SESSION['USER'] . '/' . $_SESSION['IMG']) : base_url('/assets/logo.png') ?>" alt=""> <span class="ms-2 side-text"><?= $_SESSION['USER'] ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <main class="content">
            <section>
                <?php if (!empty($pageTitle)) : ?>
                    <div class="row">
                        <div class="page-title">
                            <div class="row">
                                <div class="d-flex justify-content-between">
                                    <a href="javascript:history.go(-1)" style="<?= ($pageTitle == 'Página Inicial') ? 'visibility:hidden' : '' ?>"><i class="fa fa-arrow-left me-3"></i></a>
                                    <p><?= isset($pageTitle) ? $pageTitle : "" ?></p>
                                    <a style="<?= ($pageTitle != 'Página Inicial') ? 'visibility:hidden' : '' ?>" id="navbarTask" type="button" data-bs-toggle="modal" data-bs-target="#taskModal" title="Adicionar Tarefa" role="new task" onclick="fillModalNewJob()"><i class="fa fa-pencil"></i><i style="font-size: small;" class="fa fa-circle-plus"></i></a>
                                </div>
                                <?php if (isset($search)) : ?>
                                    <form class="d-flex mt-1 search" role="search">
                                        <button type="submit" class="btn btn-lg"><i class="fa fa-search"></i></button>
                                        <input class="form-control shadow-none" type="search" value="<?= isset($search) ? $searchInput : '' ?>" name="search" aria-label="Search" />
                                    </form>
                                    <div class="search-footer d-flex justify-content-between">
                                        <a href="<?= base_url('main/searchuser') ?>">Tarefas</a>
                                        <a href="<?= base_url('main/searchuser') ?>">Pessoas</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <?= $this->renderSection('section') ?>
            </section>
        </main>
        <footer class="d-flex flex-column justify-content-between right-panel">
            <?php if (!isset($search)) : ?>
                <form class="d-flex mt-2 search" role="search">
                    <button type="submit" class="btn btn-lg"><i class="fa fa-search"></i></button>
                    <input class="form-control shadow-none" type="search" value="" name="search" aria-label="Search" />
                </form>
            <?php endif; ?>
            <div>
                <div class="footer mt-3">
                    <a href="contact" class="text-decoration-none link-secondary fw-bolder">FEEDBACK</a>
                    <div class="footer-socials">
                        <a class="link-secondary me-4" href="https://github.com/JeanCSF" target="_blank">GitHub</a>
                        <a class="link-secondary me-4" href="https://facebook.com/fookinselfish" target="_blank">Facebook</a>
                        <a class="link-secondary me-4" href="https://twitter.com/JCS_16" target="_blank">Twitter</a>
                        <a class="link-secondary me-4" href="https://www.linkedin.com/in/jean-carlos-6149a2232/" target="_blank">Linkedin</a>
                        <a class="link-secondary" href="https://instagram.com/fookinselfish" target="_blank">Instagram</a>
                        <p>
                            <a class="link-secondary" href="http://jeancsf.github.io/portfolio" target="_blank" rel="noopener noreferrer"> Site design / logo &copy; <?= date("Y") ?> JeanCSF</a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <nav class="row task-bar">
        <ul>
            <li><a href="<?= base_url('/') ?>" class="nav-link"><i class="fa fa-home icon"></i></a></li>
            <li><a href="explore" class="nav-link"><i class="fa fa-search icon"></i></a></li>
            <li><a href="about" class="nav-link"><i class="fa fa-circle-info icon"></i></a></li>
            <li><a href="#" class="nav-link"><i class="fa fa-gear icon"></i></a></li>
        </ul>

    </nav>

    <script src="<?= base_url('assets/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap.min.js') ?>"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <script>
        <?php
        if (isset($_SESSION['msg'])) {
            echo "msg = document.querySelector('#msgInfo');
             alerta = document.querySelector('#alerta');
             alerta.classList.add('" . $_SESSION['msg']['type'] . "');
             msg.textContent = '" . $_SESSION['msg']['msg'] . "';
             new bootstrap.Toast(document.querySelector('#basicToast')).show();";
        }
        ?>

        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

        function fillModalEdit(id, job, desc) {
            document.getElementById("form").setAttribute('action', '<?= site_url('todocontroller/editjobsubmit') ?>');
            document.getElementById("taskModalLabel").textContent = "Atualizar Tarefa";
            document.getElementById("btnSubmit").setAttribute('value', 'Atualizar');
            document.getElementById("id_job").setAttribute('value', id);
            document.getElementById("job_name").setAttribute('value', job);
            document.getElementById("job_desc").setAttribute('value', desc);
            document.getElementById("job_desc").textContent = '' + desc;

        }

        function fillModalDelete(id) {
            document.getElementById("formDelete").setAttribute('action', '<?= site_url('todocontroller/delete') ?>');
            document.getElementById("modalTitle").textContent = "Deletar Tarefa";
            document.getElementById("bodyMsg").textContent = "Deseja realmente deletar esta tarefa?";
            document.getElementById("id").setAttribute('value', id);

        }

        function fillModalDeleteUser(id) {
            document.getElementById("formDelete").setAttribute('action', '<?= site_url('userscontroller/delete') ?>');
            document.getElementById("modalTitle").textContent = "Deletar Usuário";
            document.getElementById("bodyMsg").textContent = "Deseja realmente deletar este usuário?";
            document.getElementById("id").setAttribute('value', id);

        }

        function fillModalNewJob() {
            document.getElementById("form").setAttribute('action', '<?= site_url('todocontroller/newjobsubmit') ?>');
            document.getElementById("taskModalLabel").textContent = "Adicionar Tarefa";
            document.getElementById("btnSubmit").setAttribute('value', 'Gravar');
            document.getElementById("id_job").setAttribute('value', '');
            document.getElementById("job_name").setAttribute('value', '');
            document.getElementById("job_desc").setAttribute('value', '');
            document.getElementById("job_desc").textContent = '';
        }

        function fillModalPlus(job, title) {
            document.getElementById("plusTaskModalDesc").textContent = job;
            document.getElementById("plusTaskModalTitle").textContent = title;
        }

        function fillModalPrivacy(id) {
            document.getElementById("privacy_id").setAttribute('value', id)
        }
    </script>
    <?= $this->renderSection("script"); ?>

</body>

</html>