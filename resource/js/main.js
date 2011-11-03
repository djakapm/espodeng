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

	function compose_url(origin_id,destination_id){
		var weight = $('#weight').val();
		// var recaptcha_answer = $('textarea[name=recaptcha_challenge_field]').val();
		var url = 'http://localhost/app/ongkir/index.php/service/price?o='+origin_id+'&d='+destination_id+'&w='+weight;
		// +'&r='+recaptcha_answer;

		return url;
	}

	function clear_result(){
		$('#result-origin').text('');
		$('#result-destination').text('');
		$('#logistic-ouput-container').empty();
		$('#result').hide();								
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

	function search_action(){
		var origin_district_id = $('#origin-input').attr('data');
		var destination_district_id = $('#destination-input').attr('data');

		var url = compose_url(origin_district_id,destination_district_id);

		clear_result();
		console.log(url);
		$.getJSON(url,function(data){
			console.log(data);
			$('#result-info').text('');
			if(data && data.status == 200){					
				var container = $('#logistic-ouput-container');
				$('#origin-result').html('Dari <b>'+data.origin+'</b>');
				$('#destination-result').html('Ke <b>'+data.destination+'</b>');
				$('#weight-result').html('Untuk paket dengan berat <b>'+parseFloat($('#weight').val())+' kg</b>');

			    logistic_service_result(container,data.results,false);
			    // logistic_service_result(container,data.jne,false);
			    // logistic_service_result(container,data.tiki,false);

			    // var cache = new Array();
			    var cache = data.results;
			    // cache[0] = data.jne;
			    // cache[1] = data.tiki;

			    container.data('cache',cache);
				$('#result').show();
			}
			else{
				if(data && data.status == 400){
					clear_result();
					show_warning('Maaf untuk saat ini Kami hanya melayani pengiriman dari Jakarta.');
				}
				if(data && data.status == 401){
					clear_result();
					show_error('Maaf validasi captcha tidak berhasil, silahkan ketik ulang.');
				}
			}
		});		
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
				div += '<h3 id="'+prefix+'-result-info-'+idx+'">'+name+'</h3>';
				div += '<h5 id="'+prefix+'-service-name-result-'+idx+'">'+service_name+'</h5>';
				div += '<div id="'+prefix+'-result-'+idx+'" style="display:none">';
				div += '<p id="'+prefix+'-unit-price-result-'+idx+'" style="margin:10px"></p>';
				div += '<p id="'+prefix+'-delivery-time-result-'+idx+'" style="margin:10px;font-size:x-large;padding:5px"></p>';
				div += '<p id="'+prefix+'-total-price-result-'+idx+'" style="margin:10px;font-size:x-large;padding:5px"></p>';
				div += '</div>';
				div += '</div>';

			container.append(div);

			$('#'+prefix+'-unit-price-result-'+idx).text('Dengan harga per kilogram Rp. '+$.currency(unit_price,{s:',',d:'.',c:0}));
			$('#'+prefix+'-delivery-time-result-'+idx).text('Lama Pengiriman '+delivery_time+' hari');
			$('#'+prefix+'-total-price-result-'+idx).text('Total Biaya Rp. '+$.currency(total_price,{s:',',d:'.',c:0}));
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

	var main = function(){

	$('#origin-input').jsonSuggest({
		url:'http://localhost/app/ongkir/index.php/service/place',minCharacters:3,onSelect:origin_callback
	});
	$('#destination-input').jsonSuggest({
		url:'http://localhost/app/ongkir/index.php/service/place',minCharacters:3,onSelect:destination_callback
	});
				
		default_input_behaviour($('#weight'),'Kg?');
		default_input_behaviour($('#origin-input'),'Kota asal(Minimal 3 karakter)?');
		default_input_behaviour($('#destination-input'),'Kota tujuan(Minimal 3 karakter)?');
		$('#cheapest-filter').click(function(e){
			toggle_filter('cheapest-filter',['middle-filter','fastest-filter']);
			clear_result();
			the_cheapest_sort();
		});
		$('#middle-filter').click(function(e){
			toggle_filter('middle-filter',['fastest-filter','cheapest-filter']);
			clear_result();
			the_middle_sort();
		});
		$('#fastest-filter').click(function(e){
			toggle_filter('fastest-filter',['cheapest-filter','middle-filter']);
			clear_result();
			the_fastest_sort();
		});




			$('#search-button').click(function(){
				if(!parseFloat($('#weight').val())){
					show_error('Silahkan isi berat paket.');
					return false;
				}
				else{
					clear_info();
				}

			if(!$('#origin-input').attr('data')){
				show_error('Silahkan isi daerah asal.');
				return false;
			}
			else{
				clear_info();
			}

			if(!$('#destination-input').attr('data')){
				show_error('Silahkan isi daerah tujuan.');
				return false;
			}
			else{
				clear_info();
			}


			search_action();
		});
	}
	

$(document).ready(main);