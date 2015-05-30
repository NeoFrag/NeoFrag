$(function(){
	var user_agent = function(){
		$('[data-user-agent]').each(function(){
			var $icon = $(this);
					
			$.ajax({
				url: '//www.neofrag.com/user-agent.json',
				type: 'POST',
				data: 'user_agent='+$icon.data('user-agent'),
				dataType: 'json',
				crossDomain: true,
				success: function(data){
					var output = '';
					var img    = '';
					var img2   = '';
					
					if (data['agent_type'] == 'Browser'){
						if (data['agent_name'] == 'Firefox'){
							img = '{image icons/firefox.png}';
						}
						else if (data['agent_name'] == 'Chrome'){
							img = '{image icons/chrome.png}';
						}
						else if (data['agent_name'] == 'Safari'){
							img = '{image icons/safari.png}';
						}
						else if (data['agent_name'] == 'Internet Explorer'){
							img = '{image icons/ie.png}';
						}
						
						if (img){
							output += '<img src="'+img+'" data-toggle="tooltip" title="'+data['agent_name']+' '+data['agent_version']+'" alt="" /> ';
						}
					}
					
					if (data['os_type'] == 'Windows'){
						img2 = '{image icons/windows.png}';
					}
					else if (data['os_type'] == 'Linux'){
						img2 = '{image icons/animal-penguin.png}';
					}
					else if (data['os_type'] == 'Macintosh'){
						img2 = '{image icons/mac-os.png}';
					}
						
					if (img2){
						output += '<img src="'+img2+'" data-toggle="tooltip" title="'+data['os_name']+'" alt="" />';
					}
					
					if (output == ''){
						output += '<img src="{image icons/user-silhouette-question.png}" data-toggle="tooltip" title="'+$icon.data('user-agent')+'" alt="" />';
					}
					
					$icon.replaceWith('<span class="no-wrap">'+output+'</span>');
				}
			});
		});
	};
	
	$('body').on('nf.table.load', user_agent);
	
	user_agent();
});