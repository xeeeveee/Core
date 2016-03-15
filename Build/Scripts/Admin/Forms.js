jQuery(document).ready(function($){
    $('div.element.color input').wpColorPicker();
    $('div.element.date input').datepicker({
        dateFormat : 'dd-mm-yy'
    });
});