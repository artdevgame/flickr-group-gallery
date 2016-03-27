<form method="post" action="/wp-admin/options.php">
    <?php settings_fields('flickr_group_gallery_settings'); ?>
    <?php do_settings_sections('flickr_group_gallery_settings'); ?>

    <h2>API</h2>
    <p>Get your credentials here:<br />https://www.flickr.com/services/apps/by/[username]</p>

    <table class="form-table">
        <tr valign="top">
            <th scope="row">Key</th>
            <td><input type="text" name="flickr_group_gallery_api_key" value="<?php echo esc_attr( get_option('flickr_group_gallery_api_key') ); ?>" size="50" /></td>
        </tr>

        <tr valign="top">
            <th scope="row">Secret</th>
            <td><input type="text" name="flickr_group_gallery_api_secret" value="<?php echo esc_attr( get_option('flickr_group_gallery_api_secret') ); ?>" size="50" /></td>
        </tr>
    </table>

    <h2>Cache</h2>
    <p>Cache responses to the following place:</p>

    <table class="form-table">
        <tr valign="top">
            <th scope="row">Path</th>
            <td><input type="text" name="flickr_group_gallery_cache_path" value="<?php echo esc_attr( get_option('flickr_group_gallery_cache_path', '/tmp') ); ?>" size="50" /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Expires</th>
            <td><input type="text" name="flickr_group_gallery_cache_expires" value="<?php echo esc_attr( get_option('flickr_group_gallery_cache_expires', 0) ); ?>" size="10" /></td>
        </tr>
    </table>

    <?php submit_button(); ?>

    <h2>Usage</h2>
    <p>
        Embed the following into your theme where you want the gallery to appear:<br />
        [flickr_group id="YOUR_FLICKR_GROUP_ID" tags="CSV_OF_TAGS_TO_FILTER_BY(optional)"]
    </p>
</form>