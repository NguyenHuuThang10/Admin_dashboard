<?php
if(!defined('_INCODE')) die('Access Denied...');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layout($fieldName, $dir='', $data = []){
    if(!empty($dir)){
        $dir = '/'.$dir;
    }
    if(file_exists(_WEB_PATH_TEMPLACE.$dir.'/layouts/'.$fieldName.'.php')){
        require_once _WEB_PATH_TEMPLACE.$dir.'/layouts/'.$fieldName.'.php';
    }
}

function sendMail($to, $subject, $content)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = 'nguyenhuuthangag123@gmail.com'; //SMTP username
        $mail->Password = 'aqyuxujrpuctzfzf'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('nguyenhuuthangag123@gmail.com', 'Huu Thang');
        $mail->addAddress($to); //Add a recipient
        //$mail->addReplyTo($to);
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name

        //Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $content;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function isGet() {
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        return true;
    }
    return false;
}

function isPost() {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        return true;
    }
    return false;
}

function getBody($method = ''){
    $bodyArr = [];
    if(empty($method)){
        if(isGet()){
            if(!empty($_GET)){
                foreach($_GET as $key=>$value){
                    if(is_array($value)){
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }else{
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }

        }
    
        if(isPost()){
            if(!empty($_POST)){
                foreach($_POST as $key=>$value){
                    if(is_array($value)){
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }else{
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    }else{
        if($method == 'get'){
            if(!empty($_GET)){
                foreach($_GET as $key=>$value){
                    if(is_array($value)){
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }else{
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }else if($method == 'post'){
            if(!empty($_POST)){
                foreach($_POST as $key=>$value){
                    if(is_array($value)){
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    }else{
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    }
    
    return $bodyArr;
}

function isEmail($email){
    if(!empty($email)){
        $checkMail = filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    return $checkMail;
}

function checkNumberInt($number, $ranger = []){
    if(!empty($ranger)){
        $option = ['options' => $ranger];
        $checkInt = filter_var($number, FILTER_VALIDATE_INT, $option);
    }else{
        $checkInt = filter_var($number, FILTER_VALIDATE_INT);
    }
    return $checkInt;
}

function checkNumberFloat($number, $ranger = []){
    if(!empty($ranger)){
        $option = ['options' => $ranger];
        $checkFloat = filter_var($number, FILTER_VALIDATE_FLOAT, $option);
    }else{
        $checkFloat = filter_var($number, FILTER_VALIDATE_FLOAT);
    }
    return $checkFloat;
}

function isPhone($phone){
    $checkZero = false;
    $checkLast = false;
    if($phone[0] == '0'){
        $checkZero = true;
        $phone = substr($phone, 1);
    }

    if(checkNumberInt($phone) && strlen($phone) == 9){
        $checkLast = true;
    }
    if($checkLast && $checkZero){
        return true;
    }
    return false;
}

function getMsg($msg, $msg_type='success'){
    echo '<div class="alert alert-'.$msg_type.'">';
    echo $msg;
    echo '</div>';
}

function getErrors($fieldName, $errors, $beforHtml, $afterHtml){
    if(!empty($errors[$fieldName])){
        return $beforHtml.reset($errors[$fieldName]).$afterHtml;
    }
}

function getOld($fieldName, $old){
    if(!empty($old[$fieldName])){
        return $old[$fieldName];
    }
}

function redirect($path = 'index.php'){
    $url = _WEB_HOST_ROOT.'/'.$path;
    header('Location: '. $url);
    exit;
}

function isLogin(){
    if(!empty(getSession('login_token'))){
        $token = getSession('login_token');
        $query = firstRaw("SELECT user_id FROM login_token WHERE token = '$token'");
        if($query){
            return $query;
        }else{
            removeSession('login_token');
        }
    }
    return false;
}

//Tự động xoá token login đếu đăng xuất
function autoRemoveTokenLogin(){
    $allUsers = getRaw("SELECT * FROM users WHERE status=1");

    if (!empty($allUsers)){
        foreach ($allUsers as $user){
            $now = date('Y-m-d H:i:s');

            $before = $user['last_activity'];

            $diff = strtotime($now)-strtotime($before);
            $diff = floor($diff/60);

            if ($diff>=10){
                deleted('login_token', "user_id=".$user['id']);
            }
        }
    }
}

//Lưu lại thời gian cuối cùng hoạt động
function saveActivity(){
    $userId = isLogin()['user_id'];
    update('users', ['last_activity'=>date('Y-m-d H:i:s')], "id=$userId");
}

function activeMenuSideBar($module = ''){
    if(!empty(getBody()['module'])){
        if(getBody()['module'] == $module){
            return true;
        }
    }
    return false;
}

function getLinkAdmin($module, $action='', $params = []){
    $url = _WEB_HOST_ROOT_ADMIN;
    $url .= '?module='.$module;
    if(!empty($action)){
        $url .= '&action='.$action;
    }
    /**
     * $params = ['id' => 1, 'name' => 'Thang'];
     * $paramsString = id=1&name=Thang;
     */
    if(!empty($params)){
        $paramsString = http_build_query($params);
        $url .= '&'.$paramsString;
    }
    return $url;
}

function getDateFormat($dateStr, $format){
    $objectDate = date_create($dateStr);
    if(!empty($objectDate)){
        return date_format($objectDate, $format);
    }
    return false;
}

//Check font awesome icon
function isFontIcon($input){
    if(strpos($input, '<i class=') !== false){
        return true;
    }
    return false;
}

function getLinkQueryString($queryString, $key, $value){
    $queryArr = explode('&', $queryString);
    $queryArr = array_filter($queryArr);

    $queryFinal = '';

    if (!empty($queryArr)){
        foreach ($queryArr as $item){
            $itemArr = explode('=', $item);
            if (!empty($itemArr)){
               if ($itemArr[0]==$key){
                   $itemArr[1] = $value;
               }

               $item = implode('=', $itemArr);

               $queryFinal.=$item.'&';

            }

        }
    }

    if (!empty($queryFinal)){
        $queryFinal = rtrim($queryFinal, '&');
        $queryFinal .= '&module=services';
    }else{
        $queryFinal = $queryString;
    }

    return $queryFinal;

}

