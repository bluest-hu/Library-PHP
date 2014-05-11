<?php 

include("user.class.php")


class Admin extends User {

	private $sql = null;
	
	protected function get_sql() {
		if ($sql == null) {

		}
	}


	public function delete_user($user_id) {
		$query = "DELETE 
			FROM user
			WHERE ID = $user_id";
	}



}


?>