; ! ( function ( $ )
{"use strict";

	window.toggleRequiredValidation = function ( )
	{
		if  ( $ ( "#support_cost_management" ).is ( ':checked' ) )
		{
			$ ( '.iam_credentials' ).toggle ( );
			$ ( "#bucket_name" ).rules ( "add" ,
			{
				required : true ,
				messages :
				{
					required : "lang('amznaccount:error_bucket_name')"
				}
			} );

		}
		else
		{
			$ ( "#bucket_name" ).rules ( "remove" );

		}
	};

	$ ( document ).ready ( function ( )
	{
		$ ( "form" ).validate ( );
		$ ( "#amznaccount_save" ).click ( function ( event )
		{
			event.preventDefault ( );

			var $overlay = createOverlayWithProgressbar ( "#form_createedit" ,
			{
				title : "Saving please wait" ,
				attachTo : "#form_createedit"
			} );

			var form = $ ( "form" ).serializeObject ( );
			console.log ( form );
			var name = $ ( '#name' ).val ( );
			var account_id = $ ( '#account_id' ).val ( );
			var api_key = $ ( '#api_key' ).val ( );
			var secret_key = $ ( '#secret_key' ).val ( );
			var bucket_name = $ ( '#bucket_name' ).val ( );
			if  ( name == "" || account_id == "" || api_key == "" || secret_key == "" )
			{
				show_message ( "Mandatory Values Check" ,  'Fields are empty' ,  'error' );

				setTimeout ( function ( )
				{
					$overlay.hide ( );
				} ,  0 );
			}
			else
			if  ( $ ( "#support_cost_management" ).is ( ':checked' ) && ( bucket_name == "" ) )
			{
				show_message ( "AWS Billing" ,  'Bucket Name is required' ,  'error' );

				setTimeout ( function ( )
				{
					$overlay.hide ( );
				} ,  0 );
			}
			else
			{
				$.ajax (
				{
				url : SITE_URL + 'admin/amznaccount/saveData' ,
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
						show_message ( 'Amazon AWS Save:' + data["status"] ,  data["status_msg"] + ':' + form.name ,  data["status"] ,  SITE_URL + "admin/amznaccount/index" );
						//window.location = SITE_URL + "admin/requisitions/index";
					}
					else if  ( data["status"] == 'error' )
					{
						show_message ( 'Amazon AWS  Save:' + data["status"] ,  data["status_msg"] + ':' + form.name ,  data["status"] );
					}
				}
				},  "json"  ).always ( function ( )
				{
					//alert("finished");
					// Hide Overlay
					setTimeout ( function ( )
					{
						$overlay.hide ( );
					} ,  0 );
				} );
			}
		} );
	} );
} ) ( jQuery );
