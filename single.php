<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package webalive
 */
// reference the Dompdf namespace

use Dompdf\Options;
require_once __DIR__ . '\dompdf-master\lib\html5lib\Parser.php';
require_once __DIR__ . '\dompdf-master\autoload.inc.php';
require_once __DIR__ . '\dompdf-master\src\Autoloader.php';
use Dompdf\Dompdf;


$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$dompdf = new Dompdf($options);
$contxt = stream_context_create([ 
    'ssl' => [ 
        'verify_peer' => FALSE, 
        'verify_peer_name' => FALSE,
        'allow_self_signed'=> TRUE
    ] 
]);
$dompdf->setHttpContext($contxt);
// ...
if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "generate-one-sheet" && check_admin_referer( 'verify_generate_one_sheet', 'nonce_generate_one_sheet' )) { 

//Get post ID from form submission
$my_postid = $_POST['postid'];
//Get the main content from the post
$content_post = get_post($my_postid);
$thecontent = $content_post->post_content;        
$thecontent = apply_filters('the_content', $thecontent);


$image_url = wp_get_attachment_url(); //ID of your attachment
$my_image_url = explode('/',$image_url,4);
// echo $my_image_url[3];
   

$content = '
<html>
  <head>
   <style>
       .pdf-title {
           text-transform: uppercase;
       }
   </style>
    
  </head>
  <body>
      <div class="pdf-title">
          <h1>' . get_the_title() . '</h1>
      </div>

		<img src="C:\xampp\htdocs\rasadin_elementor_plugin\wp-content\themes\webalive\screenshot.png" />

      <div class="pdf-content">
          ' . $thecontent . '
      </div>

      <div class="pdf-content">
          ' . $_SERVER["DOCUMENT_ROOT"] . '
      </div>

      <div class="pdf-content">
          ' . get_the_post_thumbnail_url() . '
      </div>


      <div class="pdf-content">
        <img src="  ' . get_the_post_thumbnail_url() . '" />
      </div>

        <div class="pdf-content">
        ' .$my_image_url[3]. '
      </div>

  </body>
   
</html>
';
 
// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($content);
 
// (Optional) Setup the paper size and orientation
$dompdf->set_paper( 'letter' , 'portrait' );
 
// Render the HTML as PDF
$dompdf->render();
 
$doctitle = get_the_title() . '- MyWebsite.com';
$dompdf->stream($doctitle);
 
exit();
}
get_header();
?>
<div class="container">
	<div class="row">
		<div class="col-md-9">
			<div id="primary" class="webalive-content-area">
				<main id="main" class="webalive-site-main">

					<?php
					/**
					 * webalive_before_main_content hook
					 */
					do_action( 'webalive_before_main_content' );

					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/post/content', get_post_format($post->ID) );

						the_post_navigation();

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.

					/**
					 * webalive_after_main_content hook
					 */
					do_action( 'webalive_after_main_content' );
					?>

				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
		<form class="form-horizontal" id="" role="form" action="" method="post">
    <button type="submit" class="download-details">
        Download The Details<i class="fa fa-download"></i>
    </button>
    <input type="hidden" name="action" value="generate-one-sheet" />
    <input type="hidden" name="postid" value="<?php echo get_the_ID(); ?>" />
    <?php wp_nonce_field('verify_generate_one_sheet','nonce_generate_one_sheet'); ?>
</form>
		<div class="col-md-3">
				
			<?php 
				/**
				 * webalive_before_sidebar hook
				 */
				do_action( 'webalive_before_sidebar' );

				get_sidebar();
				
				/**
				 * webalive_after_sidebar hook
				 */
				do_action( 'webalive_after_sidebar' );
			?>
			
		</div>
	</div>
</div>
	

<?php
get_footer();
