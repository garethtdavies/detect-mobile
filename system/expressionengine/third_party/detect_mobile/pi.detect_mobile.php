<?php if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

include(PATH_THIRD . 'detect_mobile/ee_fallback.php');

/**
 * MIT License
 * ===========
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *
 * Detect Mobile Plugin for ExpressionEngine 2
 *
 * @package     ExpressionEngine
 * @category    Plugin
 * @author      Gareth Davies
 * @copyright   Copyright (c) 2013-2018
 * @link        http://www.garethtdavies.com
 */

$plugin_info = array(
	'pi_name' => 'Detect Mobile',
	'pi_version' => '1.1',
	'pi_author' => 'Gareth Davies',
	'pi_author_url' => 'http://www.garethtdavies.com',
	'pi_description' => 'Plugin that detects a mobile browser using the PHP Detect Mobile class',
	'pi_usage' => Detect_mobile::usage()
);


class Detect_mobile
{

	private $isTabletDetected = false;
	private $isMobileDetected = false;
	private $isSetUp = false;

	// --------------------------------------------------------------------

	/**
	 * This function simply returns true or false depending on whether a mobile is detected.
	 *
	 * @return bool
	 */
	public function ismobile()
	{
		$this->setUp();

		return $this->isMobileDetected;
	}

	/**
	 * This function simply returns true or false depending on whether a mobile is detected.
	 *
	 * @return bool
	 */
	public function isnotmobile()
	{
		return ! $this->ismobile();
	}

	/**
	 * This function simply returns true or false depending on whether a tablet is detected.
	 *
	 * @return bool
	 */
	public function istablet()
	{
		$this->setUp();

		return $this->isTabletDetected;
	}

	/**
	 * This function simply returns true or false depending on whether a phone and not tablet is detected.
	 *
	 * @return bool
	 */
	public function isphone()
	{
		$this->setUp();

		return ($this->isMobileDetected && !$this->isTabletDetected);
	}

	/**
	 * This function simply returns true or false depending on whether a phone and not tablet is detected.
	 *
	 * @return bool
	 */
	public function isnotphone()
	{
		return !$this->isphone();
	}

	// --------------------------------------------------------------------

	/**
	 * This function simply returns the type of the device.
	 *
	 * @return string
	 */
	public function type()
	{
		$this->setUp();

		if ($this->isTabletDetected)
		{
			return "tablet";
		}
		elseif ($this->isMobileDetected)
		{
			return "phone";
		}

		return "none";
	}

	// --------------------------------------------------------------------

	/**
	 * This function simply redirects the user to the location parameter specified using ExpressionEngine redirect method
	 */
	public function redirect()
	{
		$this->setUp();

		//Retreieve the plugin parameters
		$location = ee()->TMPL->fetch_param('location');
		$tablet_location = ee()->TMPL->fetch_param('tablet_location');
		$tablet = strtolower(ee()->TMPL->fetch_param('tablet'));
		$mobile = strtolower(ee()->TMPL->fetch_param('mobile'));

		if (!empty($location))
		{
			if ($this->isTabletDetected && $tablet == "no")
			{
				//A tablet device and don't want to redirect tablets
				return;
			}
			elseif ($this->isTabletDetected && !empty($tablet_location))
			{
				//A tablet specific page to redirect to
				ee()->functions->redirect($tablet_location);
				return;
			}
			elseif ($this->isTabletDetected)
			{
				//A tablet that is going to the mobile location
				ee()->functions->redirect($location);
				return;
			}
			elseif ($this->isMobileDetected && $mobile != "no")
			{
				//A mobile device including tablets
				ee()->functions->redirect($location);
				return;
			}
			else
			{
				//Not a mobile device or we don't want to redirect anything
				return;
			}
		}

		//No location for redirect specified so let's get out of here
		return;
	}

	/**
	 * Loads the mobile detector library and sets the isTabletDetected and isMobileDetected variables.
	 */
	private function setUp()
	{
		if (!$this->isSetUp)
		{
			$this->isSetUp = true;

			//Load the Mobile Detect Class
			ee()->load->library('Mobile_Detect');

			//Perform the device detection
			$this->isTabletDetected = ee()->mobile_detect->isTablet();
			$this->isMobileDetected = ee()->mobile_detect->isMobile();
		}
	}

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
		ob_start(); ?>

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

		Check if not a phone
		{exp:detect_mobile:isnotphone}

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