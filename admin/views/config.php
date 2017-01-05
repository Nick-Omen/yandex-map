<?php

/**
 * Yandex map configuration page.
 *
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
?>

<div class="wrap">
    <h1><?php _e('Настройка карт', 'yandex-map') ?></h1>
    <div class="notice">
        <p><?php _e('Текущие настройки применимы ко всем Яндекс-картам, для которых не заданы параметры.', 'yandex-map') ?></p>
    </div>

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
                           value="<?php echo esc_attr(get_option('yandex_map_default_width', 0)); ?>"
                           type="number" min="0"/>
                    <p class="description"><?php _e('Оставьте значение 0 что бы растянуть на 100% по ширине.', 'yandex-map') ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="yandex-map_default-height"><?php _e('Высота карты по-умолчанию(пиксели)', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-height"
                           name="yandex_map_default_height"
                           value="<?php echo esc_attr(get_option('yandex_map_default_height', 0)); ?>"
                           type="number" min="0"/>
                    <p class="description"><?php _e('Оставьте значение 0 что бы растянуть на 100% по высоте.', 'yandex-map') ?></p>
                </td>
            </tr>
        </table>

        <h3><?php _e('Начальная позиция на карте', 'yandex-map') ?></h3>
        <div style="padding-top: 10px;">
            <label style="margin-right: 10px;">
                <input name="use_map_coordinates" type="radio" value="map" checked/>
                <?php _e('Использовать коортинаты с карты', 'yandex-map') ?>
            </label>
            <label>
                <input name="use_map_coordinates" value="hand" type="radio"/>
                <?php _e('Ввести координаты вручную', 'yandex-map') ?>
            </label>
        </div>
        <table class="form-table">
            <tr valign="top" class="coordinates hand" style="display: none;">
                <th scope="row"><label for="yandex-map_default-lat"><?php _e('Широта по-умолчанию', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-lat" class="lat"
                           name="yandex_map_default_lat"
                           value="<?php echo esc_attr(get_option('yandex_map_default_lat', 0)); ?>"
                           type="number" min="-179.999999" max="179.999999" step="0.000001" size="24"/>
                </td>
            </tr>
            <tr valign="top" class="coordinates hand" style="display: none;">
                <th scope="row"><label for="yandex-map_default-lng"><?php _e('Долгота по-умолчанию', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-lng" class="lng"
                           name="yandex_map_default_lng"
                           value="<?php echo esc_attr(get_option('yandex_map_default_lng', 0)); ?>"
                           type="number" min="-179.999999" max="179.999999" step="0.000001" size="24"/>
                </td>
            </tr>
            <tr valign="top" class="coordinates hand" style="display: none;">
                <th scope="row"><label for="yandex-map_default-zoom"><?php _e('Приближение по-умолчанию', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-zoom" class="zoom"
                           name="yandex_map_default_zoom"
                           value="<?php echo esc_attr(get_option('yandex_map_default_zoom', 13)); ?>"
                           type="number" size="2" min="2" max="18"/>
                </td>
            </tr>
            <tr valign="top" class="coordinates map">
                <th scope="row"><label for="yandex-map_default-lng"><?php _e('Начальные координаты на карте', 'yandex-map') ?></label></th>
                <td>
                    <?php do_action('insert_yandex_map', array(
                        'lat' => esc_attr(get_option('yandex_map_default_lat', 0)),
                        'lng' => esc_attr(get_option('yandex_map_default_lng', 0)),
                        'zoom' => esc_attr(get_option('yandex_map_default_zoom', 13))
                    )) ?>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
