<?php
session_start();
require('crud_functions.php');


if(isset($_POST['register'])) {
	foreach ($_POST as $key => $value) {
		if(empty($value)) {
			$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." is a required field.";
		} else {
			switch ($key) {
				case 'first_name':
				case 'last_name':
					if(!ctype_alpha($value)) {
						$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." can only contain alphabetic characters.";
					}
					break;
				case 'email':
					if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
						$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." must be a valid email.";
					} else {
						$users = read_users();
						for ($i=0; $i < count($users); $i++) { 
							if($value == $users[$i]['email']) {
								$_SESSION['register_errors'][] = "The email, ".$value.", has already been registered to another user.";
								break;
							}
						}
					}
					break;
				case 'password':
					if(strlen($value) < 6) {
						$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." must be at least six characters long.";
					}
					break;
				case 'password_confirmation':
					if(strlen($value) < 6) {
						$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." must be at least six characters long.";
					} else if($value != $_POST['password']) {
						$_SESSION['register_errors'][] = ucwords(str_replace('_', ' ',$key))." must must match Password.";
					}
					break;
			}
		}
	}
	if(isset($_SESSION['register_errors'])) {
		header("Location: index.php");
		die;
	} else {
		create_user($_POST);
		$_SESSION['register_success'] = "Congratulations, you have successfully registered with us!";
		header("Location: index.php");
		die;
	}
}

if(isset($_POST['login'])) {
	foreach ($_POST as $key => $value) {
		if(empty($value)) {
			$_SESSION['login_errors'][] = ucwords(str_replace('_', ' ',$key))." is a required field.";
		} else {
			switch ($key) {
				case 'email':
					if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
						$_SESSION['login_errors'][] = ucwords(str_replace('_', ' ',$key))." must be a valid email.";
					}
					break;
				case 'password':
					if(strlen($value) < 6) {
						$_SESSION['login_errors'][] = ucwords(str_replace('_', ' ',$key))." must be at least six characters long.";
					}
					break;
			}
		}
	}
	
	if(isset($_SESSION['login_errors'])) {
		header("Location: index.php");
		die;
	} else {
		$user = read_user($_POST['email']);
		if(count($user) > 0) {
			$encPW = md5(escape_this_string($_POST['password']));
			if($encPW == $user['password']) {
				$_SESSION['name'] = $user['first_name'].' '.$user['last_name'];
				$_SESSION['user_id'] = $user['id'];
				$_SESSION['logged_in'] = true;
				header("Location: main.php");
				die;
			} else {
				$_SESSION['login_errors'][] = "The password you have entered does not match what is on record.";
				header("Location: index.php");
				die;
			}
		} else {
			$_SESSION['login_errors'][] = "A user with those credentials does not exist in our system.";
			header("Location: index.php");
			die;
		}
	}
}

if(isset($_POST['create'])) {
	if($_POST['create'] ==  "Post a message") {
		if(empty($_POST['message'])) {
			$_SESSION['main_errors'][] = "Cannot post a blank message.";
		} else {
			create_message($_POST['message'], $_SESSION['user_id']);
		}
	}
	if($_POST['create'] ==  "Post a Comment") {
		if(empty($_POST['comment'])) {
			$_SESSION['main_errors'][] = "Cannot post a blank comment.";
		} else {
			create_comment($_POST['comment'], $_SESSION['user_id'], $_POST['msg_id']);
		}
	}
	header("Location: main.php");
	die;
}

if(isset($_POST['update'])) {
	if($_POST['update'] == "Edit Message") {
		$_SESSION['type'] = 'message';
		$_SESSION['msg_id'] = $_POST['msg_id'];
	}
	if($_POST['update'] == "Edit Comment") {
		$_SESSION['type'] = 'comment';
		$_SESSION['comment_id'] = $_POST['comment_id'];
	}
	header('Location: update_view.php');
	die;
} 

if(isset($_POST['confirm_edit'])) {
	if($_POST['type'] == 'message') {
		if(empty($_POST['updated_content'])) {
			$_SESSION['edit_error'] = "Cannot submit an empty message. Please fill in the message content and try again.";
		} else {
			update_message($_POST['id'], $_POST['updated_content']);
			unset($_SESSION['msg_id']);
		}
	}
	if($_POST['type'] == 'comment') {
		if(empty($_POST['updated_content'])) {
			$_SESSION['edit_error'] = "Cannot submit an empty comment. Please fill in the comment content and try again.";
		} else {
			update_comment($_POST['id'], $_POST['updated_content']);
			unset($_SESSION['comment_id']);
		}
	}
	if(isset($_SESSION['edit_error'])) {
		header('Location: update_view.php');
		die;
	} else {
		header('Location: main.php');
		die;
	}
}

if(isset($_POST['cancel'])) {
	if(isset($_POST['msg_id'])) {
		unset($_SESSION['msg_id']);
	}
	if(isset($_POST['comment_id'])) {
		unset($_SESSION['comment_id']);
	}
	header('Location: main.php');
	die;
}

if(isset($_POST['delete'])) {
	if($_POST['delete'] == "Delete Message") {
		delete_message($_POST['msg_id']);
		$_SESSION['main_success'] = "You have successfully deleted your message.";
		header("Location: main.php");
		die;
	}
	if($_POST['delete'] == "Delete Comment") {
		delete_comment($_POST['comment_id']);
		$_SESSION['main_success'] = "You have successfully deleted your comment.";
		header("Location: main.php");
		die;
	}
}

if(isset($_POST['logoff'])) {
	session_destroy();
	header('Location: index.php');
	die;
}

?>