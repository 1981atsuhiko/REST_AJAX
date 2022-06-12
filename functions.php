/////////////////////////////////
//REST API
//キー追加
function rest_post_meta(){
	$path =  $_SERVER['DOCUMENT_ROOT'];
	$file = $path.'/dev/wp-load.php';
	require_once ($file);
	global $wpdb;
	$query = "SELECT wp4ad385posts.ID AS post_id,wp4ad385postmeta.post_id AS meta_id, wp4ad385postmeta.meta_value AS meta_value FROM wp4ad385posts INNER JOIN wp4ad385postmeta ON wp4ad385posts.ID  = wp4ad385postmeta.post_id";
	register_rest_field(
	  'post',
	  'post_meta',
	  array(
		'get_callback'  => function(  $object, $field_name, $request  ) {
		  $meta_fields = array(
			  'sdate',   
			  'edate',
			  );
			  $meta = array();
			  foreach ( $meta_fields as $field ) {
				  $meta[ $field ] = get_post_meta( $object[ 'id' ], $field, true );
			  }
			  return $meta;
		  },
		  'show_in_rest'    => true,
		  'schema'          => null,
	  )
	);
  }
add_action( 'rest_api_init', 'rest_post_meta' );
//filter追加
function my_filter_meta_value( $valid_vars ) {
	$valid_vars = array_merge( $valid_vars, array( 'meta_key', 'meta_value' ) );
	return $valid_vars;
}
add_filter( 'json_query_vars', 'my_filter_meta_value' );
