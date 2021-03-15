// filters for software samples

$(document).ready(function(){
    $('li').on('click', function() {
        // const $software = $('#'+$(this).data('content-id'));
       $('.'+$(this).data('content-id')).fadeToggle();
       // $software.hide();
       // console.log($software);
    });
});
