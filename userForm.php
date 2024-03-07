<!DOCTYPE html>
<html>

<head>
  <style>
    body {
      font-family: "Arial", sans-serif;
      padding: 10px;
      width: 100vw;
      display: flex;
      justify-content: center;
    }

    span {
      font-size: 14px;
      color: #616161;
    }

    textarea {
      resize: none;
    }

    textarea:focus {
      border: 3px solid #235594;
      outline: none;
    }

    input[type=text]:focus {
      border: 3px solid #235594;
      outline: none;
    }

    input[type=submit] {
      color: #fff;
      background-color: #235594;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      width: 100%;
      height: 30px;
    }

    .input {
      padding: 12px 20px;
      box-sizing: border-box;
      border: 2px solid #e0e0e0;
      border-radius: 4px;
      width: 100%;
    }

    .formItem {
      display: flex;
      flex-direction: column;
      gap: 5px;
      width: 100%;
    }

    .formContainer {
      width: 50%;
      padding: 10px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .error {
      color: red;
    }
  </style>
</head>

<body>
  <!-- Set up variables -->
  <?php
  $name = $email = $reason = $message = "";
  $nameErr = $emailErr = $reasonErr = $messageErr = "";
  $formErr = false;
  $from = "a valid email";
  $to = "";
  $subject = "";
  $content = "Here's your info: " . $name . "<br>" . $email . "<br>" . $reason . "<br>" . $message . "<br>";
  $headers = " From : " . $from . "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["name"])) {
      $nameErr = "Name is required";
    } else {
      $name = checkValue($_POST["name"]);
      if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $nameErr = "Only letters and white space allowed";
      }
    }

    if (empty($_POST["email"])) {
      $emailErr = "Email is required";
    } else {
      $email = checkValue($_POST["email"]);
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
      }
    }

    if (empty($_POST["reason"])) {
      $reasonErr = "Reason is required";
    } else {
      $reason = checkValue($_POST["reason"]);
      if (!preg_match("/^[a-zA-Z-' ]*$/", $reason)) {
        $reasonErr = "Only letters and white space allowed";
      }
    }

    if (empty($_POST["message"])) {
      $messageErr = "Message is required";
    } else {
      $message = checkValue($_POST["message"]);
      if (!preg_match("/^[a-zA-Z-' ]*$/", $message)) {
        $messageErr = "Only letters and white space allowed";
      }
    }

    submitForm($to, $subject, $content, $headers);
  }
  function checkValue($value)
  {
    $value = trim($value);
    $value = stripslashes($value);
    return htmlspecialchars($value);
  }
  function submitForm($to, $subject, $content, $headers)
  {
    $anyErrors = empty($nameErr) && empty($emailErr) && empty($reasonErr) && empty($messageErr);
    $emptyInputs = empty($name) || empty($email) || empty($reason) || empty($message);
    if ($anyErrors || !$emptyInputs) {
      mail($to, $subject, $content, $headers);
    } else {
      echo htmlspecialchars($_SERVER['PHP_SELF']);
    }
  }
  ?>

  <!-- Make a form -->
  <form class="formContainer" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <div class="formItem">
      <span>Name*</span>
      <input class="input" type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
      <span class="error"><?php echo $nameErr; ?></span>
    </div>
    <div class="formItem">
      <span>Email*</span>
      <input class="input" type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
      <span class="error"><?php echo $emailErr; ?></span>
    </div>
    <div class="formItem">
      <span>Reason*</span>
      <input class="input" type="text" name="reason" value="<?php echo htmlspecialchars($reason); ?>">
      <span class="error"><?php echo $reasonErr; ?></span>
    </div>
    <div class="formItem">
      <span>Message*</span>
      <textarea class="input" name="message" rows="10"><?php echo htmlspecialchars($message); ?></textarea>
      <span class="error"><?php echo $messageErr; ?></span>
    </div>
    <input type="submit" name="submit" value="Submit">
    <span>*required</span>
  </form>
</body>

</html>