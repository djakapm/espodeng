(function($) { 
$.fn.widedrop = function(options){  
   var self = this;
   var fill_data = function(data){
         var position = $(self).position();
         var top = position.top + $(self).height();
         var left = position.left;
         var list;

         if($.find('#'+options.id).length > 0){
            $('#'+options.id).empty();
         }
         else{
            list = '<ul id="'+options.id+'" style="border: 1px solid #DCDCDC;overflow:auto;position:fixed;left:'+left+'px;top:'+top+'px;background-color:#f5f5f5"></ul>';         list += '</ul>';
            $('body').append(list);            
         }

         $('#'+options.id).hide();
         $.each(data,function(key,value){
            var item = $('<li style="padding:5px;cursor:pointer"></li');
            item.html(value);
            item.attr('value',key);
            $('#'+options.id).append(item);
         });
         $('#'+options.id).children().each(function(index,elm){
            $(elm).click(function(e){
               $(self).val($(elm).text());
               $(self).attr('data',$(elm).attr('value'));
               $('#'+options.id).hide();
            });
         });
         $('#'+options.id).show();            
      };
   
   this.keyup(function(e){
      if($(self).val().length >= 3){
         $.getJSON('http://localhost/app/ongkir/index.php/service/place?q='+$(self).val(),fill_data);         
      }
   });
   this.focusout(function(e){
      $('#'+options.id).hover(function(){},function(){$('#'+options.id).hide();});
   });
}

})(jQuery);