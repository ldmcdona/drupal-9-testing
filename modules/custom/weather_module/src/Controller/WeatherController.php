<?php
namespace Drupal\weather_module\Controller;
class WeatherController {
    public function weather() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        $ch = curl_init();

        if($ipaddress != '127.0.0.1'){
            $json     = file_get_contents("http://ipinfo.io/$ipaddress/geo");
            $json     = json_decode($json, true);
            $country  = $json['country'];
            $city     = $json['city'];
            curl_setopt($ch, CURLOPT_URL, "https://api.openweathermap.org/data/2.5/weather?q=$city,$country&appid=a1d8f03b56c8e2aec877562bfa172247");
        } else {
            curl_setopt($ch, CURLOPT_URL, "https://api.openweathermap.org/data/2.5/weather?q=Edmonton,CA&appid=a1d8f03b56c8e2aec877562bfa172247");
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return array(
            '#title' => 'Weather Module',
            '#markup' => "Weather At Place (default to Edmonton if on localhost): $output"
        );
    }
}
?>