<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiAuthController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $postData = json_decode(file_get_contents('php://input'), true);
		$_POST = $postData;
    }
    
    public function login()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'login',
            'Login',
            'required',
            array(
                "required" => "O campo %s é obrigatório"
            )
        );
        $this->form_validation->set_rules(
            'senha',
            'Senha',
            'required',
            array(
                "required" => "O campo %s é obrigatório"
            )
        );
        $this->form_validation->set_error_delimiters('', '');

        if (!$this->form_validation->run()) {
            $data["status"] = false;
            $data['errors'] = array(
                "login" => form_error('login'),
                "senha" => form_error('senha'),
            );

            http_response_code(401);
            die(json_encode($data));
        } else {
            $this->load->model('usuario');
            $usuario = $this->usuario->validate($this->input->post('login'), md5($this->input->post('senha')));

            if ($usuario) {
                if (!$usuario->status) {
                    $data['status'] = false;
                    $data['error'] = "Usuário inativo!";

                    http_response_code(401);
                    die(json_encode($data));
                }
                $this->session->set_userdata("loggedUser", $usuario);
                $data['status'] = true;

                http_response_code(200);
                die(json_encode($data));
            } else {
                $data['status'] = false;
                $data['errors'] = 'Usuário ou senha incorretos';

                http_response_code(401);
                die(json_encode($data));
            }
        }
    }

    public function logout()
    {
        $this->load->helper('api_auth');
		isAuthenticatedApi();

        $this->session->unset_userdata("loggedUser");
        $data['status'] = true;

        http_response_code(200);
        die(json_encode($data));
    }
}
