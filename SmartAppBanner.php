<?php

class SmartAppBanner{
	private $adminjs = '/wp-content/plugins/smart-app-banner/js/smartappadmin.js',
			$admincss = '/wp-content/plugins/smart-app-banner/css/admin.css',
			$title = 'Smart App Banner',
			$slug = 'SmartAppBanner',
			$optionKey = 'SmartAppBannerSettings',
			$settings = false;

	protected static $defaultSettings = array(
        'daysreminder-text'=> '15',
        'dayshidden-text' => '90',
        'button-text' => 'OPEN',
        'price-text' => 'GET'
    );

	public function SmartAppBanner(){
		$this->actions();
	}

	public function actions(){
		add_action( 'wp_head', array( $this, 'addSmartAppMetaTags' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueue' ) );
		add_action( 'admin_menu', array( $this, 'adminMenu' ) );
		add_action( 'admin_init', array( $this, 'saveSettings' ) );
	}

	public function addSmartAppMetaTags() {

		$currentSABSettings = $this->getSavedSettings();

	    $all_posts['bannerTitle'] = $currentSABSettings['title-text'];
	    $all_posts['itunes_app_id'] = $currentSABSettings['itunesstoreid-text'];
	    $all_posts['google_play_app_id'] = $currentSABSettings['googleplayappid-text'];
	    $all_posts['daysHidden'] = $currentSABSettings['dayshidden-text'];
	    $all_posts['daysReminder'] = $currentSABSettings['daysreminder-text'];
	    $all_posts['buttonText'] = $currentSABSettings['button-text'];
	    $all_posts['priceText'] = $currentSABSettings['price-text'];

	    if ( $currentSABSettings['usetownsquaresettings'] == '1' ){
	    	$townsquareSettings = Townsquare::get_logo_info();
	    	$all_posts['img_url'] = $townsquareSettings['apple-low-res'];
	    } else {
	    	$all_posts['img_url'] = $currentSABSettings['imageurl-text'];
	    }

		if( JSON_TSM_API_Header_View::setSmartAppBanner(
			$all_posts
		) ){ return; }

	}

	public function adminEnqueue( $hook ){
		if( $hook === 'settings_page_SmartAppBanner' ) {
			wp_enqueue_script( 'tsmclip-admin-js', $this->adminjs, null, version_cache(), true );
			wp_enqueue_style( 'SmartAppBanner-admin-css', $this->admincss );
		}
	}

	public function adminMenu(){
		$this->adminPage = add_submenu_page( 'options-general.php', $this->title, $this->title, 'manage_options', $this->slug, array( $this, 'adminMenuFn' ) );
	}

	public function adminMenuFn(){
		$settings = $this->getSavedSettings();
		$townsquareSettings = Townsquare::get_logo_info();
		$logocheck = $this->checkAppLogo();
		ob_start();
			include "views/admin.php";
		$adminMarkup = ob_get_clean();

		$finds = array(
			'title' => stripslashes( $settings[ 'title-text' ] ),
			'itunesstoreid' => stripslashes( $settings[ 'itunesstoreid-text' ] ),
			'googleplayappid' => stripslashes( $settings[ 'googleplayappid-text' ] ),
			'imageurl' => stripslashes( $settings[ 'imageurl-text' ] ),
			'dayshidden' => stripslashes( $settings[ 'dayshidden-text' ] ),
			'daysreminder' => stripslashes( $settings[ 'daysreminder-text' ] ),
			'button' => stripslashes( $settings[ 'button-text' ] ),
			'price' => stripslashes( $settings[ 'price-text' ] ),
			//'usetownsquaresettings' => checked( $settings['usetownsquaresettings'], '1' )
		);

		if ( $logocheck['usetownsquaresettings'] == '1' ){
			$finds['usetss'] = 'usetss';

		} else {
			$finds['usetss'] = '';

		}

		foreach( $finds as $find=>$replace ){
			$adminMarkup = str_replace( '{{' . $find . '}}', $replace, $adminMarkup );
		}
		echo $adminMarkup;
	}

	public function checkAppLogo() {
		$currentAppLogo = Townsquare::get_logo_info();
		$settings = $this->getSavedSettings();

		$appLogo = array(
			'usetownsquaresettings' => ''
		);

		// check if smartapp banner is empty and townsquare is populated
		if (
			/* if townsquare settings are populated */
			( isset($currentAppLogo['apple-low-res']) && $currentAppLogo['apple-low-res'] !== '' ) &&

		   /* if sab settings image url is empty */
		   ( !isset($settings['imageurl-text']) || stripslashes($settings['imageurl-text'] === '') )
		) {
			$appLogo['usetownsquaresettings'] = 1;

		} else if ( $settings['usetownsquaresettings'] == '1') {
			$appLogo['usetownsquaresettings'] = 1;
		}
		return $appLogo;
	}

	public function getSavedSettings(){
        $options = get_option( $this->optionKey, self::$defaultSettings );
        return $options;
    }

	public function saveSettings(){
		$currentAppLogo = Townsquare::get_logo_info();
		$appLogo = self::checkAppLogo();

		if( $_POST && isset( $_POST[ 'SmartAppBanner-save' ] ) ){
			$logocheck = $this->checkAppLogo();

			$settings = $this->getSavedSettings();
			$settings[ 'title-text' ] = $_POST[ 'title-text' ];
			$settings[ 'itunesstoreid-text' ] = $_POST[ 'itunesstoreid-text' ];
			$settings[ 'googleplayappid-text' ] = $_POST[ 'googleplayappid-text' ];
			$settings[ 'dayshidden-text' ] = $_POST[ 'dayshidden-text' ];
			$settings[ 'daysreminder-text' ] = $_POST[ 'daysreminder-text' ];
			$settings[ 'usetownsquaresettings' ] =  isset($_POST[ 'usetownsquaresettings' ])?'1':'0';
			$settings[ 'button-text' ] = $_POST[ 'button-text' ];
			$settings[ 'price-text' ] = $_POST[ 'price-text' ];

			if ($appLogo['usetownsquaresettings'] === '') {
				$settings[ 'imageurl-text' ] = $currentAppLogo['apple-low-res'];

			} else {
				$settings[ 'imageurl-text' ] = $_POST[ 'imageurl-text' ];
			}
			update_option( $this->optionKey, $settings );
			add_action( 'admin_notices', array( $this, 'showSavedNotice' ) );
		}
	}

	public function showSavedNotice(){
		$settings = $this->getSavedSettings();

		?>
		<div class="updated">
			<p><?php _e( 'Settings Updated!', 'SmartAppBanner-text-domain' ); ?></p>
		</div>
		<?php
	}

	public function smartAppBannerHead(){
		$settings = $this->getSavedSettings();
	}

	/* Start Static Singleton */
	protected static $instance;
	public static function init() {
		static::$instance = self::get_instance();
	}
	public static function get_instance() {
		if ( !is_a(static::$instance, __CLASS__) ) {
			static::$instance = new static;
		}
		return static::$instance;
	}
	final public function __clone() {
		trigger_error("No cloning allowed!", E_USER_ERROR);
	}
	final public function __sleep() {
		trigger_error("No serialization allowed!", E_USER_ERROR);
	}
	/* End Static Singleton */

}
// $SmartAppBanner = new SmartAppBanner();