<?php
    /* Добавляем Модальное окно fancyBox 3 */   

    // wp_enqueue_script('fancybox_modals_js', get_template_directory_uri().'/assets/js/fancybox_modals.js', ['jquery']); 
    //    wp_enqueue_script('fancybox3_js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', ['jquery']);
    //    wp_enqueue_style('fancybox3_css3', "https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css");

   wp_enqueue_script('fancybox3_js', get_template_directory_uri() . '/assets/libs/fancybox/jquery.fancybox.min.js', ['jquery']);
   wp_enqueue_style('fancybox3_css3', get_template_directory_uri() . '/assets/libs/fancybox/jquery.fancybox.min.css');

   wp_enqueue_script('fancybox_modals_js', get_template_directory_uri().'/assets/js/fancybox_modals.js', ['jquery']); 
?>