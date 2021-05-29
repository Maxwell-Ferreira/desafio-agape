<?php
defined('BASEPATH') or exit('No direct script access allowed');

function isAuthenticatedApi()
{
    $ci = get_instance();
    $loggedUser = $ci->session->userdata("loggedUser");

    if(!$loggedUser){
        http_response_code(401);
        $data["status"] = false;
        $data["error"] = "Você precisa estar logado para ter acesso!";

        die(json_encode($data));
    }

}
