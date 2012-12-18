			<div id="nav">
				Список: &nbsp;&nbsp;
				<a href="/">по дате</a>&nbsp;&nbsp; | &nbsp;&nbsp;
				<a href="/bytag">по меткам</a>
			</div>

			<h1>Графика</h1>
			<div style="text-align: left; margin: 1em 0;"><a href="/" title="Вернуться к списку">Вернуться к списку</a></div>

			<div class="year">
				<h2><?php echo $tag; ?></h2>
			</div>

			<?php $i = 0; ?>
			<?php foreach ($pictures as $picture): ?>

				<?php if ($i == 4) { $i = 1; } else { $i++; }; ?>

				<?php if ($i == 1): ?>
					<div class="img-str">
						<div class="img-box-first">
                    		<a href="<?php echo FILE_PATH_URL; ?>pictures/x/<?php echo $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>"><img src="<?php echo FILE_PATH_URL; ?>pictures/xgray/<?php echo $picture['image']; ?>-gray.jpg" id="<?php echo $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>" /></a>
                		</div>
				<?php elseif ($i == 4): ?>
						<div class="img-box-last">
                    		<a href="<?php echo FILE_PATH_URL; ?>pictures/x/<?php echo $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>"><img src="<?php echo FILE_PATH_URL; ?>pictures/xgray/<?php echo $picture['image']; ?>-gray.jpg" id="<?php echo $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>" /></a>
                		</div>
					</div>
				<?php else: ?>
						<div class="img-box">
                    		<a href="<?php echo FILE_PATH_URL; ?>pictures/x/<?php echo $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>"><img src="<?php echo FILE_PATH_URL; ?>pictures/xgray/<?php echo $picture['image']; ?>-gray.jpg" id="<?php echo $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>" /></a>
                		</div>
				<?php endif; ?>

			<?php endforeach; ?>

			<?php if ($i != 4): ?>
				</div>
			<?php endif; ?>
			<div style="text-align: left; margin: 1em 0;"><a href="/" title="Вернуться к списку">Вернуться к списку</a></div>