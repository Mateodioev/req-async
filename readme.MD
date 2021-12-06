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
