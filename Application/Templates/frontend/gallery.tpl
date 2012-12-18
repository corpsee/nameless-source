			<div id="nav">
				<span>Список: &nbsp;&nbsp; по дате</span>&nbsp;&nbsp; | &nbsp;&nbsp;
				<a href="/bytag">по меткам</a>
			</div>

			<h1>Графика</h1>

			<div id="tags">
				<?php shuffle($tags); ?>

				<?php foreach ($tags as $tag): ?>
					<?php if($tag['class'] == 'tag0'): ?>
						<span class="tag <?php echo $tag['class']; ?>"><?php echo $tag['tag']; ?></span>
					<?php else: ?>
						<a class="tag <?php echo $tag['class']; ?>" href="/onetag/<?php echo $tag['tag']; ?>"><?php echo $tag['tag']; ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<?php foreach ($pictures as $key => $value): ?>

				<div class="year">
					<h2><?php echo $key; ?></h2>
				</div>

				<?php $i = 0; ?>
				<?php foreach ($value as $picture): ?>

					<?php if ($i == 4) { $i = 1; } else { $i++; }; ?>

					<?php if ($i == 1): ?>
						<div class="img-str">
							<div class="img-box-first">
                    			<a rel="lightbox[gallery]" href="<?php echo FILE_PATH_URL; ?>pictures/x/<?php echo $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>"><img src="<?php echo FILE_PATH_URL; ?>pictures/xgray/<?php echo $picture['image']; ?>-gray.jpg" id="<?php echo $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>" /></a>
							</div>
					<?php elseif ($i == 4): ?>
							<div class="img-box-last">
                    			<a rel="lightbox[gallery]" href="<?php echo FILE_PATH_URL; ?>pictures/x/<?php echo $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>"><img src="<?php echo FILE_PATH_URL; ?>pictures/xgray/<?php echo $picture['image']; ?>-gray.jpg" id="<?php echo $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>" /></a>
							</div>
						</div>
					<?php else: ?>
							<div class="img-box">
                    			<a rel="lightbox[gallery]" href="<?php echo FILE_PATH_URL; ?>pictures/x/<?php echo $picture['image']; ?>.jpg" class="pirobox_gr" title="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>"><img src="<?php echo FILE_PATH_URL; ?>pictures/xgray/<?php echo $picture['image']; ?>-gray.jpg" id="<?php echo $picture['image']; ?>" width="200" height="90" class="gr-col" alt="«<?php echo $picture['title']; ?>» / <?php echo $picture['create_date']; ?>" /></a>
							</div>
					<?php endif; ?>

				<?php endforeach; ?>

				<?php if ($i != 4): ?>
					</div>
				<?php endif; ?>

			<?php endforeach; ?>