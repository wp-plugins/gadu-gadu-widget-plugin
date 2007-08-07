<?php
/*
Plugin Name: Gadu widget
Description: Adds a widget to provide your Gadu-Gadu IM status.
Author: Dcn. James Konicki
Version: alpha 0.5
Author URI: http://konicki.com/blog2/
Plugin URI: http://konicki.com/blog2/downloads/gadu-gadu-wordpress-plug-in/
Based on the gadu-gadu PHP script as found at The Mirage project
WWW: http://kacper.hostingposts.com/dev/im_status.html
Gadu is released under the GNU General Public License (GPL)
WWW: http://www.gnu.org/licenses/gpl.txt
*/

function widget_gadu_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;


	function widget_gadu($args) {
		
		extract($args);

		$gaduoptions = get_option('widget_gadu');
		$title = $gaduoptions['gtitle'];
		$janbr = $gaduoptions['janbr'];
		$gplace = "http://www.gadu-gadu.pl/users/status.asp?id=".$janbr."&styl=2";
		$gicons_baseurl = get_bloginfo('wpurl') . "/wp-content/plugins/gadu-gadu/images/";
		$gimageurl=$gicons_baseurl;
		
		echo $before_widget . $before_title . $title . $after_title;
$gfile = fopen ($gplace, "r");
$gstatus = fgets($gfile, 2);
switch ($gstatus)
{
case 1:
echo '<img src="'.$gimageurl.'gg-off.png" style="border: 0; vertical-align:middle" alt="Niedostępny" /> Niedostępny';
break;
case 2:
echo '<img src="'.$gimageurl.'gg-on.png" style="border: 0; vertical-align:middle" alt="Dostępny" /> Dostępny';
break;
case 3:
echo '<img src="'.$gimageurl.'gg-hoo.png" style="border: 0; vertical-align:middle" alt="Zaraz wracam" /> Zaraz wracam';
break;
default:
echo '<img src="'.$gimageurl.'gg-off.png" style="border: 0; vertical-align:middle" alt="Gadu-Gadu unknown" /> Niewiadomy';
break;
}
fclose($gfile);
echo '<br />';
echo '<a href="gg:'.$janbr.'">Dodaj '.$janbr.'</a> do swojej listy kontaktów.';
		
		echo $after_widget;
	}

// Options form for the widget - called in the Sidebar Widgets of the Presentation tab


	function widget_gadu_control() {


        // Collect our widget's options.

        $gaduoptions = get_option('widget_gadu');

        // This is for handing the control form submission.

	if ( !is_array($gaduoptions) )
	    $gaduoptions = array('gtitle'=>'', 'janbr'=>'0');
        if ( $_POST['gadu-submit'] ) {
            $gaduoptions['gtitle'] = strip_tags(stripslashes($_POST['gadu-title']));
	    $gaduoptions['janbr'] = strip_tags(stripslashes($_POST['gadu-janbr'])); 
            update_option('widget_gadu', $gaduoptions);
            }


        $title = htmlspecialchars($gaduoptions['gtitle'], ENT_QUOTES);
	$janbr = htmlspecialchars($gaduoptions['janbr'], ENT_QUOTES); 

	// The control form for editing options.        

        echo '<p><label for="gadu-title">' . __('Title:') . ' <input style="width: 200px;" type="text" id="gadu-title" name="gadu-title" value="'.$title.'" /></label></p>';
	echo '<p><label for="gadu-janbr">' . __('Numer twojego konta:') . ' <input style="width: 100px;" type="text" id="gadu-janbr" name="gadu-janbr" value="'.$janbr.'" /></label></p>';
        echo '<input type="hidden" name="gadu-submit" id="gadu-submit" value="1" />';
        } 


// Register the widget and control
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	
	register_sidebar_widget(array('Gadu', 'widgets'), 'widget_gadu');

	// This registers the (optional!) widget control form.

    	register_widget_control('Gadu', 'widget_gadu_control'); 


}

// Run our code later in case this loads prior to any required plugins.

add_action('widgets_init', 'widget_gadu_init');

?>