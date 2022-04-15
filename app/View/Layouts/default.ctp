<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>151a:<?php echo $this->fetch('title'); ?></title>
	<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->script('common');
		//echo $this->Html->css('cake.generic');
		echo $this->Html->css('style.comm');
		echo $this->Html->css('normalize');
		echo $this->Html->css('skeleton');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

</head>
<body>
	<div id="header">
		<h1 class="logo"><a href="/151a">151a</a></h1>
	</div>
	<hr>
	<div id="content">
		<?php echo $this->Flash->render(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>
	<hr>
	<div id="footer">
		<p>
			<?php echo "151a" ?>
		</p>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
