<section>
	<div class="container">
		<div class="col-md-10 col-md-offset-1">
		<?= App\helpers\Alert::getFlash(); ?>
			<h2 class="text-center" style="margin-bottom: 30px">TODO List</h2>
			<?php if (!empty($tasks)): ?>
				<table class="table">
					<thead>
						<tr>
							<td class="col-md-10">Task</td></td>
							<td class="col-md-2">Action</td></td>
						</tr>
					</thead>
					<?php foreach ($tasks as $task): ?>
						<?php if($task->status): ?>
							<td><strike><?=$task->name;?><strike></td></td>
							<td><a href="task/done/<?=$task->id;?>">Undone</a> / <a href="task/delete/<?=$task->id;?>">X</a></td>
						<?php else: ?>
							<td><?=$task->name;?></td>
							<td><a href="task/done/<?=$task->id;?>">Done</a></td>
						<?php endif; ?>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<p style="text-align: center">Not any tasks yet</p>
			<?php endif; ?>
		</table>
		<?php if((isset($errors))): ?>
			<p style="color: red; text-align: center"><?=$errors['task'];?></p>
		<?php endif; ?>
		<form class="form-inline" action="/" method="post"  style="text-align: center">
			<div class="form-group">
				<input name="task" type="text" class="form-control">
				<input type="submit" name="add" class="btn btn-primary" value="New task">
			</div>
		</form>
	</div>
</div>
</section>