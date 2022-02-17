<?php

// This file is your starting point (= since it's the index)
// It will contain most of the logic, to prevent making a messy mix in the html

// This line makes PHP behave in a more strict way
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// We are going to use session variables so we need to enable sessions
session_start();

// Use this function when you need to need an overview of these variables
//function whatIsHappening() {
//    echo '<h2>$_GET</h2>';
//    var_dump($_GET);
//    echo '<h2>$_POST</h2>';
//    var_dump($_POST);
//    echo '<h2>$_COOKIE</h2>';
//    var_dump($_COOKIE);
//    echo '<h2>$_SESSION</h2>';
//    var_dump($_SESSION);
//}

function pre_r($array)
{
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}

function whatIsHappening()
{
  echo '<h2>$_GET</h2>';
  pre_r($_GET);
  echo '<h2>$_POST</h2>';
  pre_r($_POST);
//  echo '<h2>$_COOKIE</h2>';
//  pre_r($_COOKIE);
//  echo '<h2>$_SESSION</h2>';
//  pre_r($_SESSION);
}

whatIsHappening();

$products = [
  ['name' => 'Turkish coffee', 'price' => 4],
  ['name' => 'Caykur Turkish tea', 'price' => 1.5],
  ['name' => 'Cola', 'price' => 2],
  ['name' => 'Fanta', 'price' => 2],
  ['name' => 'Ice Tea', 'price' => 2],
  ['name' => 'Nalu', 'price' => 2.5],
  ['name' => 'Cappucino', 'price' => 3.5],
  ['name' => 'Water', 'price' => 1],
];

$totalValue = 0;

//echo "<pre>You ordered " . implode(",", $_POST["products"]) . " and your delivery address is " . $_POST["street"] . "</pre>";

function validate()
{
  // This function will send a list of invalid fields back
  return [];
}

function handleForm()
{
  // form related tasks (step 1)
  // echo "<h3 class=\"alert alert-success text-center\">You ordered " . implode(",", $_POST["products"]) . " to your delivery address " . $_POST["street"] . "</h3>";

  global $products;
  $orders = [];
  for($i = 0; $i < count($products); $i++) {
    if(isset($_POST["products"][$i])) {
      $orders[] = $products[$i]["name"];
    }
  }


  // Validation (step 2)
  $invalidFields = validate();
  if (!empty($invalidFields)) {
    // TODO: handle errors
    echo "<h3 class=\"alert alert-danger text-center\">Fill in the required fields (all except city).</h3>";
  } else {
    // TODO: handle successful submission
    echo "<h3 class=\"alert alert-success text-center\">You ordered " . implode(", ", $orders) . " to your delivery address " . $_POST["street"] . "</h3>";
  }
}

// TODO: replace this if by an actual check
$formSubmitted = "";
isset($_POST) &&
  isset($_POST["products"]) &&
  isset($_POST["email"]) &&
  isset($_POST["street"]) &&
  isset($_POST["streetnumber"]) &&
  isset($_POST["zipcode"]) ? $formSubmitted = true : $formSubmitted = false;

if ($formSubmitted) {
  handleForm();
}

require 'form-view.php';