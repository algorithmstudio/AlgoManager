
    var set_timeout;

    (function($, window, document)
    {
        $(function() 
        {
            $('#password-generate-button').click( onClickGeneratePassword );
            $('#key-generate-button').click( onClickGenerateKey );
            $('#info-display, .close-infos').click( onClickInfosDisplay);
            $('.user').click( onClickUserHandler );
            $('#search-apps').keyup( onKeyPressSearchAppsHandler );
            $('#search-input').keyup( onKeyPressSearchHandler );
            setTimeout(removeNotification, 5000);
            
            $('#update-task').click( onClickUpdateHandler );
            
            console.log("Start App");

        });

    }(window.jQuery, window, document));
    
    function onClickGeneratePassword()
    {
        $('.password').val( randomString(25, 'aA!#') );
    }
    
    function onClickGenerateKey()
    {
        $('.password').val( randomString(8, 'aA#') );
    }
    
    function onClickUpdateHandler()
    {
        
        if($('#update').css('display') === "block" )
        {
            $('#update').hide();
        }
        else
        {
            $('#update').show();
        }
        
        return false;
    }
    
    function removeNotification()
    {
        $(".display-message").fadeOut('slow', function() 
        {
            $(this).remove();
        });
    }
        
    function onClickUserHandler()
    {
        if( $('.user-expend').css('display') != 'none' ) $('.user-expend').hide();
        else $('.user-expend').show();
    }
    
    function onKeyPressSearchAppsHandler(e)
    {   
        clearTimeout(set_timeout);
        
        var search = $(this).val();
        
        set_timeout = setTimeout( function(){
            
            if(search == "")
            {
                $('.row').show();
                return;
            }

            $('.row').each(function(index)
            {
                var string = $(this).find('.label .label-content').text() + " " +
                             $(this).find('.version .label-content').text() + " " +
                             $(this).find('.field .field-content').text();
                     
                if(string.search( search ) == 1)
                {
                    $(this).show();
                }
                else
                {
                    $(this).hide();
                }
                
            });

        }, 100 );
        
    }
    
    function onKeyPressSearchHandler(e)
    {   
        clearTimeout(set_timeout);
        
        var $this = $(this);
        
        set_timeout = setTimeout( function(){
            
            $.ajax({
                url: search_path, 
                type: 'POST', 
                data: $this.serialize(), 
                success: function(data) 
                { 
                    $('#data-content').html(data);
                    window.history.pushState("search", "search", search_path);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    console.log(errorThrown + " " + textStatus);
                }
            });

        }, 200 );
        
    }
    
    function onClickInfosDisplay(e)
    {        
        if( $('.algo-info').css('display') == 'none' )
        {
           $('.algo-info').show(); 
        }
        else
        {
            $('.algo-info').hide(); 
        }
        
        return false;
    }
    
    function randomString(length, chars) 
    {
        var mask = '';
        if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
        if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if (chars.indexOf('#') > -1) mask += '0123456789';
        if (chars.indexOf('!') > -1) mask += "!#_-:;'?,.";
        var result = '';
        
        for (var i = length; i > 0; --i) result += mask[Math.round(Math.random() * (mask.length - 1))];
        
        return result;
    }