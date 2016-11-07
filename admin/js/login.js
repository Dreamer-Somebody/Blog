jQuery(document).ready(function($) {
    $("form").submit(function(event) {
        event.preventDefault();
        $user = $("input#user").val();
        $pwd = $("input#pwd").val();
        $.ajax({
            url: 'control.php?action=login',
            type: 'post',
            data: { user: $user, pwd: $pwd },
            success: function(msg) {
                if (msg == "登陆成功！") {
                    window.location.href = "index.php";
                } else {
                    $("div#msg p").html(msg);
                }
            }
        });
        return false;
    });
});
