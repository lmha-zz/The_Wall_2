<?php
require_once('process.php');
require_once('connection.php');

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
	$_SESSION['login_errors'][] = "Please log in.";
	header('Location: /');
	die;
}

if(isset($_SESSION['msg_id'])) {
	$content = read_message($_SESSION['msg_id']);
}
if(isset($_SESSION['comment_id'])) {
	$content = read_comment($_SESSION['comment_id']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CodingDojo Wall</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<div id="body_wrapper">
		<div id="content_wrapper">
			<h3>Edit your <?= $_SESSION['type'] ?></h3>
			<?php
			if(isset($_SESSION['edit_error'])) {
				echo "<p class='error'>".$_SESSION['edit_error']."</p>";
				unset($_SESSION['edit_error']);
			}
			?>
		
			<form id="edit_message_form" action="process.php" method="post">
				<input type="hidden" name="type" value="<?= $_SESSION['type'] ?>">
				<input type="hidden" name="id" value="<?= $content['id'] ?>">
				<textarea name="updated_content"><?= $content['content'] ?></textarea>
				<input type="submit" name="cancel" value="Cancel Edit">
				<input type="submit" name="confirm_edit" value="Confirm Edit">
			</form>
		</div>
	</div>

</body>
</html>