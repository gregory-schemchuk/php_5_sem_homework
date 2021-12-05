<?php

// HOMEWORK 2

require_once "vendor/autoload.php";
spl_autoload_register(function ($className) {require_once './app/classes/'.$className.'.inc';});

$deepCount = 2;
$url = "http://todolist.ru/";
$client = new GuzzleClient($url);
try {
    $content = $client->get($url);
}
catch (UnsuccessfulRequestException $ure) {
    throw new Exception($ure->getMessage()." Code: ".$ure->getCode()." Http message: ".$ure->getHttpReason());
}
catch (NotInitializedException $nie) {
    throw new Exception("Client not initialized for some mysterious reason");
}

$parser = new UriParser($content);
$result = $parser->getResult();
$host = "localhost";
$port = 5432;
$dbname = "php_home_2";
$user = "postgres";
$password = "1234";
$uridb = new UriDB($host, $port, $dbname, $user, $password);
$parent = $uridb->Add(new Uri($url));

try {
    getAllUrisFromPage($client, $url, $parent, $deepCount);
} catch (Exception $e) {
    echo $e;
}

$dbresult = $uridb->GetAllUris();
echo Renderer::render('uris', ['uris'=> $dbresult]);


function getAllUrisFromPage($client, $uri, $parentId, $deepCount) {
    try {
        $content = $client->get($uri);
        $parser = new UriParser($content);
        $result = $parser->getResult();
        $host = "localhost";
        $port = 5432;
        $dbname = "php_home_2";
        $user = "postgres";
        $password = "1234";
        $uridb = new UriDB($host, $port, $dbname, $user, $password);
        $parent = $uridb->Add(new Uri($uri, null, $parentId));
        $deepCount--;
        if ($deepCount > 0) {
            foreach ($result as $url) {
                getAllUrisFromPage($client, $url, $parent, $deepCount);
            }
        }
    }
    catch (UnsuccessfulRequestException $ure) {
        throw new Exception($ure->getMessage()." Code: ".$ure->getCode()." Http message: ".$ure->getHttpReason());
    }
    catch (NotInitializedException $nie) {
        throw new Exception("Client not initialized for some mysterious reason");
    }
}

/*

$conn = sprintf(
    "pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s",
    $host,
    $port,
    $dbname,
    $user,
    $password
);
$pdo = new PDO($conn);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement = $pdo->prepare("INSERT INTO uris(uri, parent_id) VALUES(:uri, :parent_id)");

foreach ( $parser->getResult() as $uri) {
    $statement->execute(["uri" =>$uri, "parent_id" => null]);
}


$query = $pdo->query("SELECT uri FROM uris");
*/
