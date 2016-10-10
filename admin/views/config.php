<?php

/**
 * Yandex map configuration page.
 *
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
?>

<div class="wrap">
    <h1><?php _e('Конфигурация карты Яндекс', 'yandex-map') ?></h1>
    <p class="notice"><?php _e('Текущие настройки применимы ко всем Яндекс-картам, для которых не заданы параметры.', 'yandex-map') ?></p>

    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <?php settings_fields('yandex-map-settings'); ?>
        <?php do_settings_sections('yandex-map-settings'); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="yandex-map_default-width"><?php _e('Ширина карты по-умолчанию(пиксели)', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-width"
                           name="yandex_map_default_width"
                           value="<?php echo esc_attr(get_option('yandex_map_default_width')); ?>"
                           type="number" min="0" />
                    <p class="description"><?php _e('Оставьте значение 0 что бы растянуть на 100% по ширине.', 'yandex-map') ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yandex-map_default-height"><?php _e('Высота карты по-умолчанию(пиксели)', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-height"
                           name="yandex_map_default_height"
                           value="<?php echo esc_attr(get_option('yandex_map_default_height')); ?>"
                           type="number" min="0" />
                    <p class="description"><?php _e('Оставьте значение 0 что бы растянуть на 100% по высоте.', 'yandex-map') ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yandex-map_default-lat"><?php _e('Широта по-умолчанию', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-lat"
                           name="yandex_map_default_lat"
                           value="<?php echo esc_attr(get_option('yandex_map_default_lat')); ?>"
                           type="number" min="-179.999999" max="179.999999" step="0.000001" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yandex-map_default-lng"><?php _e('Долгота по-умолчанию', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-lng"
                           name="yandex_map_default_lng"
                           value="<?php echo esc_attr(get_option('yandex_map_default_lng')); ?>"
                           type="number" min="-179.999999" max="179.999999" step="0.000001" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yandex-map_default-zoom"><?php _e('Приближение по-умолчанию', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-zoom"
                           name="yandex_map_default_zoom"
                           value="<?php echo esc_attr(get_option('yandex_map_default_zoom')); ?>"
                           type="number" size="2" min="2" max="18"/>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
