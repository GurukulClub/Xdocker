;
processKeyUploadForm = function ( )
{
	var $form_uploadamznKeyForm = $ ( 'form#uploadamznKeyForm' );
	$form_uploadamznKeyForm.removeAttr ( 'action' );
	$form_uploadamznKeyForm.on ( 'submit' , function ( e )
	{
		var form_data = $ ( this ).serialize ( );
		var file = this.files [ 0 ];
		alert ( file.name );
	} );

	$ ( ':file' ).change ( function ( )
	{
		var file = this.files [ 0 ];
		name = file.name;
		size = file.size;
		type = file.type;
		alert ( name );
		alert ( type );
		if  ( type !== 'application/x-x509-user-cert' )
		{
			$ ( '#amzn_file_type_error' ).html ( 'File is not valid type!' );
		}

	} );
}
