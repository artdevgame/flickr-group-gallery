<?php if (count($photos)): ?>
<div class="flickr-group-gallery">
    <?php foreach ($photos as $photo): ?>
        <a href="<?php echo $this->getSourceUrlForPhoto($photo); ?>" target="_blank"><img src="<?php echo $this->getEmbedUrlForPhoto($photo, '-'); ?>" alt="<?php echo $photo['title']; ?>" /></a>
    <?php endforeach; ?>
</div>
<script>
    jQuery('.flickr-group-gallery').justifiedGallery({
        lastRow: 'justify',
        randomize: true,
        margins: 5
    });
</script>
<?php endif; ?>