// filters for software samples

$(document).ready(function(){
    $('li').on('click', function() {
        $software = $(this).data('content-id');
        $notSoftware = $('div#container2 > div').not('.'+$software);
        $notSoftware.fadeToggle();
        console.log($software);
    });
});
