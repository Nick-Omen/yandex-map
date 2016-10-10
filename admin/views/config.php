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
            <label>
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
                    <input id="yandex-map_default-lat"
                           name="yandex_map_default_lat"
                           value="<?php echo esc_attr(get_option('yandex_map_default_lat', 0)); ?>"
                           type="number" min="-179.999999" max="179.999999" step="0.000001"/>
                </td>
            </tr>
            <tr valign="top" class="coordinates hand" style="display: none;">
                <th scope="row"><label for="yandex-map_default-lng"><?php _e('Долгота по-умолчанию', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-lng"
                           name="yandex_map_default_lng"
                           value="<?php echo esc_attr(get_option('yandex_map_default_lng', 0)); ?>"
                           type="number" min="-179.999999" max="179.999999" step="0.000001"/>
                </td>
            </tr>
            <tr valign="top" class="coordinates hand" style="display: none;">
                <th scope="row"><label for="yandex-map_default-zoom"><?php _e('Приближение по-умолчанию', 'yandex-map') ?></label></th>
                <td>
                    <input id="yandex-map_default-zoom"
                           name="yandex_map_default_zoom"
                           value="<?php echo esc_attr(get_option('yandex_map_default_zoom', 13)); ?>"
                           type="number" size="2" min="2" max="18"/>
                </td>
            </tr>
            <tr valign="top" class="coordinates map">
                <th scope="row"><label for="yandex-map_default-lng"><?php _e('Начальные координаты на карте', 'yandex-map') ?></label></th>
                <td>
                    <script>
                        var yandexMapConfig_admin_page = {
                            width: "100%",
                            height: "300px",
                            lat: <?php echo esc_attr(get_option('yandex_map_default_lat', 0)) ?>,
                            lng: <?php echo esc_attr(get_option('yandex_map_default_lng', 0)) ?>,
                            zoom: <?php echo esc_attr(get_option('yandex_map_default_zoom', 13)) ?>
                        };

                        var initYandexMap_admin_page = function(){
                            var map = new YandexMapClass(document.getElementById("admin_page"), yandexMapConfig_admin_page);
                            map.changeMarkerPosition = function(event){
                                console.log(event);
                            };
                            map.setMarker({
                                lat: <?php echo esc_attr(get_option('yandex_map_default_lat', 0)) ?>,
                                lng: <?php echo esc_attr(get_option('yandex_map_default_lng', 0)) ?>,
                                options: {
                                    draggable: true
                                }
                            });
                        };
                        jQuery(document).on('yandexMapLoaded', function(){
                            initYandexMap_admin_page();
                        });
                    </script>
                    <div id="admin_page"><span class="text-loading">Загрузка карты...</span></div>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
