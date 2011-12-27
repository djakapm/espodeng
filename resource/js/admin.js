var base_url = 'http://localhost/~hendra/espodeng/';


var sel = undefined;

function destination_callback(obj, item){
    console.log('destination: '+item);
    obj.val(item.id+"="+item.text);
}

function default_input_behaviour(input,default_string,click_callback){
    input.click(click_callback);
    input.val(default_string);
    input.focus(function(event){
        $(this).val('');
        $(this).css('border-color','#4D90FE');//#4D90FE
    }).focusout(function(event){
        if(!$(this).val()){
            $(this).val(default_string);				
        }
        $(this).css('border-color','#b9b9b9');
    });
}

var main = function(){
	 
    default_input_behaviour($('.location-input'),'Ketik lokasi',function(){		
        $('.location-input').removeAttr('data');		
    });

    if($.find('.location-input').length > 0) {
        $('.location-input').jsonSuggest({
            url:base_url+'index.php/service/location',
            minCharacters:3,
            onSelect:destination_callback
        });
    }
				
}
	

$(document).ready(main);