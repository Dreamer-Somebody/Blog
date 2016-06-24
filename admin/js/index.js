jQuery(document).ready(function($) {
    if ($("#content").text() === '') {
        get_page("index");
    }
    $("body").on('click', 'a[id]', function(event) {
        event.preventDefault();
        $id = $(this).attr('id');
        get_page($id);
    });
    function get_page($id){
    $.ajax({
        url: 'control.php',
        type: 'get',
        data: { action: 'get_page', id: $id },
        success: function(msg) {
            $("#content").html(msg);
        }
    });
}
});



