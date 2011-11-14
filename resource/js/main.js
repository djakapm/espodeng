var base_url = 'http://localhost/app/ongkir/';

	function default_input_behaviour(input,default_string){
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

	// function compose_url(origin_id,destination_id){
	// 	var weight = $('#weight').val();
	// 	var url = base_url+'index.php/service/price?o='+origin_id+'&d='+destination_id+'&w='+weight;

	// 	return url;
	// }

	function clear_result(){
		$('#result-origin').text('');
		$('#result-destination').text('');
		$('#logistic-ouput-container').empty();
		$('#result').hide();								
		$('#cheapest-filter').removeClass('active-filter');
		$('#middle-filter').removeClass('active-filter');
		$('#fastest-filter').removeClass('active-filter');
	}

	function recompose_cache_data(cache){
		if(!cache){return;}
		var temp = new Array();
		var counter = 0;
		for(var idx=0;idx<cache.length;idx++){
			var item = cache[idx];
			for(var idy=0;idy<item.length;idy++){
				temp[counter] = item[idy];
				counter++;
			}
		}
		return temp;
	}

	function the_middle_sort(){
		var container = $('#logistic-ouput-container');
		var cache = container.data('cache');
		if(!cache){return;}
		cache.sort(function(a,b){return parseFloat(a.rank) - parseFloat(b.rank);});
	    logistic_service_result(container,cache,true);							
		$('#result').show();		
	}

	function the_cheapest_sort(){
		var container = $('#logistic-ouput-container');
		var cache = container.data('cache');
		if(!cache){return;}
		cache.sort(function(a,b){return parseFloat(a.total_price) - parseFloat(b.total_price);});
	    logistic_service_result(container,cache,true);							
		$('#result').show();
	}

	function the_fastest_sort(){
		var container = $('#logistic-ouput-container');
		var cache = container.data('cache');
		if(!cache){return;}
		cache.sort(function(a,b){return parseFloat(a.delivery_time) - parseFloat(b.delivery_time);});
	    logistic_service_result(container,cache,true);		
		$('#result').show();
	}


	function authorized(data){
		clear_result();
		$('#result-info').text('');
		var container = $('#logistic-ouput-container');

	    logistic_service_result(container,data.results,false);

	    var cache = data.results;

	    container.data('cache',cache);
		$('#result').show();
	}

	function unauthorized(){
		clear_result();
		show_error('Maaf validasi captcha tidak berhasil, silahkan ketik ulang.');
	}

	function search_action(){
		var origin_district_id = $('#origin-input').attr('data');
		var destination_district_id = $('#destination-input').attr('data');

		// var url = compose_url(origin_district_id,destination_district_id);

		var params = $('#input-form').serialize()+'&o='+origin_district_id+'&d='+destination_district_id+'&w='+$('#weight').val();
		$.post(base_url+'index.php/service/validate',params,function(data){

			var json_response = $.parseJSON(data);
			if(!json_response){
				clear_result();
				show_error('Invalid response');				
			}
			else
			if(json_response.status == 200){
				authorized(json_response);
			}
			else
			if(json_response.status == 401){
				unauthorized();			
			}
			else
			if(json_response.status == 400){
				clear_result();
				show_warning('Maaf untuk saat ini Kami hanya melayani pengiriman dari Jakarta.');
			}
		});
		Recaptcha.reload();

	}

	function clear_info(){
		$('#info').empty();		
	}

	function show_warning(str){
		$('#info').empty();
		$('#info').append('<p style="color:orange"><b>'+str+'</b></p>');								
	}

	function show_error(str){
		$('#info').empty();
		$('#info').append('<p style="color:red"><b>'+str+'</b></p>');								
	}

	function logistic_service_result(container,logistics,sorted){

		for(var idx=0;idx<logistics.length;idx++){
			var logistic = logistics[idx];
			if(logistic.status == 404){return;}
			var name = logistic.name.toUpperCase();
			var prefix = logistic.name;
			var service_name = logistic.service_name;
			var unit_price = logistic.unit_price;
			var delivery_time = logistic.delivery_time;
			var total_price = logistic.total_price;
			var div = '';
			if(sorted){
				if(idx == 0){
					div = '<div id="'+prefix+'-result-container-'+idx+'" style="padding:5px;margin-top:20px;background-color:#FFD83C;border: 1px solid #FFB83C">';
				}
				else{
					div = '<div id="'+prefix+'-result-container-'+idx+'" style="padding:5px;margin-top:20px">';				
				}
				
			}
			else{
				div = '<div id="'+prefix+'-result-container-'+idx+'" style="padding:5px;margin-top:20px">';				
			}
				div += '<h3 id="'+prefix+'-result-info-'+idx+'"><span id="'+prefix+'-total-price-result-'+idx+'" style="font-size:inherit"></span> ('+name+'&nbsp;'+service_name+')</h3>';
				div += '<div id="'+prefix+'-result-'+idx+'" style="display:none">';
				div += '<p id="'+prefix+'-delivery-time-result-'+idx+'" style="margin:5px;font-size:small"></p>';
				div += '<p id="'+prefix+'-unit-price-result-'+idx+'" style="margin:5px;font-size:small"></p>';
				div += '</div>';
				div += '</div>';

			container.append(div);

			$('#'+prefix+'-unit-price-result-'+idx).text('Harga per kilogram Rp. '+$.currency(unit_price,{s:',',d:'.',c:0}));
			$('#'+prefix+'-delivery-time-result-'+idx).text('Waktu Pengiriman '+delivery_time+' hari');
			$('#'+prefix+'-total-price-result-'+idx).text('Rp. '+$.currency(total_price,{s:',',d:'.',c:0}));
			$('#'+prefix+'-result-'+idx).show();	
			
		}
	} 

	function toggle_filter(current_filter_id,other_filter_ids){
		$('#'+current_filter_id).removeClass('filter').addClass('active-filter');
		var len = other_filter_ids.length;
		for(var idx=0;idx<len;idx++){
			$('#'+other_filter_ids[idx]).removeClass('active-filter').addClass('filter');			
		}
	}

	function origin_callback(item){
		$('#origin-input').attr('data',item.id);		
	}

	function destination_callback(item){
		$('#destination-input').attr('data',item.id);
	}

	function valid_input(){


		if(!$('#origin-input').attr('data')){
			show_error('Silahkan isi daerah asal.');
			return false;
		}

		if(!$('#destination-input').attr('data')){
			show_error('Silahkan isi daerah tujuan.');
			return false;
		}

		if(!parseFloat($('#weight').val())){
				show_error('Silahkan isi berat paket.');
				return false;
		}

		if(!$('input[name=recaptcha_response_field]').val()){
			show_error('Silahkan isi validasi sesuai dengan gambar.');
			return false;
		}

		return true;
		
	}

	var main = function(){
	 
	if($.find('#origin-input').length > 0)	
	$('#origin-input').jsonSuggest({
		url:base_url+'index.php/service/place',minCharacters:3,onSelect:origin_callback
	});

	if($.find('#destination-input').length > 0)
	$('#destination-input').jsonSuggest({
		url:base_url+'index.php/service/place',minCharacters:3,onSelect:destination_callback
	});
				
	default_input_behaviour($('#origin-input'),'Kota asal(Minimal 3 karakter)?');
	default_input_behaviour($('#destination-input'),'Kota tujuan(Minimal 3 karakter)?');

		$('#cheapest-filter').click(function(e){
			clear_result();
			toggle_filter('cheapest-filter',['middle-filter','fastest-filter']);
			the_cheapest_sort();
		});
		$('#middle-filter').click(function(e){
			clear_result();
			toggle_filter('middle-filter',['fastest-filter','cheapest-filter']);
			the_middle_sort();
		});
		$('#fastest-filter').click(function(e){
			clear_result();
			toggle_filter('fastest-filter',['cheapest-filter','middle-filter']);
			the_fastest_sort();
		});


		$('#input-form').submit(function(event){
			 event.preventDefault(); 
		});


		$('#search-button').click(function(){
			if(valid_input()){
				clear_info();
				search_action();			
			}
		});
	}
	

$(document).ready(main);