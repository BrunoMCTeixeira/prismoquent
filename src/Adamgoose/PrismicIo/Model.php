<?php namespace Adamgoose\PrismicIo;

use Illuminate\Support\Facades\Config;

abstract class Model {

  protected $endpoint;
  protected $token;
  protected $ref;

  public $collection;
  public $mask;
  public $tags;

  public $conditions;

  /**
   * Grab variables from config
   *
   * @return void
   */
  public function __construct()
  {
    if(Config::has('prismic.endpoint') && !isset($this->endpoint))
      $this->endpoint = Config::get('prismic.endpoint');

    if(Config::has('prismic.token') && !isset($this->token))
      $this->token = Config::get('prismic.token');
  }

  /**
   * Get a new Query object
   *
   * @return \Adamgoose\PrismicIo\Query
   */
  public function newQuery()
  {
    return new Query($this);
  }

  /**
   * Handle dynamic method calls into the method.
   *
   * @param  string  $method
   * @param  array   $parameters
   * @return mixed
   */
  public function __call($method, $parameters)
  {
    $query = $this->newQuery();

    return call_user_func_array(array($query, $method), $parameters);
  }

  /**
   * Handle dynamic static calls into the method.
   *
   * @param  string  $method
   * @param  array   $parameters
   * @return mixed
   */
  public static function __callStatic($method, $parameters)
  {
    $instance = new static;

    return call_user_func_array(array($instance, $method), $parameters);
  }

  /**
   * Dynamically retrieve attributes on the model.
   *
   * @param  string $key
   * @return mixed
   */
  public function __get($key)
  {
    return $this->{$key};
  }

}