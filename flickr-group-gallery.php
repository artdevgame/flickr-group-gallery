<?php
/**
 * Plugin Name: Flickr Group Gallery
 * Plugin URI: http://mikeholloway.co.uk
 * Description: Generate a gallery from a Flickr group, optionally limited by criteria.
 * Version: 1.0.0
 * Author: Mike Holloway
 * Author URI: http://mikeholloway.co.uk
 * License: MIT
 */

require_once __DIR__ . '/vendor/autoload.php';

class FlickrGroupGallery
{
    /**
     * Stores the API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Stores the API secret.
     *
     * @var string
     */
    protected $apiSecret;

    /**
     * Stores the length of time in seconds that the cache will expire after.
     *
     * @var int
     */
    protected $cacheExpiresAfter;

    /**
     * Stores the cache path.
     *
     * @var string
     */
    protected $cachePath;

    /**
     * Stores the API client used to talk to Flickr.
     *
     * @var phpFlickr
     */
    protected $client;

    /**
     * Public interface.
     *
     * @return self
     */
    static public function init()
    {
        $self = new self();
        return $self;
    }


    /**
     * Initialise object.
     */
    public function __construct()
    {
        add_shortcode('flickr_group', array($this, 'parse'));
        add_action('wp_enqueue_scripts', array($this, 'attachAssets'));

        $settings = new \FlickrGroupGallery\Settings;

        $this->apiKey = $settings->get('flickr_group_gallery_api_key');
        $this->apiSecret = $settings->get('flickr_group_gallery_api_secret');
        $this->cachePath = $settings->get('flickr_group_gallery_cache_path', '/tmp/DPZ_Flickr');
        $this->cacheExpiresAfter = $settings->get('flickr_group_gallery_cache_expires', 0);
    }

    /**
     * Attach assets required by the plugin.
     *
     * @return void
     */
    public function attachAssets()
    {
    }

    /**
     * Parse for shortcodes.
     *
     * @param array $attributes Attributes associated to the shortcode.
     *
     * @return string Replacement string.
     */
    public function parse($attributes)
    {
        $response = $this->getClient()->call('flickr.groups.pools.getPhotos',
            ['group_id' => $attributes['id']]);

        if (null === $response) {
            return;
        }

        $photos = $response['photos']['photo'];

        if (!empty($photos) && isset($attributes['tags']) && !empty($attributes['tags'])) {
            $tags = array_flip(explode(',', $attributes['tags']));

            // the flickr api only supports one tag on the pools.getPhotos call
            // (according to docs), so grab all tags from the photo in question
            // and filter on them
            $photos = array_filter($photos, function ($photo) use ($tags) {
                $allTags = $this->getTagsForPhotoWithId($photo['id']);
                foreach ($allTags as $tag) {
                    if (isset($tags[$tag])) {
                        return true;
                    }
                }
                return false;
            });


        }

        ob_start();
        include_once __DIR__ . '/lib/templates/front.php';
        return ob_get_clean();
    }

    /**
     * Fetch all tags for a photo.
     *
     * @param int $photoId Photo Id.
     *
     * @return array
     */
    public function getTagsForPhotoWithId($photoId)
    {
        $response = $this->getClient()->call('flickr.tags.getListPhoto',
            ['photo_id' => $photoId]);

        $tags = array();

        if (!empty($response)) {
            foreach ($response['photo']['tags']['tag'] as $tag) {
                $tags[] = $tag['raw'];
            }
        }

        return $tags;
    }

    /**
     * Get all sizes of a photo for a specified id.
     *
     * @param int $photoId Photo Id.
     *
     * @return array
     */
    public function getPhotoWithId($photoId)
    {
        return $this->getClient()->call('flickr.photos.getSizes',
            ['photo_id' => $photoId]);
    }

    /**
     * Get the url to embed a photo.
     *
     * @see https://www.flickr.com/services/api/misc.urls.html
     *
     * @param array  $photo A photo, as described by groups.pools.getPhotos.
     * @param string $size  A char that represents the size of photo required.
     *
     * @return string
     */
    public function getEmbedUrlForPhoto(array $photo, $size = null)
    {
        $url = '//farm' . $photo['farm'] . '.staticflickr.com/'
            . $photo['server'] . '/' . $photo['id'] . '_' . $photo['secret'];

        if (null !== $size && preg_match('/[sqtmn-zcbhko]/', $size)) {
            $url .= '_' . $size;
        }

        $url .= '.jpg';
        return $url;
    }

    /**
     * Get the source url of a photo.
     *
     * @param array $photo A photo, as described by groups.pools.getPhotos.
     *
     * @return string
     */
    public function getSourceUrlForPhoto(array $photo)
    {
        return '//www.flickr.com/photos/' . $photo['owner'] . '/' . $photo['id'];
    }

    /**
     * Get the api client used to talk to Flickr.
     *
     * @return phpFlickr
     */
    public function getClient()
    {
        if (null === $this->client) {
            $options = array(
                'cachePath' => $this->cachePath,
                'cacheExpiresAfter' => $this->cacheExpiresAfter,
            );

            $this->client = new \DPZ\Flickr($this->apiKey, $this->apiSecret, null, $options);
        }

        return $this->client;
    }
}

FlickrGroupGallery::init();