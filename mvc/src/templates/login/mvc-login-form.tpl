{* debug *}
      <form action="index.php" method="post">
         <div style="text-align: center">
            <table style="border-collapse: collapse" bordercolor="#111111"
            cellpadding="5" cellspacing="0">
               <tr>
                  <td valign="top">
                     Username:
                  </td>
                  <td valign="top" align="center">
                     <input type="text" name="username" size="20" value="" />
                  </td>
               </tr>
               <tr>
                  <td valign="top">
                     Password:
                  </td>
                  <td valign="top" align="center">
                     <input type="password" name="password" size="20" value="" />
                  </td>
               </tr>
               <tr>
                  <td colspan="2" align="center" valign="top">
                     <!--
					 <input type="checkbox" name="keep_login" value="1" /> Keep
                     me logged in
					 -->
                  </td>
               </tr>
            </table>
            <br />
         </div>
          
			<input type="hidden" name="rebound" value="{$form.rebound.value}" />
         
            <input type="hidden" name="action" value="login/login" />
			<input type="hidden" name="submitted" value="1" />
			<input type="submit" name="login" value="Sign In" />
         
      </form>

