<?php
require_once("connection.php");

function create_user($post) {
	$escFName = escape_this_string($post['first_name']);
	$escLName = escape_this_string($post['last_name']);
	$escEmail = escape_this_string($post['email']);
	$encPW = md5(escape_this_string($post['password']));
	$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES ('{$escFName}', '{$escLName}', '{$escEmail}', '{$encPW}', NOW(), NOW())";
	run_mysql_query($query);
}

function create_message($message, $user_id) {
	$escMessage = escape_this_string($message);
	$query = "INSERT INTO messages (user_id, message, created_at, updated_at) VALUES ({$user_id}, '{$escMessage}', NOW(), NOW());";
	run_mysql_query($query);
}

function create_comment($comment, $user_id, $msg_id) {
	$escComment = escape_this_string($comment);
	$query = "INSERT INTO comments (message_id, user_id, comment, created_at, updated_at) VALUES ({$msg_id}, {$user_id}, '{$comment}',NOW(), NOW())";
	run_mysql_query($query);
}

function read_user($email) {
	$escEmail = escape_this_string($email);
	$query = "SELECT * FROM users WHERE email = '{$escEmail}'";
	return fetch_record($query);
}

function read_message($msg_id) {
	$query = "SELECT *, message AS content FROM messages WHERE messages.id = {$msg_id}";
	return fetch_record($query);
}

function read_messages() {
	$query = "SELECT messages.id AS msg_id, messages.user_id AS author_id, messages.message, messages.created_at, messages.updated_at, users.first_name, users.last_name  FROM messages JOIN users ON messages.user_id = users.id ORDER BY messages.created_at DESC";
	return fetch_all($query);
}
function read_comment($comment_id) {
	$query = "SELECT *, comment AS content FROM comments WHERE comments.id = {$comment_id}";
	return fetch_record($query);
}

function read_comments($msg_id) {
	$query = "SELECT comments.comment, CONCAT(users.first_name,' ',users.last_name) AS author, comments.created_at, comments.user_id AS author_id, comments.id AS comment_id, comments.message_id AS msg_id FROM comments JOIN users ON comments.user_id = users.id JOIN messages on comments.message_id = messages.id WHERE comments.message_id = $msg_id ORDER BY comments.created_at ASC";
	return fetch_all($query);
}

function update_message($msg_id, $message) {
	$escMsg = escape_this_string($message);
	$query = "UPDATE messages SET message = '{$escMsg}', updated_at = NOW() WHERE messages.id = {$msg_id}";
	run_mysql_query($query);
}
function update_comment($comment_id, $comment) {
	$escComment = escape_this_string($comment);
	$query = "UPDATE comments SET comment = '{$escComment}', updated_at = NOW() WHERE comments.id = {$comment_id}";
	run_mysql_query($query);
}

function delete_message($msg_id) {
	$delete_comments = "DELETE FROM comments WHERE message_id = {$msg_id}";
	run_mysql_query($delete_comments);
	$delete_messages = "DELETE FROM messages WHERE messages.id = {$msg_id}";
	run_mysql_query($delete_messages);
}

function delete_comment($comment_id) {
	$query = "DELETE FROM comments WHERE comments.id = {$comment_id}";
	run_mysql_query($query);
}

?>