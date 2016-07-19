<?php if (count($photos)): ?>
<div class="flickr-group-gallery">
    <?php foreach ($photos as $photo): ?>
        <a href="<?php echo $this->getEmbedUrlForPhoto($photo, 'b'); ?>" data-orig="<?php echo $this->getSourceUrlForPhoto($photo); ?>" target="_blank"
        	><img src="<?php echo $this->getEmbedUrlForPhoto($photo, 'n'); ?>" alt="<?php echo $photo['title']; ?>" /></a>
    <?php endforeach; ?>
</div>
<?php endif; ?>