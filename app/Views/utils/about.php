<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>

<div class="container">
    <div class="row bg-light bg-gradient">
        <div class="col-8 offset-2">
            <h1 class="text-center">Sobre este projeto</h1>
            <hr>
            <div class="about">
                <p>Este projeto é uma aplicação full-stack, o que significa que eu desenvolvi tanto o front-end quanto o back-end da aplicação, usando diversas tecnologias que aprendi até o momento. </p>
                <p>No front-end foi utilizado Bootstrap 5 e FontAwesome 6 para criar um design responsivo e moderno para a aplicação. Também utilizei JavaScript e CSS para dar mais funcionalidades e estilizações.</p>
                <p>Para o back-end, optei por utilizar a framework CodeIgniter 4, pois já tenho certa afinidade com sua versão anterior (CodeIgniter 3) por utiliza-la bastante no meu estágio. Então achei que uma boa forma de aprender esta nova versão do framework seria utilizando-a para desenvolver uma aplicação real. Banco de dados MySQL para armazenar as informações dos usuários e suas tarefas. </p>
                <p>Nesta aplicação, os usuários podem criar um perfil e compartilhar suas tarefas com outras pessoas. Isso é muito útil para organizar projetos em equipe ou para lembrar de atividades que precisam ser feitas em conjunto.</p>
                <p>Em resumo, este projeto é uma aplicação full-stack de gerenciamento de tarefas compartilhadas que usa diversas tecnologias que venho aprendendo na minha jornada como programador. O foco principal aqui é o aprendizado, conforme eu for aprendendo novas tecnologias e formas diferentes de programas estarei aplicando neste projeto. É também uma forma de materializar meus conhecimentos criando uma aplicação de verdade e a colocando no ar, lembrando que esta aplicação segue em desenvolvimento então todo feedback é bem-vindo.</p>
                <hr>
                <div class="text-end">
                    <a class="link-secondary" href="https://github.com/JeanCSF/todo" target="_blank" rel="noopener noreferrer">Repositório</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>