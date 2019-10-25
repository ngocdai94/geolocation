<?php

class Geolocation extends DatabaseObject {

  static protected $table_name = 'geolocation_data';
  static protected $db_columns = ['id', 'lat_degree', 'lat_minute', 'lat_seconds', 'lat_direction', 'long_degree', 'long_minute', 'long_seconds', 'long_direction'];//, 'lat', 'long'];

  public $id;
  public $lat_degree;
  public $lat_minute;
  public $lat_seconds;
  public $lat_direction;
  public $long_degree;
  public $long_minute;
  public $long_seconds;
  public $long_direction;
  public $lat;
  public $long;

  public const CATEGORIES = ['Road', 'Mountain', 'Hybrid', 'Cruiser', 'City', 'BMX'];

  public const GENDERS = ['Mens', 'Womens', 'Unisex'];

  public const CONDITION_OPTIONS = [
    1 => 'Beat up',
    2 => 'Decent',
    3 => 'Good',
    4 => 'Great',
    5 => 'Like New'
  ];

  public function __construct($args=[]) {
    //$this->brand = isset($args['brand']) ? $args['brand'] : '';
    $this->lat_degree = $args['lat_degree'] ?? '';
    $this->lat_minute = $args['lat_minute'] ?? '';
    $this->lat_seconds = $args['lat_seconds'] ?? '';
    $this->lat_direction = $args['lat_direction'] ?? '';
    $this->long_degree = $args['long_degree'] ?? '';
    $this->long_minute = $args['long_minute'] ?? '';
    $this->long_seconds = $args['long_seconds'] ?? '';
    $this->long_direction = $args['long_direction'] ?? 0;
    // $this->weight_kg = $args['weight_kg'] ?? 0.0;
    // $this->condition_id = $args['condition_id'] ?? 3;

    // Caution: allows private/protected properties to be set
    // foreach($args as $k => $v) {
    //   if(property_exists($this, $k)) {
    //     $this->$k = $v;
    //   }
    // }
  }

  public function convertLat_Long(){

  }
  public function name() {
    return "{$this->brand} {$this->model} {$this->year}";
  }
  public function weight_kg() {
    return number_format($this->weight_kg, 2) . ' kg';
  }

  public function set_weight_kg($value) {
    $this->weight_kg = floatval($value);
  }

  public function weight_lbs() {
    $weight_lbs = floatval($this->weight_kg) * 2.2046226218;
    return number_format($weight_lbs, 2) . ' lbs';
  }

  public function set_weight_lbs($value) {
    $this->weight_kg = floatval($value) / 2.2046226218;
  }

  public function condition() {
    if($this->condition_id > 0) {
      return self::CONDITION_OPTIONS[$this->condition_id];
    } else {
      return "Unknown";
    }
  }

  protected function validate() {
    $this->errors = [];

    // Lattitude Validation
    if(is_blank($this->lat_degree)) {
      $this->errors[] = "Lattitude degree cannot be blank";
    }
    if(is_blank($this->lat_minute)) {
      $this->errors[] = "Lattitude minute cannot be blank";
    }
    if(is_blank($this->lat_seconds)) {
      $this->errors[] = "Lattitude seconds cannot be blank";
    }
    if(is_blank($this->lat_seconds)) {
      $this->errors[] = "Lattitude direction cannot be blank";
    }
    if(strlen($this->lat_direction) > 1) {
      $this->errors[] = "Lattitude direction can only be 1 character";
    }
    if
    (
      strcmp($this->lat_direction, "N") == 0 ||
      strcmp($this->lat_direction, "n") == 0  ||
      strcmp($this->lat_direction, "S") == 0  ||
      strcmp($this->lat_direction, "S") == 0 ) {
      // Good!
    } else {
      $this->errors[] = "Lattitude direction can be either N or S " . var_dump($this->lat_direction);
    }

    // Longtitude Validation
    if(is_blank($this->long_degree)) {
      $this->errors[] = "Longitude degree cannot be blank.";
    }
    if(is_blank($this->long_minute)) {
      $this->errors[] = "Longitude monute cannot be blank.";
    }
    if(is_blank($this->long_seconds)) {
      $this->errors[] = "Longitude seconds cannot be blank.";
    }
    if(is_blank($this->long_direction)) {
      $this->errors[] = "Longitude direction cannot be blank.";
    }
    if(strlen($this->long_direction) > 1) {
      $this->errors[] = "Longitude direction can only be 1 character";
    }
    if
    (
      strcmp($this->long_direction, "W") == 0 ||
      strcmp($this->long_direction, "w") == 0  ||
      strcmp($this->long_direction, "E") == 0  ||
      strcmp($this->long_direction, "e") == 0 ) {
      // Good!
    } else {
      $this->errors[] = "Lattitude direction can be either W or E " . var_dump($this->long_direction);
    }

    return $this->errors;
  }
}

?>
