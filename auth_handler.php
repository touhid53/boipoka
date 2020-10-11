<?php
//start session
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/controller/users/user_handler.php');


// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);


//include google api files
include_once 'vendor/autoload.php';



// New Google client
$gClient = new Google_Client();
$gClient->setApplicationName('BoiPoka');
$gClient->setAuthConfigFile('client_secret.json');
$gClient->setAccessType('offline');
$gClient->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$gClient->addScope(Google_Service_Oauth2::USERINFO_EMAIL);

$google_redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . '/auth_handler.php';

// New Google Service
$google_oauthV2 = new Google_Service_Oauth2($gClient);



// LOGOUT?
if (isset($_REQUEST['logout'])) {
    $gClient->revokeToken($_SESSION['token']);
    unset($_SESSION["auto"]);
    unset($_SESSION['token']);
    session_destroy();
    header('Location: ' . filter_var('index', FILTER_SANITIZE_URL)); //redirect user back to page
}




// GOOGLE CALLBACK?
if (isset($_GET['code'])) {
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
    return;
}

// PAGE RELOAD?
if (isset($_SESSION['token'])) {
    $gClient->setAccessToken($_SESSION['token']);
}




// LOGGED IN?
if ($gClient->getAccessToken()) //------------------- Sign in
{
    //For logged in user, get details from google using access token
    try {
        $user = $google_oauthV2->userinfo->get();
        $google_id              = $user['id'];
        $user_name            = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email                = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
        $gender               = filter_var($user['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
        $profile_image_url    = filter_var($user['picture'], FILTER_VALIDATE_URL);
        
        
        $get_user = users::get_user($google_id);
        if ($get_user == 0) {
            echo " User Not regesterd yet <br>";
            users::signUp($google_id,$user_name,$email,$profile_image_url,$gender);
            $get_user = users::get_user($google_id);
        }else {
            // echo "<pre>^^^^^^^^^^";
            // print_r($get_user);
            // echo "</pre>";
            
            # code...
            // $_SESSION['token']    = $gClient->getAccessToken();
        }
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $get_user['id'];
        $_SESSION['gid'] = $google_id;
        $_SESSION['user_name'] = $get_user['name'];
        $_SESSION['user_photo'] = $get_user['image_link'];
        //send user data to user handler for further action
        //-----------------------------------
        //where to save data temporaryly : session / cookies
        // echo "<pre>^^^^^^^^^^";
        // print_r($_SESSION);
        // echo "</pre>";
        
        header('Location: ' . filter_var('profile', FILTER_SANITIZE_URL));


    } catch (Exception $e) {
        // The user revoke the permission for this App! Therefore reset session token	
        unset($_SESSION["auto"]);
        unset($_SESSION['token']);
        header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
    }
} else if (isset($_REQUEST['login'])) //------------------- login
{
    //For Guest user, get google login url
    $authUrl = $gClient->createAuthUrl();

    // Fast access or manual login button?
    if (isset($_GET["auto"])) {
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    } else {
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    }
}