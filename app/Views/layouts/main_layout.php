<?php

?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1">
    <meta name="description" content="Conecte-se com amigos e compartilhe momentos autênticos, sem os ruídos das redes sociais convencionais. Seja você mesmo na Anti Social Social Network!">
    <title>Anti Social Social Network</title>
    <script>
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        }
    </script>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap-5.3.1-dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/chat.css') ?>">

</head>

<body>
    <?= $this->include('layouts/modals') ?>
    <div class="row">
        <header class="d-flex flex-column justify-content-between mt-2 left-panel">
            <div id="userActions">
                <a href="<?= base_url('home') ?>" class="dropdown-item assn-text">ASSN</a>
                <a href="<?= base_url('home') ?>" class="dropdown-item"><i class="fa fa-home icon"></i><span class="side-text">Home</span></a>
                <?php if (isset($_SESSION['USER_ID'])) : ?>
                    <a href="<?= base_url('user/' . $_SESSION['USER']) ?>" class="dropdown-item"><i class="fa fa-user icon"></i><span class="side-text">Perfil</span></a>
                    <a href="<?= base_url('messages') ?>" class="dropdown-item"><i class="fa fa-inbox icon"></i><span class="side-text">Mensagens</span></a>
                    <a href="<?= base_url('user/' . $_SESSION['USER']) ?>" class="dropdown-item"><i class="fa fa-bell icon"></i><span class="side-text">Notificações</span></a>
                    <a href="<?= base_url('logout') ?>" class="dropdown-item"><i class="fa fa-right-from-bracket icon"></i><span class="side-text">Logout</span></a>
                    <?php if ($_SESSION['SU'] == 1) : ?>
                        <a href="<?= base_url('users') ?>" class="dropdown-item"><i class="fa fa-users icon"></i><span class="side-text">Usuários</span></a>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="<?= base_url('about') ?>" class="dropdown-item"><i class="fa fa-circle-info icon"></i><span class="side-text">Sobre</span></a>
                <a href="javascript:void(0)" role="Theme button" id="themeToggleButton" class="dropdown-item"><i class="fa fa-circle-half-stroke icon"></i> <span class="side-text">Tema</span></a>
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
                                    <a style="<?= ($pageTitle != 'Página Inicial') ? 'visibility:hidden' : '' ?>" id="navbarTask" type="button" data-bs-toggle="modal" data-bs-target="#taskModal" title="Adicionar Tarefa" role="button" onclick="fillModalNewJob()"><i class="fa fa-pencil"></i><i style="font-size: small;" class="fa fa-circle-plus"></i></a>
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
                    <button type="submit" class="btn btn-lg" title="Pesquisar" role="search"><i class="fa fa-search"></i></button>
                    <input class="form-control shadow-none" type="search" value="" name="search" aria-label="Search" />
                </form>
            <?php endif; ?>
            <div>
                <div class="footer mt-3">
                    <a href="<?= base_url('contact') ?>" class="text-decoration-none fw-bolder text-reset">FEEDBACK</a>
                    <div class="footer-socials">
                        <a class="me-4 text-reset" href="https://github.com/JeanCSF" target="_blank">GitHub</a>
                        <a class="me-4 text-reset" href="https://facebook.com/fookinselfish" target="_blank">Facebook</a>
                        <a class="me-4 text-reset" href="https://twitter.com/JCS_16" target="_blank">Twitter</a>
                        <a class="me-4 text-reset" href="https://www.linkedin.com/in/jean-carlos-6149a2232/" target="_blank">Linkedin</a>
                        <a class="text-reset" href="https://instagram.com/fookinselfish" target="_blank">Instagram</a>
                        <p>
                            <a class="text-reset" href="http://jeancsf.github.io/portfolio" target="_blank" rel="noopener noreferrer"> Site design / logo &copy; <?= date("Y") ?> JeanCSF</a>
                        </p>
                    </div>
                </div>
                <div class="bottom-0 w-100" id="chatsContainer">
                    <div class="mt-2 chat-tab d-flex justify-content-between align-content-center ps-3">
                        <p><i class="fa fa-inbox me-2 pt-3 "></i>Mensagens</p>
                        <button type="button" class="btn border-0 bg-transparent" style="cursor: pointer;" onclick="toggleChat()"><i class="fa fa-angles-up"></i></button>
                    </div>
                    <div class="d-none bg-dark-subtle bottom-0" id="chats">
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                        <p>aaa</p>
                    </div>
                </div>
            </div>
        </footer>
        <div class="row">
            <nav class="task-bar">
                <ul>
                    <li><a href="<?= base_url('/home') ?>" class="nav-link" title="Home"><i class="fa fa-home icon"></i></a></li>
                    <li><a href="explore" class="nav-link"><i class="fa fa-search icon" title="Explore"></i></a></li>
                    <li><a href="<?= base_url('about') ?>" class="nav-link"><i class="fa fa-circle-info icon" title="About"></i></a></li>
                    <li>
                        <div class="dropdown">
                            <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="true" title="Options">
                                <i class="fa fa-gear"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-dark mt-3">
                                <li> <a href="<?= base_url('contact') ?>" class="dropdown-item"><i class="fa fa-message icon"></i> Feedback</a></li>
                                <li> <a href="javascript:void(0)" role="button" id="themeToggle" class="dropdown-item"><i class="fa fa-circle-half-stroke"></i> Tema</a></li>
                                <?php if (isset($_SESSION['USER_ID'])) : ?>
                                    <li><a class="dropdown-item" href="<?= base_url('user/' . $_SESSION['USER']) ?>"><i class="fa fa-user icon"></i><span class="side-text"> Perfil</span></a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fa fa-right-from-bracket icon"></i> Sair</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <script src="<?= base_url('assets/js/libs/jquery_3.7.0_jquery.min.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="<?= base_url('assets/bootstrap-5.3.1-dist/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/libs/font-awesome_6.4.0_js_all.min.js') ?>"></script>
    <script defer src="<?= base_url('assets/js/pages/main/main_scripts.js') ?>"></script>
    <script defer src="<?= base_url('assets/js/lazysizes.min.js') ?>"></script>
    <script>
        const BASEURL = '<?= base_url() ?>';
        var session_user_id = '<?= isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : '' ?>';
        var session_profile_pic = '<?= isset($_SESSION['IMG']) ? $_SESSION['IMG'] : '' ?>';
        var session_user = '<?= isset($_SESSION['USER']) ? $_SESSION['USER'] : '' ?>'

        <?php
        if (isset($_SESSION['msg'])) {
            echo "msg = document.querySelector('#msgInfo');
            alerta = document.querySelector('#alerta');
            alerta.classList.add('" . $_SESSION['msg']['type'] . "');
            msg.textContent = '" . $_SESSION['msg']['msg'] . "';
            new bootstrap.Toast(document.querySelector('#basicToast')).show();";
        }
        ?>
        const hoverPostElements = document.querySelectorAll('.post-container');
        const hoverLinkElements = document.querySelectorAll('.dropdown-item');

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        document.querySelector("#themeToggleButton").addEventListener("click", toggleTheme);
        document.querySelector("#themeToggle").addEventListener("click", toggleTheme);
    </script>
    <?= $this->renderSection('script') ?>

</body>

</html>