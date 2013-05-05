<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Detect Mobile Plugin for ExpressionEngine 2
 *
 * @package     ExpressionEngine
 * @category    Plugin
 * @author      Gareth Davies
 * @copyright   Copyright (c) 2013
 * @link        http://www.garethtdavies.com
 */
 
$plugin_info = array(
					'pi_name'			=> 'Detect Mobile',
					'pi_version'		=> '1.0.6',
					'pi_author'			=> 'Gareth Davies',
					'pi_author_url'		=> 'http://www.garethtdavies.com',
					'pi_description'	=> 'Plugin that detects a mobile browser using the PHP Detect Mobile class',
					'pi_usage'			=> Detect_mobile::usage()
					);


class Detect_mobile {
	
	public $return_data = "";
	private $isTablet = "";
	private $isMobile = "";
	
	// --------------------------------------------------------------------
	
	/**
     * Constructor
     */

    public function __construct()
    {
        $this->EE =& get_instance();
		
		//Load the Mobile Detect Class
		$this->EE->load->library( 'Mobile_detecter' );
		
		//Perform the device detection
		$this->isTablet = $this->EE->mobile_detecter->isTablet();
		$this->isMobile = $this->EE->mobile_detecter->isMobile();
			
    }
	
	// --------------------------------------------------------------------

	/**
     * ismobile function
	 * This function simply returns true or false depending on whether a mobile is detected
     */
	 
	public function ismobile()
	{
		$this->isMobile ? $this->return_data = TRUE : $this->return_data = FALSE;
		return $this->return_data;
	}
	
	/**
     * isnotmobile function
	 * This function simply returns true or false depending on whether a mobile is detected
     */
	 
	public function isnotmobile()
	{
		$this->isMobile ? $this->return_data = FALSE : $this->return_data = TRUE;
		return $this->return_data;
	}
	
	/**
     * istablet function
	 * This function simply returns true or false depending on whether a tablet is detected
     */
	 
	public function istablet()
	{
		$this->isTablet ? $this->return_data = TRUE : $this->return_data = FALSE;
		return $this->return_data;
	}
	
	/**
     * isphone function
	 * This function simply returns true or false depending on whether a phone and not tablet is detected
     */
	 
	public function isphone()
	{
		$this->isMobile && !$this->isTablet ? $this->return_data = TRUE : $this->return_data = FALSE;
		return $this->return_data;
	}
	
	// --------------------------------------------------------------------
	
	/**
     * type function
	 * This function simply returns the type of the device
     */
	 
	public function type()
	{	
		if ( $this->isTablet )
		{
			$this->return_data = "tablet";
		}
		elseif ( $this->isMobile )
		{
			$this->return_data = "phone";
		}
		else
		{
			$this->return_data = "none";	
		}
		
		return $this->return_data;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
     * redirect function
	 * This function simply redirects the user to the location parameter specified using ExpressionEngine redirect method
     */
	 
	public function redirect()
	{
		//Retreieve the plugin parameters
		$location = $this->EE->TMPL->fetch_param('location');
		$tablet_location = $this->EE->TMPL->fetch_param('tablet_location');
		$tablet = strtolower($this->EE->TMPL->fetch_param('tablet'));
		$mobile = strtolower($this->EE->TMPL->fetch_param('mobile'));
		
		if( !empty( $location ) )
		{
			if ($this->isTablet && $tablet == "no")
			{
				//A tablet device and don't want to redirect tablets
				return;	
			}
			elseif ($this->isTablet && !empty( $tablet_location ))
			{
				//A tablet specific page to redirect to
				$this->EE->functions->redirect($tablet_location);
				return;
			}
			elseif ($this->isTablet)
			{
				//A tablet that is going to the mobile location
				$this->EE->functions->redirect($location);	
				return;
			}
			elseif ($this->isMobile && $mobile != "no")
			{
				//A mobile device including tablets
				$this->EE->functions->redirect($location);
				return;
			}
			else
			{
				//Not a mobile device or we don't want to redirect anything
				return;	
			}
		}
		else
		{
			//No location for redirect specified so let's get out of here
			return;	
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
     * Usage
     *
     * This function describes how the plugin is used.
     *
     * @access  public
     * @return  string
     */
    public static function usage()
    {
     	ob_start();  ?>

		Lightweight PHP plugin for EE2 that detects a mobile browser using the PHP Detect Mobile class (http://mobiledetect.net/)
		
        Basic Usage
        =============
        
        Check if any mobile device
        {exp:detect_mobile:ismobile} - returns true or false
        
        Check if not a mobile device
        {exp:detect_mobile:isnotmobile}
        
        Check if tablet
        {exp:detect_mobile:istablet}
        
        Check if phone
        {exp:detect_mobile:isphone}
        
        Conditional check for a mobile device
        {if '{exp:detect_mobile:ismobile}'}
        	I am a mobile device
        {if:else}
        	I am not a mobile device
        {/if}
        
        Redirect any mobile device including tablets
        {exp:detect_mobile:redirect location="mobile.mysite.com"}
        
        Redirect all non-tablet mobile devices
		{exp:detect_mobile:redirect location="mobile.mysite.com" tablet="no"}
        
        Redirect just tablets and not mobiles
        {exp:detect_mobile location="tablet.mysite.com" mobile="no"}
        
        Seperate locations for tablets and mobiles
        {exp:detect_mobile tablet_location="tablet.mysite.com" location="mobile.mysite.com"}
        
        Check for device type
        {exp:detect_mobile:type} - returns phone, tablet or none
        
        Conditional check
        {if '{exp:detect_mobile:type}' == "tablet"}
        	I am a tablet
        {if:elseif '{exp:detect_mobile:type}' == "phone"}
        	I am a mobile phone
        {if:else}
        	I am not a mobile device
        {/if}
        <?php
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }
    // END
}

/* End of file pi.mobile_deetect.php */
/* Location: ./system/expressionengine/third_party/mobile_deetect/pi.mobile_deetect.php */
