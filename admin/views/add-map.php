<div class="wrap">
    <h1>Добавить карту</h1>
    <form method='post' action='admin-post.php' novalidate>
        <input name='action' type="hidden" value='map_handler'>
        <div class="poststuff">
            <div id="titlediv">
                <input placeholder="Введите" type="text" name="post_title" size="30" value="" id="title" spellcheck="true" autocomplete="off">
            </div>
        </div>
        <div class="yandex-map-container">
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
            <div class="coordinates map" style="padding-top: 10px;">
                <?php do_action('insert_yandex_map', array(
                    'lat' => esc_attr(get_option('yandex_map_default_lat', 0)),
                    'lng' => esc_attr(get_option('yandex_map_default_lng', 0)),
                    'zoom' => esc_attr(get_option('yandex_map_default_zoom', 13))
                )) ?>
            </div>
            <div class="coordinates hand" style="padding-top: 10px; display: none;">
                <label>
                    Lat
                    <input name="lat" class="lat" type="number" min="-179.999999" max="179.999999" step="0.000001"/>
                </label>
                <label>
                    Lng
                    <input name="lon" class="lng" type="number" min="-179.999999" max="179.999999" step="0.000001"/>
                </label>
                <label>
                    Zoom
                    <input name="zoom" class="zoom" type="number" min="-179.999999" max="179.999999" step="0.000001"/>
                </label>
            </div>
        </div>
        <?=submit_button();?>
    </form>
</div>