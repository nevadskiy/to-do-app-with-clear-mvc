<?php

namespace App\models;
use \App\core\Model;

class Task extends Model {
	public function getTasksByUserId($id) {
		return $this->db->featQuery('ORDER BY id DESC')->getAll('tasks', ['author_id' => $id]);
	}
	public function registerNewTask($task, $authorId) {
		if (!$this->db->insert('tasks', ['name' => $task, 'author_id' => $authorId])) {
			throw new Exception('Task wasnt created!');
		}
	}
	public function getTaskById($id) {
		return $this->db->get('tasks', ['id' => $id]);
	}
	public function deleteById($id) {
		return $this->db->delete('tasks', ['id' => $id]);
	}
	public function doneById($id) {
		return $this->db->update('tasks', ['status' => 1], ['id' => $id]);
	}
	public function undoneById($id) {
		return $this->db->update('tasks', ['status' => 0], ['id' => $id]);	
	}
}