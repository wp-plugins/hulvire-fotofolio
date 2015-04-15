<div class="wrap">
    <h2><img src="<?php echo plugin_dir_url( __FILE__ ). '../images/fotofolio-icon-a.png'; ?>" /fotofolio> Hulvire Fotofolio</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('wp_hulvire_fotofolio-group'); ?>
        <?php @do_settings_fields('wp_hulvire_fotofolio-group'); ?>

        <?php do_settings_sections('wp_hulvire_fotofolio'); ?>

        <?php @submit_button(); ?>
    </form>
</div>