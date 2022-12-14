<?php
include_once '../config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

Auth::checkToken();
Auth::checkUserType();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = json_decode(file_get_contents("php://input", true));

    if (isset($data->id) || isset($data->name) || isset($data->url)) {
        if (isset($data->id)) {
            $site = DB::query("SELECT * FROM sites WHERE id = :id", array(':id' => $data->id))[0];
        } else if (isset($data->name)) {
            $site = DB::query("SELECT * FROM sites WHERE name = :name", array(':name' => $data->name))[0];
        } else if (isset($data->url)) {
            $site = DB::query("SELECT * FROM sites WHERE url = :url", array(':url' => $data->url))[0];
        }

        $array = array(
            'id' => $site['id'],
            'url' => $site['url'],
            'name' => $site['name'],
            'description' => $site['description'],
            'tags' => $site['tags'],
            'owner' => $site['owner'],
        );

        echo json_encode($array);
    } else {
        echo json_encode(array("error" => "Please specify an id, url or name"));
        http_response_code(500);
    }
}
