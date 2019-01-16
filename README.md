Detect Mobile
=============

Lightweight PHP plugin for EE5 that detects a mobile browser using the [PHP Mobile Detect class](http://mobiledetect.net/). 
Contains a bundled version of **2.8.33**. For EE2 support see the [EE2 tagged release](https://github.com/garethtdavies/detect-mobile/releases/tag/ee2).

To install simply copy the `detect_mobile` directory to `system/user/addons` and install the add-on via the [Add-On manager](https://docs.expressionengine.com/latest/cp/addons/index.html).

Basic Usage
=============

**Check if any mobile device**

*Returns true or false for use in conditionals see later examples*

```{exp:detect_mobile:ismobile}```
        
**Check if not a mobile device**

```{exp:detect_mobile:isnotmobile}```
        
**Check if tablet**

```{exp:detect_mobile:istablet}```
        
**Check if phone**

```{exp:detect_mobile:isphone}```
        
### Conditional check for a mobile device

```
{if '{exp:detect_mobile:ismobile}'}
    I am a mobile device
{if:else}	
    I am not a mobile device
{/if}
```

**Redirect any mobile device including tablets**

```{exp:detect_mobile:redirect location="mobile.mysite.com"}```
        
**Redirect all non-tablet mobile devices**

```{exp:detect_mobile:redirect location="mobile.mysite.com" tablet="no"}```
        
**Redirect just tablets and not mobiles**

```{exp:detect_mobile location="tablet.mysite.com" mobile="no"}```
        
**Seperate redirect locations for tablets and mobiles**

```{exp:detect_mobile tablet_location="tablet.mysite.com" location="mobile.mysite.com"}```
        
**Check for device type**

```{exp:detect_mobile:type}```

*returns phone, tablet or none*

### Conditional check for device type

```
{if '{exp:detect_mobile:type}' == "tablet"}	
    I am a tablet
{if:elseif '{exp:detect_mobile:type}' == "phone"}	
    I am a mobile phone
{if:else}
    I am not a mobile device
{/if}
```    
