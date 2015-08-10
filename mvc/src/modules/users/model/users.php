<?

/*
 * Users Model
 *
 */

 define("USERS_TABLE", 'users');
 		
class UsersModel extends MVC_Model {

	var $pkey; // primary key

    function UsersModel( $c ) {
	
         parent::MVC_Model( $c );
    
	}

/**
 * Helper Methods
 */

 /**
  * new_password
  * Create temporary password for user registration.
  */
  
function new_password() {
	$str = "";
	srand ((double) microtime() * 1000000);
/**
 * The number one and the upper and lowercase ell
 * have been removed to avoid confusion.
 */
	$letters = "abcdefghijkmnopqrstuvwxyz";
	$numbers = "23456789";
	$temp = rand()%strlen($letters);
	$chr = substr($letters,$temp,1);
	$str .= $chr . $chr;
	$temp = rand()%strlen($numbers);
	$chr = substr($numbers,$temp,1);
	$str .= $chr . $chr;
	$temp = rand()%strlen($letters);
	$chr = substr($letters,$temp,1);
	$str .= $chr . $chr;
	$temp = rand()%strlen($numbers);
	$chr = substr($numbers,$temp,1);
	$str .= $chr . $chr;
	return $str;
}

    function getUsers() {
		// FIXME: specify columns
        $sql = "SELECT * from ". USERS_TABLE;
        $this->result = $this->db->query($sql);
        return $this->fetchToArray();
    }


    function getUser() {

		//log_err( __FILE__, __LINE__, "(Model.getUser())" );
	
	
	/**
	 * Data is retrieved only if it belongs to the user,
	 * i.e. based on their user identity. No url param
	 * is required (except potentially for access by
	 * an administrator.
	 * In this case, since Auth only stores username
	 * it is used to select the correct row.
	 * This whole business of either getting the user id
	 * from a param or from the logged in session, maybe
	 * it would be better to have user module for admins
	 * to edit using param and a 'my' module for editing
	 * based on logged in id. save user, to remain a single
	 * function must determine whether a new user reg is
	 * coming in or a old user is updating. With two ways
	 * of getting values, this may be a problem?
	 */
	
		//$key = $this->c->request->params['id'];
				
		//$key = $this->c->auth->getUsername();
				
		
		// debug
		//print "Username: $this->pkey";
		
		if( empty( $this->pkey ) ) {
			die("Critical Error");
		}
		
		/**
		 * Security Note: Unnecessary to retrieve password.
		 */
		
        $sql = "SELECT userid, username,  usergroup, email, personalname, familyname, interests, optin_announce, optin_newsletter, datecreated
				FROM ". USERS_TABLE ."
				WHERE username=". $this->db->quote($this->pkey);

				//WHERE userid=". $this->db->quote($key);
				
        $this->result = $this->db->query($sql);

	if ( DB::isError( $this->result ) ) {
        die( $this->result->getMessage() );
    }
		return $this->result->fetchRow(DB_FETCHMODE_ASSOC);
    }


	/**
	 * This method is for internal use.
	 * It returns a user profile for a 
	 * known user id without needing input
	 * from CGI params/form or logged in 
	 * session.
	 */
	
    function getUserByUsername($username) {
			
		/**
		 * Security Note: Unnecessary to retrieve password.
		 */
		
        $sql = "SELECT userid, username,  usergroup, email, personalname, familyname, interests, optin_announce, optin_newsletter, datecreated
				FROM ". USERS_TABLE ."
				WHERE username=". $this->db->quote($username);
				
        $this->result = $this->db->query($sql);

	if ( DB::isError( $this->result ) ) {
        die( $this->result->getMessage() );
    }
		return $this->result->fetchRow(DB_FETCHMODE_ASSOC);
    }

	/**
	 * This method is for internal use.
	 * It returns a user profile for a 
	 * known confirm code.
	 */
	
