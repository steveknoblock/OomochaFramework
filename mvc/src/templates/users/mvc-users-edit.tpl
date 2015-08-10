
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
   <head>
      <!-- FarmFoody theme by Steve Knoblock (c)2006 -->
		<meta http-equiv="Content-Type" content="{$SITE_CHARSET}" />
		<meta http-equiv="Content-Language" content="en-us" />
<!-- link rel="STYLESHEET" type="text/css" href="{$app_root}/styles/screen.css" / -->

      <title>
         {$SITE_TITLE}
      </title>
		<style type="text/css">
		        @import url("{$THEME_STYLES_URL}/common.css");
		        @import url("{$THEME_STYLES_URL}/layout.css");
		        @import url("{$THEME_STYLES_URL}/content.css");
		        /* @import url("css/detail.css"); */
		</style>
   </head>
   <body>
      <!-- begin header -->
      <div id="header">
         <div id="headerleft">
            <img src="{$THEME_IMAGES_URL}/FarmFoodyLogo.png" width="248" height="60" alt="">
         </div>
         <div id="headerright">
            <!-- HEADER RIGHT -->
            <div style="text-align: right">
			<!--
               <a href="{$WEB_ROOT}/index.php?action=users/register">
register
</a> | <a href="{$WEB_ROOT}/index.php?action=login/login">
login
</a>
-->
            </div>
         </div>
      </div>
      <!-- end header -->
      <!-- begin navigation -->
      <div id="navigation">
         <ul>
            <li id="menu-empty">
               &nbsp;
            </li>
            <li id="menu-video">
               <!-- CAUTION: Link altered for mockup! --><a href=
               "{$WEB_ROOT}/">Home</a>
            </li>
			<li id="menu-video">
               <!-- CAUTION: Link altered for mockup! --><a href=
               "{$WEB_ROOT}/stuff.html">Stuff</a>
            </li>
            <li id="menu-chapters">
               <!-- CAUTION: Link altered for mockup! --><a href=
               "{$WEB_ROOT}/tags.html">Tags</a>
            </li>
			<li id="menu-resources">
               <!-- CAUTION: Link altered for mockup! --><a href=
               "{$WEB_ROOT}/farms.html">Farms</a>
            </li>
            <li id="menu-resources">
               <!-- CAUTION: Link altered for mockup! --><a href=
               "{$WEB_ROOT}/foodies.html">Foodies</a>
            </li>
			
         </ul>
      </div>
      <!-- end navigation -->
	  
	  <!-- begin content -->
      <div id="content">
      {$form_view}   
      </div>
      <!-- end content -->
	  
	  <!-- begin footer -->
	  <div id="footer">
         <div id="copyright">
              &copy; 2006 the FarmFoody group unless otherwise noted. All rights reserved.<br /> Portions &copy; 2006 Steve Knoblock. All rights reserved.<br /> Trademarks are property of their respective owners.

         </div>
         <div id="textnav">
		 <!--
            <a href="contact.html">Contact</a> | <a href=
            "about.html">About</a> | <a href="legal.html">Terms Of Use</a>
			-->
         </div>
      </div>  
	  		   
      <!-- end footer -->
   </body>
</html>
