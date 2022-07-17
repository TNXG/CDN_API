<?
error_reporting(E_ERROR);
$domain = $_SERVER['HTTP_HOST'];
$uri = urldecode($_SERVER['REQUEST_URI']);
$url = 'https://' . $domain . $uri;
$type = 取两者之间($uri, '/', '/');
$header = getallheaders();
$code = $header['Tnxg-Service-Code'];

print_r ($header);


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
