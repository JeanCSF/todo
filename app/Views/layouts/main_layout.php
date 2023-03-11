<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO!</title>
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap.min.css') ?>">
    <style>
        #pager li {
            width: 25px;
            height: 25px;
            margin-left: 15px;
        }

        #pager a:hover {
            color: #EFEFEF;
            background-color: #3B71CA;
        }

        .active a {
            color: #EFEFEF !important;
            background-color: #3B71CA !important;
        }

        #pager a {
            display: inline-block;
            position: relative;
            z-index: 1;
            padding: 1em;
            margin: -1em;
            text-decoration: none;
            color: black;
            font-weight: bolder;
        }
    </style>
</head>

<!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="taskModalLabel">Adicionar Tarefa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-6 offset-3">
                            <form action="" id="form" method="post">
                                <div class="form-group">
                                    <input type="text" placeholder="Nome da tarefa" name="job_name" id="job_name" value="" class="form-control" required>
                                    <input type="hidden" name="id_job" id="id_job" value="">
                                    <input type="hidden" id="editar" value="">
                                </div>
                        </div>
                    </div>
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
<!-- Task Modal -->

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel">Deletar Tarefa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3>Deseja deletar a tarefa:</h3>
                <h5 id="tarefa"></h5>
                <span class="text-danger">Esta ação é irreversível</span>
            </div>
            <form action="" id="formDelete" method="post">
            <input type="hidden" name="deleteJobId" id="deleteJobId" value="">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <input type="submit" class="btn btn-warning" id="btnDeletar" value="Deletar">
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Modal -->

<body>
    <navbar class="navbar navbar-expand-lg bg-light mb-2">
        <ul class="text-light col-1 navbar-nav me-auto mb-1 mb-lg-0">
            <li class="nav-item">
                <a href="<?php echo base_url('/') ?>" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Sobre</a>
            </li>
        </ul>
        <div class="col-1 collapse navbar-collapse justify-content-end" id="navbarNavDarkDropdown">
            <div class="btn-group dropstart">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    &#9965;
                </button>
                <ul class="dropdown-menu">
                    <?php if (isset($_SESSION['USER_ID'])) : ?>
                        <?php if ($_SESSION['SU'] == 1) : ?>
                            <li><a class="dropdown-item" href="<?= base_url('userscontroller/users/') ?>">&#129485; Usuários</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="<?= base_url('userscontroller/profile/' . base64_encode($_SESSION['USER_ID'])) ?>">&#129485; Minha Conta</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('logincontroller/logout') ?>"><strong>&#8998;</strong> Logout</a></li>
                    <?php else : ?>
                        <li><a class="dropdown-item" href="<?= base_url('logincontroller/login') ?>">&#129485; Minha Conta</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </navbar>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="top: 10px; right: 10px; z-index: 9999;">
        <div id="basicToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="alert" style="margin-bottom: 0;" id="alerta">
                <span id="msgInfo" style="text-transform: capitalize;"></span>
                <button type="button" class="btn-close btn-close-black float-end" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <?= $this->renderSection('conteudo') ?>

    <footer class="fixed-bottom text-center">
        <div>
            &copy; <?php echo date('Y') ?>
        </div>
    </footer>

    <script src="<?= base_url('assets/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap.min.js') ?>"></script>
    <script>
        <?php
        if (isset($_SESSION['mensagem'])) {
            echo "msg = document.querySelector('#msgInfo');
             alerta = document.querySelector('#alerta');
             alerta.classList.add('" . $_SESSION['mensagem']['tipo'] . "');
             msg.textContent = '" . $_SESSION['mensagem']['mensagem'] . "';
             new bootstrap.Toast(document.querySelector('#basicToast')).show();";
        }
        ?>

        function fillModalDelete(id) {
            frm = document.getElementById("formDelete");
            frm.setAttribute('action', '<?= site_url('todocontroller/delete') ?>')
            frmId = document.getElementById("deleteJobId");
            frmId.setAttribute('value', id);

        }

        function fillModalNewJob() {
            frm = document.getElementById("form");
            frm.setAttribute('action', '<?= site_url('todocontroller/newjobsubmit') ?>')

            frmBtn = document.getElementById("btnSubmit");
            frmBtn.setAttribute('value', 'Gravar');
        }

        function fillModalEdit(id, job) {
            frm = document.getElementById("form");
            frm.setAttribute('action', '<?= site_url('todocontroller/editjobsubmit') ?>')

            frmBtn = document.getElementById("btnSubmit");
            frmBtn.setAttribute('value', 'Atualizar');

            frmId = document.getElementById("id_job");
            frmId.setAttribute('value', id);

            frmJob = document.getElementById("job_name");
            frmJob.setAttribute('value', job);

        }

        function deleteJob(id) {
            var id = btnDelete.getAttribute('dado-alvo', id);
            window.location.replace('todocontroller/delete/' + id);
        }
    </script>
    <?= $this->renderSection("script"); ?>
</body>

</html>