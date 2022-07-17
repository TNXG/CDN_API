<?
error_reporting(E_ERROR);
$domain = $_SERVER['HTTP_HOST'];
$uri = urldecode($_SERVER['REQUEST_URI']);
$url = 'https://' . $domain . $uri;
$type = 取两者之间($uri, '/', '/');
$header = getallheaders();
// $code = $header['Tnxg-Service-Code'];
// if ($code != '1145141919810') {
//     $array = array(
//         'code' => '403',
//         'message' => '你无权从我们的回源服务器中获取信息',
//         'time' => time(),
//     );
//     header('Content-type:text/json');
//     http_response_code(200);
//     echo json_encode($array, JSON_UNESCAPED_UNICODE);
// } else {
    //判断cdn回源为npm还是gh
    if ($type == 'npm') {
        //获取包名，如果格式为 xx@aa 则返回 xx 如果没有版本号则返回空
        $pack = 取两者之间($url, 'npm/', '@');
        //先给$npm包最新版本赋值版本号
        $npm包最新版本 = 取两者之间($url, 'npm/' . $pack . '@', '/');
        $url = str_replace('/' . 'npm' . '/' . $pack . '@' . $npm包最新版本 . '/', '', $uri);
        //判断版本是否为为latest或空，如果为真则从registry获取最新版本号
        if ($npm包最新版本 == 'latest') {
            $npm包最新版本 = 获取npm包最新版本($pack);
            $url = str_replace('/' . 'npm' . '/' . $pack . '@latest' . '/', '', $uri);
        }
        if (empty($npm包最新版本)) {
            $pack = 取两者之间($url, 'npm/', '/');
            $npm包最新版本 = 获取npm包最新版本($pack);
            $url = str_replace('/' . 'npm' . '/' . $pack . '/', '', $uri);
        }
        $文件地址 = "https://unpkg.com/$pack@$npm包最新版本/$url";
        $文件状态 = (string)获取文件状态($文件地址);
        $文件header = 获取文件格式($文件地址);
        if ($文件状态 == 200 || $文件状态 == 302) {
            if (empty($文件header)) {
                $文件header = 'text/plain';
            }
            header('Content-type:' . $文件header);
            echo 获取文件($文件地址);
        } else {
            $array = array(
                'code' => '404',
                'message' => '回源服务器无法从资源服务器中找到数据',
                'time' => time(),
                '文件状态' => $文件状态
            );
            header('Content-type:text/json');
            http_response_code(404);
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    }


    if ($type == 'gh') {
        $user = 取两者之间($uri, '/gh/', '/');
        $repoaver = 取两者之间($uri, '/gh/' . $user . '/', '/');
        $ver = 取出右边文本($repoaver, '@');
        $repo = 取出左边文本($repoaver, '@');
        if (empty($ver)) {
            $ver = 'master';
        }
        $url = 取出右边文本($uri, $repoaver . '/');
        $文件地址 = "https://gcore.jsdelivr.net/gh/$user/$repoaver/$url";
        $文件状态 = (string)获取文件状态($文件地址);
        $文件header = 获取文件格式($文件地址);
        if ($文件状态 == 200 || $文件状态 == 302) {
            if (empty($文件header)) {
                $文件header = 'text/plain';
            }
            header('Content-type:' . $文件header);
            echo 获取文件($文件地址);
        } else {
            $array = array(
                'code' => '404',
                'message' => '回源服务器无法从资源服务器中找到数据',
                'time' => time(),
                '文件状态' => $文件状态
            );
            header('Content-type:text/json');
            http_response_code(404);
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    }

    if ($type != 'npm' || $type != 'gh') {
        $array = array(
            'code' => '404',
            'message' => '回源服务器无法从资源服务器中找到数据',
            'time' => time(),
            '文件状态' => $文件状态
        );
        header('Content-type:text/json');
        http_response_code(404);
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
    }
// }


function 取两者之间($str, $start_str, $end_str = '/')
{
    $arr = explode($start_str, $str);
    if (empty($arr) || !isset($arr[1])) {
        return false;
    }
    $str = $arr[1];
    if (strpos($str, $end_str) !== false) {
        return substr($str, 0, strpos($str, $end_str));
    } else {
        return $str;
    }
}

function 取出左边文本($str, $rightStr)
{
    $right = strpos($str, $rightStr);
    return substr($str, 0, $right);
}

function 取出右边文本($str, $leftStr)
{
    $left = strpos($str, $leftStr);
    return substr($str, $left + strlen($leftStr));
}

function 获取npm包最新版本($npm包名)
{
    $npm包信息 = file_get_contents('https://registry.npmjs.org/' . $npm包名);
    $npm包信息 = json_decode($npm包信息, true);
    $npm包版本 = $npm包信息['dist-tags']['latest'];
    return $npm包版本;
}

function 获取文件($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'user-agent: Mozilla/5.0 (LoYuNetwork; MiHyper) Raiden_Aurora/16'
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function 获取文件状态($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'user-agent: Mozilla/5.0 (LoYuNetwork; MiHyper) Raiden_Aurora/16'
    ));
    curl_exec($ch);
    return curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
}

function 获取文件格式($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'user-agent: Mozilla/5.0 (LoYuNetwork; MiHyper) Raiden_Aurora/16'
    ));
    curl_exec($ch);
    return curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
}
