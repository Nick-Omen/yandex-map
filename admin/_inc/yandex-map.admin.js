jQuery(document).ready(function($){
    "use strict";
    
    $('[name="use_map_coordinates"]').change(function(){
        $('.coordinates').hide().filter('.' + $(this).val()).show();
    })
});