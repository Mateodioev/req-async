Async requests
=======

Create new request
--------

```php
$req = new Req();
$promise = array();
$promise[] = $req::Get('https://httpbin.org/get'); // GET method
$promise[] = $req::Post('https://httpbin.org/post'); // POST method
$promise[] = $req::Put('https://httpbin.org/put'); // CUSTOM method

```

Run all request
--------

```php
$response = Run::Async($promise);
```

Proxy sintax
--------

```php
# PROXY (http/s, socks4, socks5)
$server = [
    "METHOD" => "TUNNEL",
    "SERVER" => "ip:port"
];

# Windscribe
$server = [
    "METHOD" => "CUSTOM",
    "SERVER" = "socks5h://socks-us.windscribe.com:1080",
    "AUTH" => "w07lu4mt-r6vxjpb:ucxqefrm5h"
];

# Webshare
$server = [
    "METHOD" => "CUSTOM",
    "SERVER" = "p.webshare.io:80",
    "AUTH" => "user-rotate:pass"
];


# APIFY valid syntax example
$server = [
    "METHOD" => "CUSTOM",
    "SERVER" = "http://proxy.apify.com:8000",
    "AUTH" => "auto:pasword"
];

# IPVANISH valid syntax example
$server = [
    "METHOD" => "CUSTOM",
    "SERVER" => "akl-c12.ipvanish.com:1080",
    "AUTH"   => "my_zone_customer_id:my_zone_customer_password"
];
```

Get sintax
--------

```php
$headers = ['Origin: https://google.com/', 'MSG: testing'];
$server = ["METHOD" => "TUNNEL", "SERVER" => "ip:port"];

$req = new Req();
$promise = array();

$promise[] = $req::Get('https://httpbin.org/get');
$promise[] = $req::Get('https://httpbin.org/get', $headers); // Using headers
$promise[] = $req::Get('https://httpbin.org/cookies/set?name=John&age=25', $headers, null, 'file_example_cookie_file'); // Using headers and cookies
$promise[] = $req::Get('https://httpbin.org/get', null, $server); // Using only proxy

$response = Run::Async($promise); // Run all resquests
```

Post sintax
--------

```php
$headers = ['Origin: https://google.com/', 'MSG: testing'];
$server = ["METHOD" => "TUNNEL", "SERVER" => "ip:port"];
$post = ['name' => 'Jhon', 'age' => 25];

$req = new Req();
$promise = array();

$promise[] = $req::Post('https://httpbin.org/post'); // Simple resquest
$promise[] = $req::Post('https://httpbin.org/post', http_build_query($post)); // Post data
$promise[] = $req::Post('https://httpbin.org/post', $post); // Post (in json)
$promise[] = $req::Post('https://httpbin.org/post', $post, $headers); // Post (in json) and headers
$promise[] = $req::Post('https://httpbin.org/cookies/set?name=John&age=25', null, $headers, null, 'cookie_example'); // Using headers and cookies
```

Custom methods
--------

```php
$req = new Req();
$promise = array();

/**
 * Format:
 * $promise[] = $req::MethodName('url', $post_data, $headers, $server, $cookie_name);
 * $response = Run::Async($promise);
*/
$promise[] = $req::Put('https://httpbin.org/put');
$promise[] = $req::Patch('https://httpbin.org/patch');
$promise[] = $req::Delete('https://httpbin.org/delete');
```

Run requests
--------

```php
$req = new Req();
$promise = array();

for ($i=0; $i < 10; $i++) {
    $promise[] = $req::Get('https://httpbin.org/get');
}

$response = Run::Async($promise); // Run all resquests
$response = Run::Async([$promise[0], $promise[1]]); // Run someone resquests
$response = Run::Async($promise, 100); // Modified ms
```

Installation
------------

### Install source from GitHub
To install the source code:

    $ git clone https://github.com/Mateodioev/req-async.git


### Install from Composer

    $ composer create-project mateodioev/async-curl-requests

And include it in your scripts:

```php
require './vendor/autoload.php';
use Async\Req;
use Async\Run;
```
