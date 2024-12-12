<?php

//not needed please remove it
/**
 * Created by PhpStorm.
 * User: muhammad abid
 * Date: 1/17/19
 * Time: 11:19 AM
 */
namespace App\Helpers;

use TwitterAPIExchange;

/**
 * Description: Following configuration needs to be set in the .env file of the respective project
 * TWITTER_ACCESS_TOKEN =
 * TWITTER_ACCESS_TOKEN_SECRET =
 * TWITTER_CONSUMER_KEY =
 * TWITTER_CONSUMER_SECRET =
 */
class TwitterSDK
{

    /**
     * @var string: https://api.twitter.com/1.1/
     */
    protected $uri = 'https://api.twitter.com/1.1/';

    /**
     * @var TwitterAPIExchange: twitter sdk
     */
    protected $twitter;

    /**
     * @var :twitter response
     */
    protected $response;

    /**
     * TwitterSDK constructor.
     * @param $url
     * @param $query
     */
    public function __construct($url, $method = 'POST', $query = [])
    {
        $this->twitter = new TwitterAPIExchange($this->setSetting());

        $query = $method == 'POST' ? $query : '?'. $this->setQuery($query);

        $this->setResponse($this->twitter->buildOauth($this->uri . $url, $method)->setPostfields($query)->performRequest());
    }


    /**
     * Description: The following method is used to set access and consumer keys
     * @author Muhammad Abid - I2L
     * @return mixed
     */
    public function setSetting()
    {
        return array(
            'oauth_access_token' => env('TWITTER_ACCESS_TOKEN'),
            'oauth_access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
            'consumer_key' => env('TWITTER_CONSUMER_KEY'),
            'consumer_secret' => env('TWITTER_CONSUMER_SECRET')
        );
    }

    /**
     * Description: The following method is used to set response
     * @author Muhammad Abid - I2L
     * @return mixed
     */
    public function setResponse($response)
    {
        $response = json_decode($response);
        return $this->response = isset($response->errors) ? $response->errors[0] : (count($response) ? $response[0] : $response);
    }

    /**
     * Description: The following method is used to get response
     * @author Muhammad Abid - I2L
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Description: The following method is used to set $query array to string for GET method
     * @author Muhammad Abid - I2L
     * @param $query
     * @return mixed
     */
    public function setQuery($query, $string = '', $index = false)
    {
        foreach ($query as $par => $val) {
            if ($index) {
                $par = $index;
            }

            if (is_array($val)) {
                $string = bqs($val, $string, $par);
            } else {
                $string .= $par.'='.$val.'&';
            }
        }
        return $string;
    }
}
