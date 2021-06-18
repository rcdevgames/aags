<div id="popup-character-images">
	<?php foreach ($images as $image): ?>
		<a class="image" data-id="<?php echo $image->id ?>">
			<img src="<?php echo image_url($image->profile_image(true)) ?>">
		</a>
	<?php endforeach ?>
	<div class="break"></div>
</div>