<?php
require_once('process.php');
require_once('connection.php');

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
	$_SESSION['login_errors'][] = "Please log in.";
	header('Location: /');
	die;
}

$messages = read_messages();
$name = explode(' ',$_SESSION['name']);
// var_dump($messages[0]);

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
		<div id="header_wrapper">
			<h1>CodingDojo Wall</h1>
			<p class="welcome_msg">Welcome <?= ucfirst($name[0]) ?>!</p>
			<form action="process.php" method='post'>
				<input id="logoff_button" type="submit" name="logoff" value="Log Out">
			</form>
		</div>

		<div id="content_wrapper">
			<?php
			if(isset($_SESSION['main_success'])) {
				echo "<p class='success'>".$_SESSION['main_success']."</p>";
				unset($_SESSION['main_success']);
			}
			if(isset($_SESSION['main_errors'])) {
				foreach ($_SESSION['main_errors'] as $error) {
					echo "<p class='error'>".$error."</p>";
				}
				unset($_SESSION['main_errors']);
			}
			?>
			<div class="post_message_form_wrapper">
				<h3>Post a message</h3>
				<form action="process.php" method='post'>
					<textarea name="message"></textarea>
					<input id="post_msg_button" type="submit" name="create" value="Post a message">
				</form>
			</div>
			<div id="messages_wrapper">
				<div id="messages_container">
					<?php
						foreach ($messages as $index => $message) {
						?>
							<div class="message_box">
								<p class="author">Message by <?= ucwords($message['first_name']." ".$message['last_name'])." - ".date('M jS, Y \a\t h:i a', strtotime($message['created_at'])) ?></p>
								<p class="message_text"><?= $message['message'] ?></p>
								<?php
								if($_SESSION['user_id'] == $message['author_id']) {
									?>
									<form class="message_actions_form" action="process.php" method="post">
										<input type="hidden" name="msg_id" value="<?= $message['msg_id'] ?>">
										<input type="submit" name="delete" value="Delete Message">
										<input type="submit" name="update" value="Edit Message">
									</form>
									<?php
								}
								$comments = read_comments($message['msg_id']);
								foreach ($comments as $index => $comment) {
									?>
									<div class="comment_box">
										<p class="comment_text"><?= $comment['comment'] ?></p>
										<p class="comment_author">Comment by <?= $comment['author']." - ".date('M jS, Y \a\t h:i a', strtotime($comment['created_at'])) ?></p>
										<?php
											if($_SESSION['user_id'] == $comment['author_id']) {
												?>
												<form class="comment_actions_form" action="process.php" method="post">
													<input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
													<input type="submit" name="delete" value="Delete Comment">
													<input type="submit" name="update" value="Edit Comment">
												</form>
												<?php
											}
										?>
									</div>
									<?php
								}
								?>
								<div class="post_comment_form_wrapper">
									<h5>Post a comment</h5>
									<form action="process.php" method="post">
										<input type="hidden" name="msg_id" value="<?= $message['msg_id'] ?>">
										<textarea name="comment"></textarea>
										<input type="submit" name="create" value="Post a Comment">
									</form>
								</div>
							</div>
							<?php
						}
					?>
				</div>
			</div>
		</div>
	</div>

</body>
</html>