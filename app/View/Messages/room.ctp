<form name="messageSendForm" method="POST" action="/151a/messages/add/<?php echo $groupId?>">
<textarea name="message" rows="4" cols="40"></textarea>
<input type="hidden" name="sendUser" value="<?php echo "d8e6aafe6958e5aa186b2a6fbbeed333"; ?>">
<br>
<input type="submit" class="button-primary" value="send">
</form>
<?php if(!$error):?>
	<?php foreach ($messageList as $ms): ?>
			<div class="userName"><?php echo $ms['User']['user_name']; ?></div>
			<div class="message"><?php echo str_replace("\r\n","<br>",$ms['Message']['message']);?></div>
			<hr />
	<?php endforeach;?>
<?php else : ?>
	<h3>エラーが発生しました。</h3>
	<p><?php echo $errorMessage ;?></p>
<?php endif;?>