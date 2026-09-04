<?php 
$clpath               = $this->get_variable("clpath");
$recaptcha_public_key = $this->get_variable("recaptcha_public_key");

if($clpath != "" && Configuration::get_instance()->read('enable_captcha_verification') == 1){?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="<?php echo DEFAULT_CHARSET;?>">
    <script src="//www.google.com/recaptcha/api.js" async defer></script>
    <title>Bot Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f7f7f7;
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
      	.top-section, .bottom-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .g-recaptcha {
            display: inline-block;
            margin: 20px 0;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:disabled {
            background-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bot Validation</h1>
        <p>Please verify that you are not a bot to proceed.</p>
        <form action="<?php echo $this->make_url($clpath);?>" method="POST" id="captcha-form">  
          <div class="top-section">
            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public_key; ?>" data-callback="enableSubmitButton"></div>
          </div>
    	  <div class="bottom-section">
      		<button type="submit" id="submit-button" disabled>Validate</button>
          </div>  
        </form>
    </div>
    <script>
        function enableSubmitButton() {
            document.getElementById('submit-button').disabled = false;
        }
    </script>
</body>
</html>
<?php }?>