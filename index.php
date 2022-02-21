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
function whatIsHappening()
{
  echo '<h2>$_GET</h2>';
  pre_r($_GET);
  echo '<h2>$_POST</h2>';
  pre_r($_POST);
  echo '<h2>$_COOKIE</h2>';
  pre_r($_COOKIE);
  echo '<h2>$_SESSION</h2>';
  pre_r($_SESSION);
}

function pre_r($array)
{
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}

//whatIsHappening();

$drinks = [
  ['name' => 'Turkish coffee', 'price' => 4],
  ['name' => 'Caykur Turkish tea', 'price' => 1.5],
  ['name' => 'Cola', 'price' => 2],
  ['name' => 'Fanta', 'price' => 2],
  ['name' => 'Ice Tea', 'price' => 2],
  ['name' => 'Nalu', 'price' => 2.5],
  ['name' => 'Cappucino', 'price' => 3.5],
  ['name' => 'Water', 'price' => 1],
];

$foods = [
  ['name' => 'Baklava', 'price' => 4],
  ['name' => 'Tiramisu', 'price' => 2.5],
  ['name' => 'Ice cream', 'price' => 2],
  ['name' => 'Lokum', 'price' => 1],
  ['name' => 'Waffle', 'price' => 2],
  ['name' => 'Pancake', 'price' => 3.5],
  ['name' => 'Frietjes!', 'price' => 3],
  ['name' => 'Chocolate bits', 'price' => 0.5],
];

$products = $drinks;

$totalValue = 0;
$totalValueAllOrders = 0;

if(isset($_COOKIE["totalValue"])) {
  $totalValueAllOrders = $_COOKIE["totalValue"];
}

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

    if (empty($_POST["streetNumber"])) {
      $streetNrErr = "Street number is required";
      $errorList[] = $streetNrErr;
    } else {
      $streetNr = test_input($_POST["streetNumber"]);
      // check if street number only contains numbers
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
      // check if zipcode only contains numbers
      if (!preg_match("/^[0-9]*$/",$zipCode)) {
        $zipCodeErr = "Only numbers allowed in zipcode";
        $errorList[] = $zipCodeErr;
      }
    }

    if (!isset($_POST["products"])) {
      $productsErr = "There are no products selected";
      $errorList[] = $productsErr;
    }
  }

  return $errorList;
}

function handleForm($products, $totalValue)
{
//  global $products, $totalValue;
  $orders = [];

  for($i = 0; $i < count($products); $i++) {
    if(isset($_POST["products"][$i])) {
      $orders[] = $products[$i]["name"];
      $totalValue += $products[$i]["price"] * $_POST["productAmount"][$i];
    }
  }

  $_POST["totalValue"] = $totalValue;

  // Validation (step 2)
  $invalidFields = validate();

  if (!empty($invalidFields)) {
    // handle errors
    print_r("<h4 class=\"container d-flex justify-content-center alert alert-danger w-75 mx-auto mt-3\">- Invalid form -<br>"
      . implode(nl2br(",\n"), $invalidFields) . "</h4>");
  }
  else {
    // handle successful submission
    print_r("<h4 class=\"container d-flex justify-content-center alert alert-success w-75 mx-auto mt-3\">You ordered "
      . implode(", ", $orders) . " with the total amount of €" . $totalValue
      . " to your delivery address " . $_POST["street"] . " " . $_POST["streetNumber"]
      . ", " . $_POST["zipcode"] . " " . $_POST["city"] . "</h4>");

    print_r("<h4 class=\"container d-flex justify-content-center alert alert-success w-75 mx-auto mt-3\">Your order will arrive at "
        . gmdate("H:i",time() + 3600 + 7200) . " (in 2 hours)</h4>");

    // Clear $_SESSION data so the input fields get clean
    $_SESSION = "";
    session_destroy();
  }
}

if(isset($_GET["food"])) {
  $_GET["food"] === "0" ? : $products = $foods;
}

$formSubmitted = !empty($_POST);
if ($formSubmitted) {
  $_SESSION = $_POST;
  handleForm($products, $totalValue);
}

// Set cookie for an hour
$totalValueAllOrders += $totalValue;
setcookie("totalValue", strval($totalValueAllOrders), time() + 3600);

require 'form-view.php';