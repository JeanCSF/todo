<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('section') ?>

<div class="container">
    <div class="row">
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
    <hr>
    <div class="row bg-light bg-gradient">
        <div class="col-8 offset-2">
            <h1 class="text-center">About this project</h1>
            <hr>
            <div class="about">
                <p>This project is a full-stack application, which means that I developed both the front-end and the back-end of the application, using several technologies that I learned so far.</p>
                <p>In the front-end, Bootstrap 5 and FontAwesome 6 were used to create a responsive and modern design for the application. I also used JavaScript and CSS to give more functionality and styling.</p>
                <p>For the back-end, I chose to use the CodeIgniter 4 framework, as I already have a certain affinity with its previous version (CodeIgniter 3) as I use it a lot in my internship. So I thought that a good way to learn this new version of the framework would be to use it to develop a real application. MySQL database to store user information and tasks.</p>
                <p>In this application, users can create a profile and share their tasks with others. This is very useful for organizing team projects or remembering activities that need to be done together.</p>
                <p>In short, this project is a full-stack application for managing shared tasks that uses several technologies that I've been learning in my journey as a programmer. The main focus here is learning, as I learn new technologies and different forms of programs I will be applying them to this project. It is also a way to materialize my knowledge by creating a real application and putting it on the air, remembering that this application is still under development so all feedback is welcome.</p>
                <hr>
                <div class="text-end">
                    <a class="link-secondary" href="https://github.com/JeanCSF/todo" target="_blank" rel="noopener noreferrer">Repository</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>