<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthController extends CI_Controller
{

    public function index()
    {
        $this->session->unset_userdata("loggedUser");

        $this->load->view('templates/header');
        $this->load->view('pages/login');
        $this->load->view('templates/footer');
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
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

        if (!$this->form_validation->run()) {
            $data["errors"] = validation_errors();

            $this->load->view('templates/header');
            $this->load->view('pages/login', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->model('usuario');
            $usuario = $this->usuario->validate($this->input->post('login'), md5($this->input->post('senha')));

            if ($usuario) {
                if (!$usuario->status) {
                    $ci = get_instance();
                    $ci->session->set_flashdata(
                        "danger",
                        '<div class="alert alert-danger">ATENÇÃO: Usuário inativo!</div>'
                    );
                    redirect('auth/login');
                }
                $this->session->set_userdata("loggedUser", $usuario);
                redirect('usuarios');
            } else {
                $data['errors'] = '<div class="alert alert-danger">Usuário ou senha incorretos!</div>';
                $this->load->view('templates/header');
                $this->load->view('pages/login', $data);
                $this->load->view('templates/footer');
            }
        }
    }

    public function logout()
    {
        isAuthenticated();
        $this->session->unset_userdata("loggedUser");
        redirect('auth/login');
    }
}
