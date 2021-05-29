<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UsuarioController extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		isAuthenticated();
		$this->load->model('usuario');
	}

	public function index()
	{
		$config = array(
			"base_url" => base_url('usuarios'),
			"per_page" => 5,
			"num_links" => 5,
			"uri_segment" => 2,
			"total_rows" => $this->usuario->countAll(),
			"full_tag_open" => '<nav><ul class="pagination justify-content-center">',
			"full_tag_close" => '</ul></nav>',

			"next_tag_open" => "<li class='page-link'>",
			"next_tag_close" => "</li>",
			"next_link" => "Proximo",

			"prev_tag_open" => "<li class='page-link'>",
			"prev_tag_close" => "</li>",
			"prev_link" => "Anterior",

			"num_tag_open" => "<li class='page-link'>",
			"num_tag_close" => "</li>",

			"cur_tag_open" => "<li class='page-link link-active'>",
			"cur_tag_close" => "</li>",

		);

		$this->load->library('pagination');
		$this->pagination->initialize($config);

		$data["pagination"] = $this->pagination->create_links();

		$offset = $this->uri->segment(2) ? $this->uri->segment(2) : 0;
		$data['usuarios'] = $this->usuario->allWithPagination($config["per_page"], $offset);

		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('pages/usuarios', $data);
		$this->load->view('templates/footer');
	}

	public function create()
	{
		isAdmin();
		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('pages/formusuario');
		$this->load->view('templates/footer');
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

			foreach ($this->input->post() as $key => $value) {
				$data["form"][$key] = $value;
			}

			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('pages/formusuario', $data);
			$this->load->view('templates/footer');
		} else {
			$request = $this->input->post();

			$this->usuario->create($request);
			redirect('usuarios');
		}
	}

	public function show($id)
	{
		isAdmin();
		$usuario = $this->usuario->find($id);

		if (empty($usuario)) {
			redirect('usuarios');
		}

		$data['isEdit'] = true;
		$data['id'] = $usuario->id;

		foreach ($usuario as $key => $value) {
			$data['form'][$key] = $value;
		}

		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('pages/formusuario', $data);
		$this->load->view('templates/footer');
	}

	public function update($id)
	{
		isAdmin();
		if (!$this->formValidator()) {

			$data['isEdit'] = true;
			$data['id'] = $id;

			$data['errors'] = array(
				"nome" => form_error('nome'),
				"email" => form_error('email'),
				"login" => form_error('login'),
				"senha" => form_error('senha'),
				"status" => form_error('status'),
				"admin" => form_error('admin'),
			);

			foreach ($this->input->post() as $key => $value) {
				$data["form"][$key] = $value;
			}

			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('pages/formusuario', $data);
			$this->load->view('templates/footer');
		} else {
			$request = $this->input->post();
			$this->usuario->update($request, $id);

			redirect('usuarios');
		}
	}

	public function destroy($id)
	{
		isAdmin();
		$this->usuario->delete($id);
		redirect('usuarios');
	}

	public function myData()
	{
		$data['isEditLogged'] = true;
		$usuario = $this->session->userData('loggedUser');
		$data['id'] = $usuario->id;

		foreach($usuario as $key => $value){
			$data['form'][$key] = $value;
		}

		$this->load->view('templates/header');
		$this->load->view('templates/menu');
		$this->load->view('pages/formusuario', $data);
		$this->load->view('templates/footer');
	}

	public function updateMyData()
	{
		if (!$this->formValidator()) {

			$data['isEditLogged'] = true;
			$data['id'] = $this->session->userData('loggedUser')->id;

			$data['errors'] = array(
				"nome" => form_error('nome'),
				"email" => form_error('email'),
				"login" => form_error('login'),
				"senha" => form_error('senha'),
				"status" => form_error('status'),
				"admin" => form_error('admin'),
			);

			foreach ($this->input->post() as $key => $value) {
				$data["form"][$key] = $value;
			}

			$this->load->view('templates/header');
			$this->load->view('templates/menu');
			$this->load->view('pages/formusuario', $data);
			$this->load->view('templates/footer');
		} else {
			$request = $this->input->post();
			$this->usuario->update($request, $this->session->userData('loggedUser')->id);

			redirect('usuarios');
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
			$this->session->userdata("loggedUser")->admin ? 'required|max_length[1]' : 'max_length[1]',
			array(
				"required" => "O campo %s é obrigatório",
				"max_length" => "O campo %s não pode ter mais que 1 caracteres!"
			)
		);
		$this->form_validation->set_rules(
			'admin',
			'Permissão',
			$this->session->userdata("loggedUser")->admin ? 'required|max_length[1]' : 'max_length[1]',
			array(
				"required" => "O campo %s é obrigatório",
				"max_length" => "O campo %s não pode ter mais que 1 caracteres!"
			)
		);

		return $this->form_validation->run();
	}

	public function login_check($login){
        
        $id = $this->uri->segment(3) ? $this->uri->segment(3) : $this->session->userData("loggedUser")->id;
        
        $user = $this->usuario->find($id);

        if($user){
            if($user->login === $login){
                return true;
            }else{
				$possibleUser = $this->usuario->findByLogin($login);

				if($possibleUser){
					$this->form_validation->set_message('login_check', 'Este login já está sendo utilizado por outro usuário!');
					return false;
				}else{
					return true;
				}
                
            }
        }
    }

	public function email_check($email){

        $id = $this->uri->segment(3) ? $this->uri->segment(3) : $this->session->userData("loggedUser")->id;
        
        $user = $this->usuario->find($id);

        if($user){
            if($user->email === $email){
                return true;
            }else{
                $possibleUser = $this->usuario->findByEmail($email);

				if($possibleUser){
					$this->form_validation->set_message('email_check', 'Este Email já está sendo utilizado por outro usuário!');
					return false;
				}else{
					return true;
				}
                
            }
        }

    }
}
