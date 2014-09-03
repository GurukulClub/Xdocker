; ! ( function ( $ )
{"use strict";

	$ ( document ).ready ( function ( )
	{
		$ ( "form" ).validate ( );
		$ ( "#Account_save" ).click ( function ( event )
		{
			event.preventDefault ( );

			var $overlay = createOverlayWithProgressbar ( "#form_createedit" ,
			{
				title : "Saving please wait" ,
				attachTo : "#form_createedit"
			} );
			
			var form = $( "form" ).serializeObject ( );
			
			if(form.name == '')
			{
				show_message ('Account validation:' +  form.name + ' is a mandatory field ' ,  data["status"] );
				return false;
			}
			console.log ( form );
			$.ajax (
				{
				url : SITE_URL + 'admin/account/saveData' ,
				type : "POST" ,
				data : form ,
				success : function ( json )
				{
					setTimeout ( function ( )
					{
						$overlay.hide ( );
					} ,  0 );
					var data = JSON.parse(json);
					
					if  ( data["status"] == 'success' )
					{
						show_message ('Account Save:' + data["status"] ,  data["status_msg"] + ':' + form.name ,  data["status"] ,  SITE_URL + "admin/Account/index" );
						window.location = SITE_URL + "admin/Account/index";
					}
					else if  ( data["status"] == 'error' )
					{
						show_message ('Account Save:' + data["status"] ,  data["status_msg"] + ':' + form.name ,  data["status"] );
					}
				}
				},  "json"  ).always ( function ( )
				{
					setTimeout ( function ( )
					{
						$overlay.hide ( );
					} ,  0 );
				} );
		} );
	} );
} ) ( jQuery );
