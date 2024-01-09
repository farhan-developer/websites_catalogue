<div class="wc-website-form-container">

    <form id="wc-website-form">
    
        <label for="name"><?php _e('Name:', 'websites_catalog'); ?></label>
        <input type="text" name="user_name" id="user_name" required>

        <label for="url"><?php _e('Website URL:', 'websites_catalog'); ?></label>
        <input type="url" name="site_url" id="site_url" required>

        <input type="button" value="<?php _e('Submit', 'websites_catalog'); ?>" name="wc_website_submit" id="wc_website_submit">

        <p id="wc_submission_message"></p>
    
    </form>

</div>
