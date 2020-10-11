<?php
/**
 * profile view by direct link \n
 * boipoka.org/profile?id=1  
 * check my profile view or other view
 */
session_start();
  

  /**
   * - user not logged in
   * - does user want to view some profile? => view
   * - or, go to login page
   */
  if (!isset($_SESSION['token'])) :
    //adjust navbar link 
    if (isset($_GET['id'])) {
      include_once($_SERVER['DOCUMENT_ROOT'].'/controller/users/user_handler.php');
      include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/header.php');
      
      notLoggedInProfileView($_GET['id']);
    }else{
      header('Location: auth_handler.php?login');
      exit; 
    }
    /**
     * or, user is logged in,
     * dose user want to view some profile?
     * - - - is that users own profile? => 
     * or some other profile => 
     */
  else:
    include_once($_SERVER['DOCUMENT_ROOT'].'/controller/users/user_handler.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/header.php');
    
    
    if (isset($_GET['id'])) {
      // echo "user logged in, and want to : ";
      if ($_GET['id'] == $_SESSION['user_id']) {
        viewOwnProfile();
      }else {
        loggedInProfileView($_GET['id']);
      }
    }else {
      viewOwnProfile();
    }
    
  endif;
  
    /**
     * User Not Logged in, But want to view some profile
     * - shows profile overview
     *
     */
    function notLoggedInProfileView($id){
      // echo "user not logged in, but want to view some profile";
      $user = users::get_user($id);
      
      if ($user) {
        profilePageBuilder($user);
      } else {
        echo "<h2 class='text-center text-danger p-3'>User Not Found</h2>";

      }
      
    }
    

    /**
     * - user logged in, view other user profile
     *
     *
     */
    function loggedInProfileView($id)
    {
      // echo " view other profile. as logged in state";
      $user = users::get_user($id);

      if ($user) {
        profilePageBuilder($user);
      } else {
        echo "<h2 class='text-center text-danger p-3'>User Not Found</h2>";
      }
    }


    /**
     * - As logged in state View own Profile
     *
     */
    function viewOwnProfile()
    {
      // echo "View Own Profile";
      $user = users::get_user($_SESSION['gid']);
      profilePageBuilder($user);
    }

    /**
     * - get $user as param
     * - which contains user data
     * - whose profile will be displayed
     * - main profile page view biulder
     *
     * @param [type] $user
     * @return void
     */
    function profilePageBuilder($user)
    {
      include_once($_SERVER['DOCUMENT_ROOT'].'/view/profile/profileHeader.php');
      include_once($_SERVER['DOCUMENT_ROOT'].'/view/profile/profileBody.php');
    }

?>

  <!-- header finished , add page title-->
  <title>Boipoka:Profile</title>



<!-- footer start -->
<?php
  include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/footer.php');
  // endif;
?>