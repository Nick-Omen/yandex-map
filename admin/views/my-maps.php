<div class="wrap">
    <h2>
        <?=__('Мои Карты', 'yandex-map')?>
        <a href="<?=admin_url('admin.php?page=add-yandex-map')?>" class="page-title-action">
            <?=_e('Добавить','test')?>
        </a>
    </h2>

    <div id="yandexmap-table">
        <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
                <form method="post"><?php
                    $this->table_obj->prepare_items();
                    $this->table_obj->display();
                 ?></form>
            </div>
        </div>
        <br class="clear">
    </div>
</div>