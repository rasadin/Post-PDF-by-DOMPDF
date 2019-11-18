use Dompdf\Dompdf;
use Dompdf\Options;

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


if(isset($_POST['download_pdf'])) {
    // instantiate and use the dompdf class

    //Get post ID from form submission
    $uri_path = get_the_post_thumbnail_url();
    $uri_segments = explode('/', $uri_path);
    $url_part1 = $uri_segments[0];
    $url_part2 = $uri_segments[1];
    $url_part3 = $uri_segments[2];
    $url_part4 = $uri_segments[3];
    $url_part5 = $uri_segments[4];
    $url_part6 = $uri_segments[5];
    $url_part7 = $uri_segments[6];
    $url_part8 = $uri_segments[7];
    $url_part9 = $uri_segments[8];

    $root_path = $_SERVER["DOCUMENT_ROOT"];
    $root_path_str  = str_replace('/', '\\', $root_path);

    $path_slash = '\\';
    $root_url = $url_part4.$path_slash.$url_part5.$path_slash.$url_part6.$path_slash.$url_part7.$path_slash.$url_part8.$path_slash.$url_part9;
    $root_url_str  = str_replace('/', '\\', $root_url);

    $image_url_pdf = $root_path_str.$root_url_str; 
   

	$dompdf = new Dompdf();
    $pdf_dom = '
        <center><h2 class="pdf-post-title">'.$post->post_title.'</h2></center>
        <div class="pdf-post-content">
            <center><img src="'.$image_url_pdf.'" alt="Cinque Terre" width="500" height="500"/></center>
            <center><img src="C:\Wamp64\www\eventbookings-website\wp-content\themes\webalive\assets\images\header.jpg" alt="Cinque Terre" width="500" height="500"/></center>
            <br>
            '.$post->post_content.'
        </div>
        <div class="pdf-content">
          ' . $_SERVER["DOCUMENT_ROOT"] . '
        </div>
        <div class="pdf-content">
            ' .$url_part1. '
         </div>
         <div class="pdf-content">
            ' .$url_part2. '
        </div>
        <div class="pdf-content">
            ' .$url_part3. '
        </div>
        <div class="pdf-content">
            ' .$url_part4. '
        </div>
        <div class="pdf-content">
            ' .$url_part5. '
        </div>
        <div class="pdf-content">
            ' .$url_part6. '
        </div>
        <div class="pdf-content">
            ' .$url_part7. '
        </div>
        <div class="pdf-content">
            ' .$root_path_str. '
        </div>
        <div class="pdf-content">
        ' . $root_url_str . '
	';

	$dompdf->loadHtml($pdf_dom);

	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'landscape');

	// Render the HTML as PDF
	$dompdf->render();

	// Output the generated PDF to Browser
	$dompdf->stream();
}
