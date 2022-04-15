<div class="one column"></div>
<div class="ten column">
<table>
	<?php foreach ($userLists as $list): ?>
		<tr>
			<td><?php echo $list['User']['id']; ?></td>
			<td><?php echo $list['User']['uniqid']; ?></td>
			<td><a href="/151a/user_groups/userList/<?php echo $list['User']['uniqid']; ?>"><?php echo $list['User']['user_name']; ?></td>
			<td><?php echo $list['User']['created']; ?></td>
			<td><?php echo $list['User']['modified']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>
</div>
<div class="one column"></div>
