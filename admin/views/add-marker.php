<div class="wrap">
    <h1>Добавить Маркер</h1>
    <form method='post' action='admin-post.php' novalidate>
        <input name='action' type="hidden" value='<?=$action?>_marker'>
        <?php if($action == 'edit'): ?>
            <input type="hidden" name="marker" value="<?=$marker_id;?>">
        <?php endif; ?>
        <div class="poststuff">
            <div id="titlediv">
                <input value="<?=$title;?>" placeholder="Название маркера" type="text" name="post_title" size="30" value="" id="title" spellcheck="true" autocomplete="off">
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
                <?php do_action('insert_yandex_map', array( 'lat' => $lat, 'lng' => $lon,'zoom' => $zoom)) ?>
            </div>
            <div class="coordinates hand" style="padding-top: 10px; display: none;">
                <label>
                    Lat
                    <input value="" name="lat" class="lat" type="number" min="-179.999999" max="179.999999" step="0.000001"/>
                </label>
                <label>
                    Lng
                    <input value="" name="lon" class="lng" type="number" min="-179.999999" max="179.999999" step="0.000001"/>
                </label>
                <label>
                    Zoom
                    <input value="" name="zoom" class="zoom" type="number" min="-179.999999" max="179.999999" step="0.000001"/>
                </label>
            </div>
        </div>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="map-selector">Выберите карту</label>
                </th>
                <td>
                    <select id="map-selector" name="map_id">
                            <?php foreach($maps as $map): ?>
                                <option value="<?=$map['ID']?>"><?=$map['Title']?></option>
                            <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
               <th scope="row">
                   <label for="">Описание</label>
               </th>
               <td>
                    <textarea class="large-text" rows="5" name="description" placeholder="Описание точки"></textarea>
               </td>
            </tr>
        </table>
        <?=submit_button();?>
    </form>
</div>