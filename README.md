# About this project
This project is a full-stack application, which means that I developed both the front-end and the back-end of the application, using several technologies that I learned so far.

In the front-end, Bootstrap 5 and FontAwesome 6 were used to create a responsive and modern design for the application. I also used JavaScript and CSS to give more functionality and styling.

For the back-end, I chose to use the CodeIgniter 4 framework, as I already have a certain affinity with its previous version (CodeIgniter 3) as I use it a lot in my internship. So I thought that a good way to learn this new version of the framework would be to use it to develop a real application. MySQL database to store user information and tasks.

In this application, users can create a profile and share their tasks with others. This is very useful for organizing team projects or remembering activities that need to be done together.

In short, this project is a full-stack application for managing shared tasks that uses several technologies that I've been learning in my journey as a programmer. The main focus here is learning, as I learn new technologies and different forms of programs I will be applying them to this project. It is also a way to materialize my knowledge by creating a real application and putting it on the air, remembering that this application is still under development so all feedback is welcome.

# Sobre este projeto
Este projeto é uma aplicação full-stack, o que significa que eu desenvolvi tanto o front-end quanto o back-end da aplicação, usando diversas tecnologias que aprendi até o momento.

No front-end foi utilizado Bootstrap 5 e FontAwesome 6 para criar um design responsivo e moderno para a aplicação. Também utilizei JavaScript e CSS para dar mais funcionalidades e estilizações.

Para o back-end, optei por utilizar a framework CodeIgniter 4, pois já tenho certa afinidade com sua versão anterior (CodeIgniter 3) por utiliza-la bastante no meu estágio. Então achei que uma boa forma de aprender esta nova versão do framework seria utilizando-a para desenvolver uma aplicação real. Banco de dados MySQL para armazenar as informações dos usuários e suas tarefas.

Nesta aplicação, os usuários podem criar um perfil e compartilhar suas tarefas com outras pessoas. Isso é muito útil para organizar projetos em equipe ou para lembrar de atividades que precisam ser feitas em conjunto.

Em resumo, este projeto é uma aplicação full-stack de gerenciamento de tarefas compartilhadas que usa diversas tecnologias que venho aprendendo na minha jornada como programador. O foco principal aqui é o aprendizado, conforme eu for aprendendo novas tecnologias e formas diferentes de programas estarei aplicando neste projeto. É também uma forma de materializar meus conhecimentos criando uma aplicação de verdade e a colocando no ar, lembrando que esta aplicação segue em desenvolvimento então todo feedback é bem-vindo.

# CodeIgniter 4 Development

[![Build Status](https://github.com/codeigniter4/CodeIgniter4/workflows/PHPUnit/badge.svg)](https://github.com/codeigniter4/CodeIgniter4/actions?query=workflow%3A%22PHPUnit%22)
[![Coverage Status](https://coveralls.io/repos/github/codeigniter4/CodeIgniter4/badge.svg?branch=develop)](https://coveralls.io/github/codeigniter4/CodeIgniter4?branch=develop)
[![Downloads](https://poser.pugx.org/codeigniter4/framework/downloads)](https://packagist.org/packages/codeigniter4/framework)
[![GitHub release (latest by date)](https://img.shields.io/github/v/release/codeigniter4/CodeIgniter4)](https://packagist.org/packages/codeigniter4/framework)
[![GitHub stars](https://img.shields.io/github/stars/codeigniter4/CodeIgniter4)](https://packagist.org/packages/codeigniter4/framework)
[![GitHub license](https://img.shields.io/github/license/codeigniter4/CodeIgniter4)](https://github.com/codeigniter4/CodeIgniter4/blob/develop/LICENSE)
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/codeigniter4/CodeIgniter4/pulls)
<br>

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](http://codeigniter.com).

This repository holds the source code for CodeIgniter 4 only.
Version 4 is a complete rewrite to bring the quality and the code into a more modern version,
while still keeping as many of the things intact that has made people love the framework over the years.

More information about the plans for version 4 can be found in [the announcement](http://forum.codeigniter.com/thread-62615.html) on the forums.

### Documentation

The [User Guide](https://codeigniter4.github.io/userguide/) is the primary documentation for CodeIgniter 4.

The current **in-progress** User Guide can be found [here](https://codeigniter4.github.io/CodeIgniter4/).
As with the rest of the framework, it is a work in progress, and will see changes over time to structure, explanations, etc.

You might also be interested in the [API documentation](https://codeigniter4.github.io/api/) for the framework components.

## Important Change with index.php

index.php is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

CodeIgniter is developed completely on a volunteer basis. As such, please give up to 7 days
for your issues to be reviewed. If you haven't heard from one of the team in that time period,
feel free to leave a comment on the issue so that it gets brought back to our attention.

We use GitHub issues to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

If you raise an issue here that pertains to support or a feature request, it will
be closed! If you are not sure if you have found a bug, raise a thread on the forum first -
someone else may have encountered the same thing.

Before raising a new GitHub issue, please check that your bug hasn't already
been reported or fixed.

We use pull requests (PRs) for CONTRIBUTIONS to the repository.
We are looking for contributions that address one of the reported bugs or
approved work packages.

Do not use a PR as a form of feature request.
Unsolicited contributions will only be considered if they fit nicely
into the framework roadmap.
Remember that some components that were part of CodeIgniter 3 are being moved
to optional packages, with their own repository.

## Contributing

We **are** accepting contributions from the community!

Please read the [*Contributing to CodeIgniter*](https://github.com/codeigniter4/CodeIgniter4/blob/develop/contributing/README.md).

## Server Requirements

PHP version 7.3 or higher is required, with the following extensions installed:


- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- xml (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)

## Running CodeIgniter Tests

Information on running the CodeIgniter test suite can be found in the [README.md](tests/README.md) file in the tests directory.
