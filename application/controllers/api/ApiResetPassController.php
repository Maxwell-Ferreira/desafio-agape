<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiResetPassController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('usuario');
        $this->load->model('recuperarsenha');
        $postData = json_decode(file_get_contents('php://input'), true);
		$_POST = $postData;
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
        $this->form_validation->set_error_delimiters('', '');

        if (!$this->form_validation->run()) {
            $data['errors'] = array(
                "email" => form_error('email'),
            );
            $data["status"] = false;

            http_response_code(401);
            die(json_encode($data));
        } else {

            $usuario = $this->usuario->findByEmail($this->input->post('email'));

            if (!$usuario) {
                $data["error"] = 'Email não registrado!';
                $data["status"] = false;

                http_response_code(401);
                die(json_encode($data));
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
                    $data["errors"] = $this->email->print_debugger();
                    $data["status"] = false;

                    http_response_code(401);
                    die(json_encode($data));
                } else {
                    $data["status"] = true;

                    http_response_code(200);
                    die(json_encode($data));
                }
            }
        }
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
            $data["status"] = false;
            $data['errors'] = array(
                "senha" => form_error('senha'),
                "confSenha" => form_error('confSenha'),
            );

            http_response_code(401);
            die(json_encode($data));
        } else {
            $validationHash = $this->uri->segment(3);

            $alterarSenha = $this->recuperarsenha->findByHash($validationHash);

            if ($alterarSenha) {
                $usuario = $this->usuario->find($alterarSenha->usuario_id);

                if ($usuario) {
                    $senha = $this->input->post('senha');
                    $this->usuario->alterarSenha(md5($senha), $usuario->id);

                    $data['status'] = true;

                    http_response_code(200);
                    die(json_encode($data));
                } else {
                    $data["error"] = 'Usuário não existe!';
                    $data['status'] = false;

                    http_response_code(401);
                    die(json_encode($data));
                }
            } else {
                $data["error"] = 'Código inválido!';
                $data['status'] = false;

                http_response_code(401);
                die(json_encode($data));
            }
        }
    }
}
