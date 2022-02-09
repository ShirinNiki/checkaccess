<?php
/*
 * Plugin Name: CHECK ACCESS APP TO SITE
 * Plugin URI: 
 * Description: ACCESS TO APP
 * Author: Shirin Niki
 * Author URI: 
 * Version: 1.0.1
 */


// define API Keys.
define( 'WP_CONSUMER_KEY', 'ck_6d2c7a515f1af9ff9a2fe0f58e9509884c9a0961' );
define( 'WP_CONSUMER_SECRET', 'cs_73c59e0b31a815a1dcc3e553a3e89a33ab369c1d' );
 
function validate_authorization_header() {
    $headers = apache_request_headers();
    if ( isset( $headers['authorization'] ) ) {
        $wc_header =  base64_encode( WP_CONSUMER_KEY . ':' . WP_CONSUMER_SECRET );
        if ( $headers['authorization'] == $wc_header ) {
            return true;
        }
    }
    return false;
    //return true;
}
add_action(
    'rest_api_init',
    function () {
        register_rest_route(
            '/api',
            '/login',
            array(
                'methods'  => 'POST',
                'callback' => 'login',
            )
        );
    }
);
function login( WP_REST_Request $request ) { 
    if ( validate_authorization_header() || true ) {       
        $arr_request = json_decode( $request->get_body() );
            
        if ( ! empty( $arr_request->email ) && ! empty( $arr_request->password ) ) {
            // this returns the user ID and other info from the user name.
            $user = get_user_by( 'email', $arr_request->email );

            $res = wp_login($arr_request->email, $arr_request->password );
            // Get the contents of the JSON file 
            $strJsonFileContents = file_get_contents("css-color-names.json");
            // Convert to array 
            $array = json_decode($strJsonFileContents, true);
            var_dump($array);
 
            if ( ! $user ) {
                // if the user name doesn't exist.           
                return [
                    'status' => false,
                    'message' => 'Incorrect Email',
                    
                ];
            }
  
            // check the user's login with their password.
            if ( ! wp_check_password( $arr_request->password, $user->user_pass, $user->ID ) ) {
                // if the password is incorrect for the specified user.
                return [
                    'status' => false,
                    'message' => 'Incorrect Password',
                ];
            }
			//return user auhenticate and data to a custom app needs
            $res = wp_authenticate($arr_request->email, $arr_request->password );
            return [
                'status' => 'OK',
                'message' => 'User credentials are correct.',
                'fullname' => $res->data->user_nicename,
                "today" =>  date("Y-m-d"),
                "link" => "http://dl.ibtil.org/ibtil/",
                "ibtil" => "xxxx",
                "gui"=> "yyyy",
                "toefl"=> "zzzz",
                "ielts"=> "wwww",
                "gre"=> "yyyy",
                "tests"=>"10001_T0_2022-01-01,10002_T0_2022-01-01,10003_T0_2022-01-01,10004_T0_2022-01-01,10005_T0_2022-01-01,10006_T0_2022-01-01,10007_T0_2022-01-01,10008_T0_2022-01-01,10009_T0_2022-01-01,10010_T0_2022-01-01,10011_T0_2022-01-01,10012_T0_2022-01-01,10013_T0_2022-01-01,10014_T0_2022-01-01,10015_T0_2022-01-01,10016_T0_2022-01-01,10017_T0_2022-01-01,10018_T0_2022-01-01,10019_T0_2022-01-01,10020_T0_2022-01-01,10021_T0_2022-01-01,10022_T0_2022-01-01,10023_T0_2022-01-01,10024_T0_2022-01-01,10025_T0_2022-01-01,10026_T0_2022-01-01,10027_T0_2022-01-01,10028_T0_2022-01-01,10029_T0_2022-01-01,10030_T0_2022-01-01,10031_T0_2022-01-01,10032_T0_2022-01-01,10033_T0_2022-01-01,10034_T0_2022-01-01,10035_T0_2022-01-01,10036_T0_2022-01-01,10037_T0_2022-01-01,10038_T0_2022-01-01,10039_T0_2022-01-01,10040_T0_2022-01-01,10041_T0_2022-01-01,10042_T0_2022-01-01,10043_T0_2022-01-01,10044_T0_2022-01-01,10045_T0_2022-01-01,10046_T0_2022-01-01,10047_T0_2022-01-01,10048_T0_2022-01-01,10049_T0_2022-01-01,10050_T0_2022-01-01,10051_T0_2022-01-01,10052_T0_2022-01-01,10053_T0_2022-01-01,10054_T0_2022-01-01,10055_T0_2022-01-01,10056_T0_2022-01-01,10057_T0_2022-01-01,10058_T0_2022-01-01,10059_T0_2022-01-01,10060_T0_2022-01-01,10061_T0_2022-01-01,10062_T0_2022-01-01,10063_T0_2022-01-01,10064_T0_2022-01-01,10065_T0_2022-01-01,11001_T0_2022-01-01,11002_T0_2022-01-01,11003_T0_2022-01-01,12001_T0_2022-01-01,12002_T0_2022-01-01,12003_T0_2022-01-01,12004_T0_2022-01-01"
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Invalid credentials.',
            ];
        }
    } else {
        return [
            'status' => false,
            'message' => 'Authorization failed.',
        ];
    }
}
