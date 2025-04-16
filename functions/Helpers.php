<?php


/**
 * Dump and Die
 * @param array $args
 * @return never
 */
function dd(...$args)
{
    extract(debug_backtrace()[0]);
    echo "$file:$line";
    foreach ($args as $value) {
        echo "<pre>";
        var_dump($value);
        echo "</pre>";
    }
    http_response_code(505);
    die("$file:$line");
}

/**
 * trnslate a key and return. if don't have translate return key.
 * @param string $key
 * @return string
 */
function lang($key)
{
    global $sap_common;
    return $sap_common->lang($key) ?: $key;
}

/**
 * like lang just echo and don't return andthing.
 * @param string $key
 * @return void
 */
function eLang($key)
{
    echo lang($key);
}

/**
 * get Laravel Validation Factory to valrdating data.
 * @param array $messages list of translations 
 * @return Illuminate\Validation\Factory
 */
function getValidator(array $messages = [])
{
    require SAP_APP_PATH . '/vendor/autoload.php';
    $loader = new \Illuminate\Translation\ArrayLoader();
    if (!empty($messages)) {
        $loader->addMessages('en', 'validation', $messages);
    }

    return new \Illuminate\Validation\Factory(new \Illuminate\Translation\Translator($loader, 'en'));
}

function REST($data, $statusCode = 200, $headers = [])
{
    header("Content-Type: application/json; charset=UTF-8");
    header("X-Powered-By: Hoosh Saman Group");

    foreach ($headers as $header) {
        header($header);
    }

    http_response_code($statusCode);
    $messages = [
        200 => 'success',
        400 => 'user error',
        401 => 'unathorized',
        403 => 'forbidden',
        404 => 'not found',
        500 => 'error happen',
    ];

    $response = [
        "status" => $statusCode >= 400 ? "error" : "success",
        "message" => $messages[$statusCode],
        "data" => $data
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

function toTimestamp(string $datetime, string $format = 'Y-m-d H:i:s'): ?int
{
    $date = DateTime::createFromFormat($format, $datetime);
    return $date ? $date->getTimestamp() : null;
}

function getPHPSpreadsSheet($filePath, $fileType = 'Xlsx')
{
    require_once SAP_APP_PATH . '/vendor/autoload.php';

    try {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        return $reader->load($filePath); // برمی‌گردونه Spreadsheet object
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        die('Error loading spreadsheet: ' . $e->getMessage());
    }
}

function detectMediaTypeByFilename(string $filename): string
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
    $videoExts = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'flv', 'wmv'];

    if (in_array($ext, $imageExts)) {
        return 'image';
    } elseif (in_array($ext, $videoExts)) {
        return 'video';
    } else {
        return 'unknown';
    }
}

function mediaIsImage(string $filename): bool
{
    return detectMediaTypeByFilename($filename) === 'image';
}

function mediaIsVideo(string $filename): bool
{
    return detectMediaTypeByFilename($filename) === 'video';
}

function isEmptyJson($json): bool
{
    try {
        $json = json_decode($json, true);
    } catch (Exception $exception) {
        // TODO: handle exception
    }

    return emptY($json);
}