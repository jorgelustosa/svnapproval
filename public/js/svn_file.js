$(document).ready(function()
{

	$.post('/svnapproval/file/listfiles/', function(data) 
	{
		 $('#filemanager').html(data);
	});
	

	$('#last_commit').html('<center><img src="/svnapproval/img/loading.gif"> </img></center>');
	$.post('/svnapproval/file/getsvnlast/', function(data) 
	{
		$('#last_commit').html(data);
	});


	$.post('/svnapproval/file/svnst/', function(data) 
	{
		
		// when no have data to show !!! 
		if(data.length == 3 )
		{ 
			 
			$('#fileschange').hide();
		}
		else
		{ 
			$('#changedfiles').html(data);
		}
		
		
	});
	
	
	// when files are add 
	$('#add_file').click(function() 
	{

	     
		 var tagsArray = new Array();
	     $('input:checked').each(function()
	     {
	    	 id = $(this).attr('id') ; 
	    	 tagsArray.push(id);
	     })  
		
		$('#changedfiles').html('<center><img src="/svnapproval/img/loading.gif"> </img></center>');
	     
		  $.post('/svnapproval/file/svnadd', { 'tags[]': tagsArray },function(data) 
		  {
			  
			  $("#missing-readme").show("slow");
			  $('#missing-readme').html(data);
			  
			  //update added files 
			  $.get('/svnapproval/file/svnst/', function(data) 
			  {
				  
				  	if(data.length == 3 )
					{ 
						 
						$('#fileschange').hide();
					}
					else
					{ 
						$('#changedfiles').html(data);
					}
				  
			  });
			  
			  
		  });
	});
	
	// when files are del 
	$('#delete_file').click(function() 
	{

	     
		 var tagsArray = new Array();
	     $('input:checked').each(function()
	     {
	    	 id = $(this).attr('id') ; 
	    	 tagsArray.push(id);
	     })  
		
		$('#changedfiles').html('<center><img src="/svnapproval/img/loading.gif"> </img></center>');
	     
		  $.post('/svnapproval/file/svndel', { 'tags[]': tagsArray },function(data) 
		  {
			  
			  $("#missing-readme").show("slow");
			  $('#missing-readme').html(data);
			  
			  //update added files 
			  $.get('/svnapproval/file/svnst/', function(data) 
			  {
				  
				  	if(data.length == 3 )
					{ 
						 
						$('#fileschange').hide();
					}
					else
					{ 
						$('#changedfiles').html(data);
					}
				  
			  });
			  
			  
		  });
	});
	
	
	// when files are commited 
	$('#commit_file').click(function() 
	{

		
		if($('#comment_body_50').val() == "") 
		{ 
			
			alert("You need add a comment before commit files!");  
			
		}
		else
		{
		
	     
				 var tagsArray = new Array();
				 var comment = $('#comment_body_50').val() ; 
			     $('input:checked').each(function()
			     {
			    	 id = $(this).attr('id') ; 
			    	 tagsArray.push(id);
			     })  
				   
			     $('#changedfiles').html('<center><img src="/svnapproval/img/loading.gif"> </img></center>');
			     $('#comment_body_50').hide() ; 
			     
			     
				  $.post('/svnapproval/file/svncommit', { 'tags[]': tagsArray , 'comment': comment },function(data) 
				  {
					  
					  $("#missing-readme").show("slow");
					  $('#missing-readme').html(data);
		
					  
					  	$('#last_commit').html('<center><img src="/svnapproval/img/loading.gif"> </img></center>');
						$.post('/svnapproval/file/getsvnlast/', function(data) 
						{
							$('#last_commit').html(data);
						});

					  
					  //update added files 
					  $.get('/svnapproval/file/svnst/', function(data) 
					  {
						  
						  	if(data.length == 3 )
							{ 
								 
								$('#fileschange').hide();
								$('#comment_body_50').val()="" ; 
							}
							else
							{ 
								$('#changedfiles').html(data);
								$('#comment_body_50').val()="" ; 
							}
					  });
				  });
		}  
	});
	
	 
	
	
});



function opendir(dir) 
{
	
	
	
	
	$.post('/svnapproval/file/listfiles/',{ 'folder':dir } , function(data) 
			{
				 $('#filemanager').html(data);
			});



}