    function getUserByConfirm($confirmcode) {
							
		/**
		 * Security Note: This retrieves password for use
		 * in email to user.
		 */
		
        $sql = "SELECT userid, username, password, usergroup, email, personalname, familyname, interests, optin_announce, optin_newsletter, datecreated
				FROM ". USERS_TABLE ."
				WHERE confirm=". $this->db->quote($confirmcode);

				
        $this->result = $this->db->query($sql);

	if ( DB::isError( $this->result ) ) {
        die( $this->result->getMessage() );
    }
		return $this->result->fetchRow(DB_FETCHMODE_ASSOC);
    }
	
	/**
	 * Sets a new confirmation code on username.
	 * Convenience method to set confirmation
	 * without calling saveUser.
	 * @param username
	 * @param confirmcode
	 */
	
function setConfirm($username, $confirmcode) {
	
	$sql = 'UPDATE '. USERS_TABLE;
	$sql .= ' SET';
	$sql .= ' confirm=';
	$sql .= $this->db->quote($confirmcode);
	$sql .= " WHERE username=".$this->db->quote($username);

	// debug
	//print $sql;
	
	$this->result = $this->db->query($sql);

	if ( DB::isError( $this->result ) ) {
        die( $this->result->getMessage() );
    }
}

	/**
	 * Sets a new password on username.
	 * Convenience method to set password
	 * without calling saveUser.
	 * @param username
	 * @param confirmcode
	 */
	
function setPassword($confirmcode) {
	
	// debug
	// print "$confirmcode";
	
	if( empty($confirmcode) ) {
		die("Critical Error");
	}
	
	$newpass = $this->new_password();
	
	$sql = 'UPDATE '. USERS_TABLE;
	$sql .= ' SET';
	$sql .= ' password=';
	$sql .= $this->db->quote(md5($newpass));
	$sql .= " WHERE confirm=". $this->db->quote($confirmcode);
	
	// debug
	//print $sql;
	
	$this->result = $this->db->query($sql);

	if ( DB::isError( $this->result ) ) {
        die( $this->result->getMessage() );
    }
	return $newpass;
}



	function createUser() {
	
	
	}


	/**
	 * Note: This handles new registrations and updates.
	 * This function presents a slight problem. It assumes
	 * mode is determined by data in the cgi environment and
	 * that data to be saved comes from cgi params, which makes
	 * it nearly impossible to use this to save data from within
	 * the application. This means a user object can't truly
	 * save itself. This code is from the original MVC code
	 * I borrowed. Should it work with a model of the form or
	 * with a model of the user entity? Should a saveUser method
	 * take its values from user object properties and fill those
	 * values from the form object somehow when handling new user
	 * create or an update from a form? I suppose the users model
	 * object is the same as a user object. I suppose in a mvc
	 * framework objects that are part of the framework do not
	 * have an independent existience. So a user object has to be
	 * a user-model object. Ideally, I should have a saveUser method
	 * that saves the values of the user profile without getting them
	 * from the cgi params or form object. So that if somewhere in the
	 * code, like in response to a password reset, I can just change
	 * the password property of the user object and then call saveUser
	 * on it.
	 
	  You know, this is all very clever to have one function that intelligently inserts or updates, creates or modifies data, based on the information it is given, but it is pain in the you know what.

	It fails to recognize that there are different sources of data.
	
	If a logged in user is updating their data, the key must be taken from the username in the session.

	If an admin or someone else with permission (no one at this time) is updating data for a user (another user, not self-update), the key must be taken from the cgi environment.

	If a person is registering, the key should not be present.
	
	So if a request comes and there is no session logged in then we know NOT to use the session username as a key. We can then decide whether a key suggests in the cgi enviroment, if not, then we can do an insert.

if ! session->username
	if ! cgi key
		insert
	else
		update using cgi key
else
update using session->username key

Note: I decided to go with using a property to carry the value to the model. It ought to be moding the user anyway.
	
	
	
	 */
	
