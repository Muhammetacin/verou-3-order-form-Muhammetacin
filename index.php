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

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function validate()
{
  // This function will send a list of invalid fields back
  $streetErr = $emailErr = $streetNrErr = $zipCodeErr = $productsErr = "";
  $street = $email = $streetNr = $zipCode = "";
  $errorList = [];

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
      $emailErr = "Email is required";
      $errorList[] = $emailErr;
    } else {
      $email = test_input($_POST["email"]);
      // check if e-mail address is well-formed
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $errorList[] = $emailErr;
      }
    }

    if (empty($_POST["street"])) {
      $streetErr = "Street is required";
      $errorList[] = $streetErr;
    } else {
      $street = test_input($_POST["street"]);
      // check if street only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z-' ]*$/",$street)) {
        $streetErr = "Only letters and white space allowed in street";
        $errorList[] = $streetErr;
      }
    }

    if (empty($_POST["streetnumber"])) {
      $streetNrErr = "Street number is required";
      $errorList[] = $streetNrErr;
    } else {
      $streetNr = test_input($_POST["streetnumber"]);
      // check if street only contains letters and whitespace
      if (!preg_match("/^[0-9]*$/",$streetNr)) {
        $streetNrErr = "Only numbers allowed in street number";
        $errorList[] = $streetNrErr;
      }
    }

    if (empty($_POST["zipcode"])) {
      $zipCodeErr = "Zipcode is required";
      $errorList[] = $zipCodeErr;
    } else {
      $zipCode = test_input($_POST["zipcode"]);
      // check if street only contains letters and whitespace
      if (!preg_match("/^[0-9]*$/",$zipCode)) {
        $zipCodeErr = "Only numbers allowed in zipcode";
        $errorList[] = $zipCodeErr;
      }
    }

    if (!isset($_POST["products"])) {
//      $zipCodeErr = "Zipcode is required";
//      $errorList[] = $zipCodeErr;
//    } else {
//      $zipCode = test_input($_POST["zipcode"]);
//      // check if street only contains letters and whitespace
//      if (!preg_match("/^[0-9]*$/",$zipCode)) {
//        $zipCodeErr = "Only numbers allowed in zipcode";
//        $errorList[] = $zipCodeErr;
//      }
      $productsErr = "There are no products selected";
      $errorList[] = $productsErr;
    }
  }

  return $errorList;
}

function handleForm()
{
  // form related tasks (step 1)
  // echo "<h3 class=\"alert alert-success text-center\">You ordered " . implode(",", $_POST["products"]) . " to your delivery address " . $_POST["street"] . "</h3>";

  global $products, $totalValue;
  $orders = [];

  for($i = 0; $i < count($products); $i++) {
    if(isset($_POST["products"][$i])) {
      $orders[] = $products[$i]["name"];
      $totalValue += $products[$i]["price"];
    }
  }


  // Validation (step 2)
  $invalidFields = validate();
//  echo empty($invalidFields); // empty = 1, something in there = 0

  if (!empty($invalidFields)) {
    // TODO: handle errors
    print_r("<h3 class=\"d-flex justify-content-center alert alert-danger w-75 mx-auto\">Invalid form.<br>"
      . implode(nl2br(",\n"), $invalidFields) . "</h3>");
  }
  else {
    // TODO: handle successful submission
    print_r("<h3 class=\"d-flex justify-content-center alert alert-success w-75 mx-auto\">You ordered "
      . implode(", ", $orders) . " to your delivery address "
      . $_POST["street"] . "</h3>");
  }
}

// TODO: replace this if by an actual check
//$formSubmitted = "";
//isset($_POST) &&
//  isset($_POST["products"]) &&
//  isset($_POST["email"]) &&
//  isset($_POST["street"]) &&
//  isset($_POST["streetnumber"]) &&
//  isset($_POST["zipcode"]) ? $formSubmitted = true : $formSubmitted = false;

$formSubmitted = isset($_POST["submit"]);

if ($formSubmitted) {
  handleForm();
}

require 'form-view.php';