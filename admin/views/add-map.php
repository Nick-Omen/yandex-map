<div class="wrap">
    <h1>Добавить карту</h1>
    <form method='post' action='admin-post.php'>
        <input name='action' type="hidden" value='map_handler'>
        <div class="poststuff">
            <div id="titlediv">
                <label class="" id="title-prompt-text" for="title">Введите заголовок</label>
                <input type="text" name="post_title" size="30" value="" id="title" spellcheck="true" autocomplete="off">
            </div>
        </div>
        <?=submit_button();?>
    </form>
</div>