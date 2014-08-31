(function($, window, document) 
{
    $(function() 
    {
        $('.chekbox').click(function(){

            if ( $( this ).children('.checkON').css('left') == '0px' )
            {
                $( this ).children('.checkON').css('left', '-55px');
                $( this ).children('.checkOFF').css('left', '0px');
                $( this ).children('input[type="checkbox"]').attr('checked', false);
                $( this ).children('input[type="checkbox"]').attr('value', 1);
            }
            else
            {
                $( this ).children('.checkON').css('left', '0px');
                $( this ).children('.checkOFF').css('left', '55px');
                $( this ).children('input[type="checkbox"]').attr('checked', true);
                $( this ).children('input[type="checkbox"]').attr('value', 0);
            }

        });
        
        $.each($('.chekbox'), function(key, value)
        {
            if($(value).children('input[type="checkbox"]').attr('checked') == "checked" )
            {
                $( this ).children('.checkON').css('left', '0px');
                $( this ).children('.checkOFF').css('left', '55px');
            }
        });
    });

}(window.jQuery, window, document));