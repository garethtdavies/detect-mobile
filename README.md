Detect Mobile
=============

Lightweight PHP plugin for EE2 that detects a mobile browser using the PHP Detect Mobile class (http://mobiledetect.net/)

Basic Usage
=============

Check if any mobile device

{exp:detect_mobile:ismobile} - returns true or false

Redirect any mobile device including tablets

{exp:detect_mobile:redirect location="mobile.mysite.com"}

Redirect all non-tablet mobile devices

{exp:detect_mobile:redirect location="mobile.mysite.com" tablet="no"}

Redirect just tablets and not mobiles

{exp:detect_mobile location="tablet.mysite.com" mobile="no"}

Seperate locations for tablets and mobiles

{exp:detect_mobile tablet_location="tablet.mysite.com" location="mobile.mysite.com"}

Conditional check for a mobile device

{if 'exp:detect_mobile:ismobile'}
    	I am a mobile device
{else}
	I am not a mobile device
{/if}

Check for device type

{exp:detect_mobile:type} - returns phone, tablet or none

Conditional check

{if 'exp:detect_mobile:type' == "tablet"}
	I am a tablet
{elseif 'exp:detect_mobile:type' == "phone"}
	I am a mobile phone
{else}
	I am not a mobile device
{/if}
    
