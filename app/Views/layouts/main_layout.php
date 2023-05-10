<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tome Nota!</title>
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/main.css') ?>">
    <!-- FontAwesome 6.2.0 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- (Optional) Use CSS or JS implementation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="bg-gradient">
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
                            <div class="col-8 offset-2">
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
                                    <div class="modal-footer">
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
                            <div class="p-4 col-10 offset-1 mb-3">
                                <form action="" id="form" method="post">
                                    <div class="form-group">
                                        <input type="text" placeholder="Nome da tarefa" name="job_name" id="job_name" value="" class="form-control" autofocus required>
                                        <textarea placeholder="Descrição" name="job_desc" id="job_desc" rows="5" value="" class="form-control mt-3" required></textarea>
                                        <input type="hidden" name="id_job" id="id_job" value="">
                                        <input type="hidden" id="editar" value="">
                                    </div>
                            </div>
                            <div class="modal-footer">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" id="btnDeletar" value="Sim, Deletar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Modal -->
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
    <header>
        <navbar class="ms navbar navbar-expand-lg bg-light mb-2">
            <ul class="text-light col-1 navbar-nav me-auto mb-1 mb-lg-0">
                <li class="nav-item">
                    <a href="<?= base_url('/') ?>">
                    <img class="rounded float-start" src="<?= base_url('/assets/logo.png')?>" alt="logo" width="64" height="64">
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/') ?>" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('main/about') ?>" class="nav-link">Sobre</a>
                </li>
            </ul>
            <div class="col-1 collapse navbar-collapse justify-content-end" id="navbarNavDarkDropdown">
                <div class="btn-group dropstart">
                    <button type="button" class="btn bg-transparent border-0 fs-3" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <?php if (isset($_SESSION['USER_ID'])) : ?>
                            <?php if ($_SESSION['SU'] == 1) : ?>
                                <li><a class="dropdown-item" href="<?= base_url('userscontroller/users/') ?>"><i class="fa fa-users"></i> Usuários</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="<?= base_url('userscontroller/profile/' . base64_encode($_SESSION['USER_ID'])) ?>"><i class="fa fa-user"></i> Minha Conta</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('logincontroller/logout') ?>"><strong><i class="fa fa-right-from-bracket"></i></strong> Logout</a></li>
                        <?php else : ?>
                            <li><a class="dropdown-item" href="<?= base_url('logincontroller/login') ?>"><i class="fa fa-user"></i> Minha Conta</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </navbar>
    </header>

    <section class="content">
        <?= $this->renderSection('section') ?>
    </section>

    <footer class="footer container-fluid bg-dark bg-gradient text-white fw-5 fs-5 mt-3">
        <div class="row">
            <div class="col-4">
                <a href="<?= base_url('main/contact')?>" class="text-decoration-none link-secondary fw-bolder">FEEDBACK</a>
            </div>
            <div class="col-4">
                 
            </div>
            <div class="footer-socials col-4 d-flex flex-column justify-content-between">
                <div>
                    <a class="link-secondary me-1" href="https://github.com/JeanCSF" target="_blank">GitHub</a>
                    <a class="link-secondary mx-2" href="https://facebook.com/fookinselfish" target="_blank">Facebook</a>
                    <a class="link-secondary mx-2" href="https://twitter.com/JCS_16" target="_blank">Twitter</a>
                    <a class="link-secondary me-2" href="https://www.linkedin.com/in/jean-carlos-6149a2232/" target="_blank">Linkedin</a>
                    <a class="link-secondary" href="https://instagram.com/fookinselfish" target="_blank">Instagram</a>
                </div>
                <div>
                    <p class="link-secondary">Site design / logo &copy; <?= date("Y") ?> c0ka0 Inc;</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?= base_url('assets/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap.min.js') ?>"></script>

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
        }
    </script>
    <?= $this->renderSection("script"); ?>
</body>

</html>