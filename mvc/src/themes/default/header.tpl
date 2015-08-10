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