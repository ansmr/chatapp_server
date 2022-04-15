<ul>
	<?php foreach ($userLists as $userlist): ?>
		<li><a
		href="/151a/messages/room/<?php echo $userlist['UserGroup']['group_id'];?>">
		<?php echo $userlist['Group']['group_name'];?></a>
		</li>
	<?php endforeach;?>
</ul>