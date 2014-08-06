<?php
/**
 * Rev.com is a transcription API service
 *
 * Basic Usage:
 *
 * 1. Configure Rev with your access credentials
 * <code>
 * <?php
 * $rev = new Rev('APP_ID', 'API_KEY');
 * ?>
 * </code>
 *
 * 2. Make requests
 * <code>
 * <?php
 * $orders = $rev->getOrders();
 * var_dump($orders);
 * ?>
 * </code>
 *
 * @author   Mitch Flindell <mitch@verbate.co>
 * @link     https://github.com/scrummitch/Rev-PHP
 * @license  MIT License (MIT)
 */
class Rev {

  /**
   * @var  string
   */
  protected $_app_id;

  /**
   * @var  string
   */
  protected $_api_key;

  /**
   * @var  string
   */
  protected $_base_url;

  public function __construct($app_id, $api_key, $sandbox = FALSE)
  {
    $this->_app_id = $app_id;
    $this->_api_key=  $api_key;

    $this->_base_url = ($sandbox)
      ? 'https://api-sandbox.rev.com/api/v1/'
      : 'https://api.rev.com/api/v1/';
  }

  /**
   *
   *
   * @param
   * @param
   * @param   array
   * @return  object
   */
  protected function _http($url, $method = 'GET', $post_data = NULL)
  {

    $headers = array('Content-Type: application/json');

    $ch = curl_init($this->_base_url.$url);

    if ($method == 'POST')
    {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
      curl_setopt($ch, CURLOPT_POST, true);
    }
    elseif ($method == 'PUT')
    {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
      $headers[] = 'Content-Length: ' . strlen($post_data);

    }
    else if ($method != 'GET')
    {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_BUFFERSIZE, 4096);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $this->_app_id . ':' . $this->_api_key);

    $response = curl_exec($ch);

    $this->error = array(
      'code' => 1,
      'message' => '',
      'http_code' => 1,
    );

    return json_decode($response);
  }

  /**
   * Creates a new input?
   *
   * @param   string  $content_type
   * @param   string  $file_name
   * @param   string  $url
   * @return  array
   */
  public function post_inputs($content_type, $file_name = NULL, $url = NULL)
  {
    $params = array(
      'content_type' => $content_type,
      'file_name' => $file_name,
      'url' => $url
    );

    $response = $this->_http('inputs', 'POST', $params);

    // TODO - check for errors
    return $response;
  }

  /**
   * Creates a new order
   * for [transcription, caption, translation]
   */
  public function post_orders()
  {
    $this->_http('orders');
  }

  /**
   *
   *
   * @param   int  $page
   * @return  array
   */
  public function get_orders($page = 0)
  {
    $response = $this->_http('orders?page='.$page);
  }

  /**
   *
   *
   * @param   string  $order_id
   * @return  array
   */
  public function get_order($order_id)
  {

    $response = $this->_http('orders/'.$order_id);
  }

  /**
   *
   *
   * @param   string  $order_id
   * @return  array
   */
  public function cancel_order($order_id)
  {
    $response = $this->_http('orders/'.$order_id.'/cancel');
  }

  /**
   *
   *
   * @param  string  $attachment_id
   */
  public function get_attachment($attachment_id)
  {
    $response = $this->_http('attachments/'.$attachment_id);
  }

  /**
   *
   *
   * @param  string  $attachment_id
   */
  public function get_attachment_content($attachment_id)
  {
    $response = $this->_http('attachments/'.$attachment_id.'/content');
  }
}
