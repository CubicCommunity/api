<?php
enum RequestMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    case PATCH = 'PATCH';
    case OPTIONS = 'OPTIONS';
}

class Utils
{
    public $headers = [ // default headers for web requests
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FAILONERROR => true,
        CURLOPT_VERBOSE => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => 'gzip, deflate',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 '
            . '(KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36 Edg/117.0.2045.60',
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Referer: https://mods.yoursite.com/',
            'Connection: keep-alive',
        ],
    ];

    public function checkMethod(string $allowed, RequestMethod $incoming): void // check if the request method is correct
    {
        if ($allowed !== $incoming) {
            http_response_code(response_code: 405);

            header(header: "Allow: $allowed");
            header(header: 'Content-Type: application/json');

            echo json_encode(value: [
                'error' => 'Method Not Allowed',
            ]);

            exit;
        } else {
            return;
        }
    }
}