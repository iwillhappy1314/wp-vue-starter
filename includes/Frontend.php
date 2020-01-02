<?php


namespace WenpriseTranslatorManager;


class Frontend
{
    public function __construct()
    {
        add_shortcode('wenprise-translator-manager', [$this, 'render_frontend']);
    }

    /**
     * Render frontend app
     *
     * @param array  $atts
     * @param string $content
     *
     * @return string
     */
    public function render_frontend($atts, $content = '')
    {
        wp_enqueue_style('wprs-tsm-frontend');
        wp_enqueue_script('wprs-tsm-frontend');
        $content .= '<div id="wprs-tsm-frontend-app"></div>';

        return $content;
    }
}