    function saveUser() {
	
	//print "Creating: Entering saveUser";
	
	/**
	 * Note: if 'id' is used to determine whether an existing user
	 * is to be updated or a new user is to be created, then both
	 * Add and Edit forms must use the same name for the user id.
	 * Well, actually Add does not provide a user id, since none
	 * yet exists? I suppose the unique id primary key does not
	 * but of course the username does.
	 */
	
	/* What is really stupid about this is if we have a model of the 
	form available, in the form object, why the hell can't this code
	look to the form model to coordinate the form fields it expects
	to find values in with the values in the database? Why do I have
	to manually match up form template, form object and fields to
	columns? It seems pretty arbitrary that if I change the name of a
	form field on the template it breaks critical behavoir of choosing
	whether to update or insert.
	*/
	
	/*
	The first thing to do is determine whether the Add form or the Edit form is being submitted. (It might be better to create separate update methods since the registration process and form is so different from the edit profile process and form?)
The id cannot be used, since it is not available in the logged in session, only username.

Okay, this is a real problem. If I get key from username, then register/add fails becuase THERE IS ALWAYS A KEY present and existience of a key is used to decide whether to insert or update.




	*/
	
		// Deprecated: get id from params object
        //$key = $_REQUEST['username'];
	/*
	
	Changing this to get key from property. This cod eshould not have
	make decisions about what form is invoking the action. It should
	only base its action on whether a key is present or not.
	
		$key = $this->c->auth->getUsername();
		
	*/
	
		// FIXME: change this on other modules
        if ( empty($this->pkey) ) {
	   
		// debug
		//print "Inserting";
	   
	   //print "MD5'ing Password: " . $this->c->request->params['password'];


$password = md5( $this->c->request->params['password'] );

// FIXME: pattern other modules on this EXCEPT I want to convert all to INSERT SET form


// FIXME: THIS HAS CHANGED TO SINGLE INPUT
 
 /**
  * Email options are represented by an array.
  * Currently returns: Array ( [0] => 1 [1] => 2 )
  * 1 = optin_newsletter 2 = optin_aanouncements
  * I think it would be useful if there were some way to
  * refer to the checkbox values individually. Like
  * checkbox.0 etc.
  * One can look for a particular value among the multiple
  * values returned, but it may be easier to check if a
  * particular box is checked. Of course, I could just
  * use two single checkboxes (but each would still require
  * a iterative template element).
  */
  
/**
 * Handle email options checkbox group
 */
 
 // debug
 //print "<p>Email Options:</p>";
 //print_r( $this->c->request->params['email_options'] );
 
// avoid NULL values
$optin_newsletter = 0;
$optin_announce = 0;
if( ! empty($this->c->request->params['email_options']) ) {
	
	if( in_array(1, $this->c->request->params['email_options']) ) { 
		$optin_newsletter = 1;
	}
	if( in_array(2, $this->c->request->params['email_options']) ) { 
		$optin_announce = 1;
	}
}


$ip = $_SERVER['REMOTE_ADDR'];
	
	
	//INSERT INTO users (userid, username, password, usergroup, email, personalname, familyname, interests, optin_announce, optin_newsletter, datecreated, confirm, ip) VALUES (NULL,'test','202cb962ac59075b964b07152d234b70',0,'knoblock_private@city-gallery.com','s','k','\r\n\r\ntest',0,0,NOW(),)

/**
 * Note: The password is not updated here. I changed my mind about
 * users updating from the profile edit form and decided to provide
 * a link to reset password.
 */

$sql="INSERT INTO ". USERS_TABLE ."
	(userid, username, usergroup, password, email, personalname, familyname, interests, optin_announce, optin_newsletter, usertype, datecreated, confirm, ip)
	VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";

$sql = sprintf($sql,
	'NULL',
	$this->db->quote($this->c->request->params['username']),
	$this->db->quote(0),
	$this->db->quote($password),
	$this->db->quote($this->c->request->params['email']),
	$this->db->quote($this->c->request->params['personalname']),
	$this->db->quote($this->c->request->params['familyname']),
	$this->db->quote($this->c->request->params['interests']),
	$this->db->quote($optin_announce),
	$this->db->quote($optin_newsletter),
	$this->db->quote($this->c->request->params['usertype']),
	'NOW()',
	$this->db->quote(''),
	$this->db->quote($ip)
);

 	$this->result = $this->db->query($sql);
	
	// debug
	//print $sql;
		
	if ( DB::isError( $this->result ) ) {
        die( $this->result->getMessage() );
    }
        } else {
	 
	 //print "Updating";
	 
	 /**
	  * IMPORTANT: USERS SHOULD NOT BE ABLE TO SELF UPDATE THEIR TYPE. Only
	  * admins should be able to do this.
	  */


$sql = 'UPDATE '. USERS_TABLE;

// FIXME: is this necesary? It may be a security risk and violate business logic.

/**
 Deprecated: This is a problem because we really don't need to set username on update. The user should not be changing their user name and I do not see any reason why an admim would either. This is just a result of being universal about
the fields being updated, as if I were looping through all fields.
	$sql .= ' SET username=';
	$sql .= $this->db->quote($this->c->request->params['username']);
	
	This presents another complicated comma fiasco. Solution is to move a field always presnt up to the begining.
	
*/

$sql .= ' SET ';

	$sql .= ' personalname=';
	$sql .= $this->db->quote($this->c->request->params['personalname']);
	$sql .= ', familyname=';
	$sql .= $this->db->quote($this->c->request->params['familyname']);

	/**
	  * Conditional validations are hard to do with a
	  * limited form handler.
	  * It would need information about how fields relate to each
	  * other. Trouble is, response can't be handled within form
	  * reponse framework.
	  */


/**
 * Note: The password is not updated here. I changed my mind about
 * users updating from the profile edit form and decided to provide
 * a link to reset password.
 */
 
/*	 
	 if( ! empty($this->c->request->params['password']) )  {
		if( empty($this->c->request->params['password']) && empty($this->c->request->params['confirm_password']) )  {
			 	
			die("Error - your password does not match confirm password");	
			 
		}

	$sql .= ', password=';
	$sql .= $this->db->quote($this->c->request->params['password']);

	 }
*/

	$sql .= ', email=';
	$sql .= $this->db->quote($this->c->request->params['email']);
	$sql .= ', interests=';
	$sql .= $this->db->quote($this->c->request->params['interests']);	

	
/**
 * Handle email options checkbox group
 */
// print "<p>Email Options:</p>";
 //print_r( $this->c->request->params['email_options'] );
 
// avoid NULL values
$optin_newsletter = 0;
$optin_announce = 0;
if( ! empty($this->c->request->params['email_options']) ) {
	
	if( in_array(1, $this->c->request->params['email_options']) ) { 
		$optin_newsletter = 1;
	}
	if( in_array(2, $this->c->request->params['email_options']) ) { 
		$optin_announce = 1;
	}
}

	$sql .= ', optin_announce=';
	$sql .= $this->db->quote($optin_announce);
	$sql .= ', optin_newsletter=';
	$sql .= $this->db->quote($optin_newsletter);
	
	/**
	 * Note: For users self-updating their profile, the id must come
	 * from a logged in session. For administrators, other users who
	 * could have permission to edit a user's profile, the id must
	 * come from the request params.
	 */
	
	// if we know the user name then update on it, but if we don't we
	// need the user id
	// Hmm, it seems to know the userid, where is it getting it from?
	// Ah, it gets it from the form, because user id is on the form
	// coming from the data retrieval and the placeholder for id
	
	/**
	Use key
	$sql .= 	
	" WHERE username=".$this->db->quote($this->c->request->params['username']);
	*/
	
	$sql .= 	
	" WHERE username=".$this->db->quote($this->pkey);

	// debug
	// print $sql;
	exit;
	
	$this->result = $this->db->query($sql);


	if ( DB::isError( $this->result ) ) {
        die( $this->result->getMessage() );
    }
	
		}

    }



} // end of UsersModel


?>