<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
   <head>
      <!-- FarmFoody theme by Steve Knoblock (c)2006 -->
      <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
      <title>
         FarmFoody.com
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
            <div style="color: black; text-align: right">
{if $AUTH_USER_NAME}

Hello, <a href="http://{$WEB_ROOT}/index.php?action=users/edit">{$AUTH_USER_NAME}</a> 
{else}
<a href="http://{$WEB_ROOT}/index.php?action=users/register">register</a> | <a href="http://{$WEB_ROOT}/index.php?action=login/login">login</a>
{/if}
            </div>
         </div>
      </div>
      <!-- end header -->
      <!-- begin navigation -->
      <div id="navigation">
         <!-- NAVIGATION -->
         <ul>
            <li id="menu-empty">
               &nbsp;
            </li>
            <li id="menu-video">
               <!-- CAUTION: Link altered for mockup! --><a href=
               "http://{$WEB_ROOT}/">Home</a>
            </li>
			<!--
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
			-->
         </ul>
      </div>
      <div id="feature">
         <table summary="Table used for layout" border="0" cellspacing="2"
         cellpadding="2">
            <tr valign="top">
               <td>
			    
                  <p>
                     FarmFoody is a social networking site for people who love and grow good food. 
                  </p>
                  <!-- begin buttonbox -->
                  <div class="buttonbox">
                     <a href="pages/about.html">Learn More</a>
                  </div>
                  <!-- end buttonbox -->
                  <!-- begin buttonbox -->

                  <div class="buttonbox">
                     <a href="{$WEB_ROOT}/index.php?action=users/register">Join Us!</a>
                  </div>
                  <!-- end buttonbox -->
               </td>
               <td>
               </td>
               <td width="50%">
			   		   
                  <div class="keypoint">
				  <img src="{$THEME_IMAGES_URL}/twochicks.png" width="72" height="150" alt="Fun two chicks art." style="float: left" />
				  <p> <span style="font-size: larger">Good food. Good people.</span> That is our motto. FarmFoody is a social networking site for farmers and consumers of farm goods. FarmFoody brings together "foodies" (people who love to eat and cook with fresh ingredients) and the farmers who grow them.
				  </p>
				  </div>
               </td>
            </tr>
         </table>
      </div>
      <!-- end navigation -->
      <div id="content">
         
      </div>
      
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