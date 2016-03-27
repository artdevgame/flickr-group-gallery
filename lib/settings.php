<?php

namespace FlickrGroupGallery;

require_once __DIR__ . '/../vendor/autoload.php';

class Settings
{
    /**
     * Initialise object.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'initMenu'));
        add_action('admin_init', array($this, 'initSettings'));
    }

    /**
     * Add an item to the admin menu.
     *
     * @return void
     */
    public function initMenu()
    {
        add_menu_page(
            'Flickr Group Gallery',
            'Flickr Group Gallery',
            'administrator',
            'flickr-group-gallery',
            array($this, 'render'),
            'dashicons-admin-generic'
        );
    }

    /**
     * Set up the settings we wish to capture data for.
     *
     * @return void
     */
    public function initSettings()
    {
        register_setting('flickr_group_gallery_settings', 'flickr_group_gallery_api_key');
        register_setting('flickr_group_gallery_settings', 'flickr_group_gallery_api_secret');
        register_setting('flickr_group_gallery_settings', 'flickr_group_gallery_cache_path');
        register_setting('flickr_group_gallery_settings', 'flickr_group_gallery_cache_expires');
    }

    /**
     * Render the settings page.
     *
     * @return string
     */
    public function render()
    {
        include_once __DIR__ . '/templates/back.php';
    }

    /**
     * Retrieve a value from storage.
     *
     * @param string $key     Key name.
     * @param mixed  $default Default value.
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return get_option($key, $default);
    }
}