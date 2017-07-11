<?php

namespace App\controllers;
use \App\core\Controller;
use \App\models\User;
use \App\models\Task;
use \App\helpers\Redirect;
use \App\helpers\Input;
use \App\helpers\Session;
use \App\helpers\Config;
use \App\helpers\Validator;

class TaskController extends Controller {
	private $user;
	
	public function __construct() {
		parent::__construct();
		$this->user = new User;
		if (!$this->user->isLoggedIn()) {
			Redirect::to('/login');
		}
	}

	public function indexAction() {
		$task = new Task;
		$tasks = $task->getTasksByUserId(Session::get(Config::get('session/userId')));
		if (Input::exists('add')) {
			$val = new Validator($_POST);
			$val->addField('task')->addRules('require|min:3|max:140');
			if ($val->check()) {
				$task->registerNewTask(Input::get('task', true), Session::get(Config::get('session/userId')));
				Redirect::to('/');
			} else {
				$this->view->data('errors', $val->getErrors());
			}
		}
		return $this->view->render('main', 'index', ['tasks' => $tasks]);
	}

	public function doneAction($id) {
		$taskModel = new Task;
		$task = $taskModel->getTaskById($id);
		if (!empty($task) && $this->user->data()->id == $task->author_id) {
			if ($task->status) {
				$taskModel->undoneById($id);
			} else {
				$taskModel->doneById($id);
			}
		} else {
			Redirect::to('/', 'Don\'t do this anymore!', 'danger');
		}
		Redirect::to('/');
	}

	public function deleteAction($id) {
		$taskModel = new Task;
		$task = $taskModel->getTaskById($id);
		if ($this->user->data()->id == $task->author_id) {
			$taskModel->deleteById($id);
		}  else {
			Redirect::to('/', 'You can\'t do everything you want :)', 'danger');
		}
		Redirect::to('/');
	}
}