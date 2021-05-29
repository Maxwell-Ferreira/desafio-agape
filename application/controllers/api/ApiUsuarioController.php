<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiUsuarioController extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('api_auth');
		isAuthenticatedApi();
		$this->load->model('usuario');

		$postData = json_decode(file_get_contents('php://input'), true);
		$_POST = $postData;
	}

	public function index()
	{

		$config = array(
			"base_url" => base_url('usuarios'),
			"per_page" => 5,
			"num_links" => 5,
			"uri_segment" => 3,
			"total_rows" => $this->usuario->countAll(),
		);

		$this->load->library('pagination');
		$this->pagination->initialize($config);

		$data["pagination"] = $this->pagination->create_links();

		$offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
		$data['usuarios'] = $this->usuario->allWithPagination($config["per_page"], $offset);

		http_response_code(200);
		die(json_encode($data));
	}

	public function store()
	{
		isAdmin();

		if (!$this->formValidator(true)) {
			$data['errors'] = array(
				"nome" => form_error('nome'),
				"email" => form_error('email'),
				"login" => form_error('login'),
				"senha" => form_error('senha'),
				"status" => form_error('status'),
				"admin" => form_error('admin'),
			);
			$data["status"] = false;

			http_response_code(401);
			die(json_encode($data));
		} else {
			$request = $this->input->post();
			$result = $this->usuario->create($request);

			$data['status'] = true;

			http_response_code(200);
			die(json_encode($data));
		}
	}

	public function show($id)
	{
		$usuario = $this->usuario->find($id);

		if (empty($usuario)) {

			$data['status'] = false;
			$data['error'] = "Nenhum usuário com esse ID cadastrado";

			http_response_code(400);
			die(json_encode($data));
		}

		$data['status'] = true;
		$data['usuario'] = $usuario;

		http_response_code(200);
		die(json_encode($data));
	}

	public function update($id)
	{
		isAdmin();

		if (!$this->formValidator()) {
			$data['status'] = false;
			$data['errors'] = array(
				"nome" => form_error('nome'),
				"email" => form_error('email'),
				"login" => form_error('login'),
				"senha" => form_error('senha'),
				"status" => form_error('status'),
				"admin" => form_error('admin'),
			);

			http_response_code(401);
			die(json_encode($data));
		} else {
			$this->usuario->update($this->input->post(), $id);
			$data['status'] = true;

			http_response_code(200);
			die(json_encode($data));
		}
	}

	public function destroy($id)
	{
		isAdmin();
		if ($this->usuario->find($id)) {
			$this->usuario->delete($id);
			$data["status"] = true;
			http_response_code(200);
		} else {
			$data["status"] = false;
			$data["error"] = "Usuário não existe!";
			http_response_code(400);
		}

		die(json_encode($data));
	}

	public function myData()
	{
		$data['usuario'] = $this->session->userData('loggedUser');

		http_response_code(200);
		die(json_encode($data));
	}

	public function updateMyData()
	{
		if (!$this->formValidator()) {

			$data['status'] = false;
			$data['errors'] = array(
				"nome" => form_error('nome'),
				"email" => form_error('email'),
				"login" => form_error('login'),
				"senha" => form_error('senha'),
				"status" => form_error('status'),
				"admin" => form_error('admin'),
			);
			http_response_code(401);
			die(json_encode($data));
		} else {
			$request = $this->input->post();
			$id = $this->session->userData('loggedUser')->id;
			$this->usuario->update($request, $id);

			$data["status"] = true;
			http_response_code(200);


			die(json_encode($data));
		}
	}

	private function formValidator($isCad = false)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules(
			'nome',
			'Nome',
			'required|max_length[60]',
			array(
				"required" => "O campo %s é obrigatório",
				"max_length" => "O campo %s não pode ter mais que 60 caracteres"
			)
		);
		$this->form_validation->set_rules(
			'email',
			'Email',
			$isCad ? 'required|valid_email|max_length[100]|is_unique[usuarios.email]'
				: 'required|valid_email|max_length[100]|callback_email_check',
			array(
				"required" => "O campo %s é obrigatório!",
				"max_length" => "O campo %s não pode ter mais que 100 caracteres!",
				"valid_email" => "Favor, insira um email válido!",
				"is_unique" => "Este email já está sendo utilizado!"
			)
		);
		$this->form_validation->set_rules(
			'login',
			'Login',
			$isCad ? 'required|max_length[20]|is_unique[usuarios.login]'
				: 'required|max_length[20]|callback_login_check',
			array(
				"required" => "O campo %s é obrigatório!",
				"max_length" => "O campo %s não pode ter mais que 20 caracteres!",
				"is_unique" => "Este login já está sendo utilizado!"
			)
		);
		$this->form_validation->set_rules(
			'senha',
			'Senha',
			$isCad ? 'max_length[30]|required' : 'max_length[30]',
			array(
				"max_length" => "O campo %s não pode ter mais que 30 caracteres!",
				"required" => "O campo %s é obrigatório!"
			)
		);
		$this->form_validation->set_rules(
			'status',
			'Status',
			'required|max_length[1]',
			array(
				"required" => "O campo %s é obrigatório",
				"max_length" => "O campo %s não pode ter mais que 1 caracteres!"
			)
		);
		$this->form_validation->set_rules(
			'admin',
			'Permissão',
			'required|max_length[1]',
			array(
				"required" => "O campo %s é obrigatório",
				"max_length" => "O campo %s não pode ter mais que 1 caracteres!"
			)
		);

		return $this->form_validation->run();
	}

	public function login_check($login)
	{

		$id = $this->uri->segment(4) ? $this->uri->segment(4) : $this->session->userData("loggedUser")->id;

		$user = $this->usuario->find($id);

		if ($user) {
			if ($user->login === $login) {
				return true;
			} else {
				$possibleUser = $this->usuario->findByLogin($login);

				if ($possibleUser) {
					$this->form_validation->set_message('login_check', 'Este login já está sendo utilizado por outro usuário!');
					return false;
				} else {
					return true;
				}
			}
		}
	}

	public function email_check($email)
	{

		$id = $this->uri->segment(4) ? $this->uri->segment(4) : $this->session->userData("loggedUser")->id;

		$user = $this->usuario->find($id);

		if ($user) {
			if ($user->email === $email) {
				return true;
			} else {
				$possibleUser = $this->usuario->findByEmail($email);

				if ($possibleUser) {
					$this->form_validation->set_message('email_check', 'Este Email já está sendo utilizado por outro usuário!');
					return false;
				} else {
					return true;
				}
			}
		}
	}
}
