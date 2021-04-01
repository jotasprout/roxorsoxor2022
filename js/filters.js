// filters for software samples

$(document).ready(function(){
    $('li').on('click', function() {
        // const $software = $('#'+$(this).data('content-id'));
        $software = $(this).data('content-id');
        $notSoftware = $('div').not('.'+$software);
           // $('.'+$(this).data('content-id'))
           $notSoftware.fadeToggle();

       // $('.'+$(this).data('content-id')).fadeToggle();
       // $software.fadeToggle();
       console.log($software);
    });
});
