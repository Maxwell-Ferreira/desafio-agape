<?php
defined('BASEPATH') or exit('No direct script access allowed');

function isAuthenticated(){
    $ci = get_instance();
    $loggedUser = $ci->session->userdata("loggedUser");

    if(!$loggedUser){
        $ci->session->set_flashdata("danger", 
            '<div class="alert alert-danger">Você precisa estar logado para acessar essa página!</div>'
        );
        redirect('auth/login');
    }else{
        $ci->session->unset_userdata("danger");
    }

    return $loggedUser;

}

function isAdmin(){
    $ci = get_instance();
    $loggedUser = $ci->session->userdata("loggedUser");

    if(!$loggedUser->admin){
        $ci->session->set_flashdata("danger", 
            '<div class="alert alert-danger">Você não possui privilégios de administrador para acessar essa página!</div>'
        );
        redirect('auth/login');
    }
}
