<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

    <style>
        html,
        body,
        .menu,
        .main {
            height: 100vh;
            width: 100vw;
        }

        body {
            background-color: #d3e8d9;
        }

        .menu {
            width: 210px;
        }

        .nav {
            height: 100%;
        }
    </style>
    <div class="menu d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
        <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4">Bem vindo</span>
        </a>
        <span><?=$this->session->userData("loggedUser")->nome?></span>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?=base_url('usuarios')?>" class="nav-link text-white"><i class="bi bi-person-lines-fill m-2"></i>Usuarios</a>
            </li>
            <hr class="mt-auto">
            <li class="nav-item">
                <a href="<?=base_url('meusdados')?>" class="nav-link text-white">Alterar meus dados</a>
            </li>
            <li class="nav-item">
                <a href="<?=base_url('auth/logout')?>" class="nav-link text-white">Sair</a>
            </li>
        </ul>
    </div>