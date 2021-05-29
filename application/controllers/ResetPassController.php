<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ResetPassController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('usuario');
        $this->load->model('recuperarsenha');
    }

    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('pages/resetPass');
        $this->load->view('templates/footer');
    }

    public function sendEmail()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'email',
            'Email',
            'required|valid_email',
            array(
                "required" => "O campo %s é obrigatório!",
                "valid_email" => "Insira um %s válido!"
            )
        );
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        if (!$this->form_validation->run()) {
            $data["errors"] = validation_errors();

            $this->load->view('templates/header');
            $this->load->view('pages/resetPass', $data);
            $this->load->view('templates/footer');
        } else {

            $usuario = $this->usuario->findByEmail($this->input->post('email'));

            if (!$usuario) {
                $data["errors"] = '<div class="alert alert-danger>Email não registrado!</div>"';

                $this->load->view('templates/header');
                $this->load->view('pages/resetPass', $data);
                $this->load->view('templates/footer');
            } else {

                $hash = hash('sha512', mt_rand());

                $this->recuperarsenha->create($hash, $usuario->id);

                $this->load->library('email');
                $this->email->clear();

                $this->email->from('seuemail@gmail.com', 'Nome');
                $this->email->to($usuario->email, $usuario->nome);

                $this->email->subject('Recuperação de Senha');
                $this->email->message('Clique no link para alterar sua senha: ' . base_url('recuperar/' . $hash));

                if (!$this->email->send()) {
                    $this->email->print_debugger();
                    $data["errors"] = '<div class="alert alert-danger">Erro ao enviar o email: ' . $this->email->print_debugger() . '</div>';

                    $this->load->view('templates/header');
                    $this->load->view('pages/resetPass', $data);
                    $this->load->view('templates/footer');
                } else {
                    $data["errors"] = '<div class="mt-3 alert alert-success">Email enviado! Favor verifique sua caixa de entrada.</div>';

                    $this->load->view('templates/header');
                    $this->load->view('pages/resetPass', $data);
                    $this->load->view('templates/footer');
                }
            }
        }
    }

    public function showResetPass()
    {
        $validationHash = $this->uri->segment(2);
        $alterarSenha = $this->recuperarsenha->findByHash($validationHash);

        if(!$alterarSenha){
            redirect('auth/login');
        }
        
        $this->load->view('templates/header');
        $this->load->view('pages/formResetPass');
        $this->load->view('templates/footer');
    }

    public function resetPass()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'senha',
            'Senha',
            'required',
            array(
                "required" => "O campo %s é obrigatório!"
            )
        );
        $this->form_validation->set_rules(
            'confSenha',
            'Confirmar Senha',
            'required|matches[senha]',
            array(
                "required" => "O campo %s é obrigatório!",
                "matches" => "As senhas não confirmam"
            )
        );
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

        if (!$this->form_validation->run()) {
            $data["errors"] = validation_errors();

            $this->load->view('templates/header');
            $this->load->view('pages/formResetPass', $data);
            $this->load->view('templates/footer');
        } else {
            $validationHash = $this->uri->segment(2);

            $alterarSenha = $this->recuperarsenha->findByHash($validationHash);

            if ($alterarSenha) {
                $usuario = $this->usuario->find($alterarSenha->usuario_id);

                if ($usuario) {
                    $senha = $this->input->post('senha');
                    $this->usuario->alterarSenha(md5($senha), $usuario->id);

                    $this->session->set_flashdata(
                        "danger",
                        '<div class="alert alert-success">Senha alterada com sucesso!</div>'
                    );

                    redirect('auth/login');
                } else {
                    $data["errors"] = '<div class="alert alert-danger mt-3">Usuário não existe!</div>';

                    $this->load->view('templates/header');
                    $this->load->view('pages/formResetPass', $data);
                    $this->load->view('templates/footer');
                }
            } else {
                $data["errors"] = '<div class="alert alert-danger mt-3">Codigo inválido!</div>';

                $this->load->view('templates/header');
                $this->load->view('pages/formResetPass', $data);
                $this->load->view('templates/footer');
            }
        }
    }
}
