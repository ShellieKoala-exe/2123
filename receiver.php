
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <!-- iOS Web App Meta Tags -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Swishify">
  <link rel="apple-touch-icon" href="images/logo.jpg">
  <!-- Link to Montserrat font from Google Fonts (you can adjust weights/styles as needed) -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">
  <link rel="manifest" href="/manifest.json">
  <title>Swishify</title>
</head>
<style>
  body {
    margin: 0;
    padding: 0;
    height: calc(var(--vh, 1vh) * 100);
    background-image: url('images/receiver.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    position: relative;
    font-family: 'Montserrat', sans-serif;
    /* Set Montserrat as the default font */
  }

  /* Add these styles in your CSS */
  #inputs-container.fixed {
    position: fixed;
    top: 20%;
    /* adjust this value to fit properly above the keyboard */
    left: 0;
    width: 100%;
    padding: 0 20px;
    /* adjust padding as needed */
  }

  input[type="text"],
  input[type="password"] {
    position: absolute;
    padding: 12px;
    /* Increased padding for bigger input fields */
    font-size: 18px;
    /* Increased font size */
    width: 270px;
    /* Adjust width as needed */
    border: none;
    /* Remove border */
    outline: none;
    /* Remove focus outline */
    background-color: transparent;
  }

  /* Example positions for the input fields */
  #input-mottagare {
    font-family: 'Montserrat', sans-serif;
    top: 260px;
    left: 20px;
    color: white;
  }

  #input-telefonnummer {
    font-family: 'Montserrat', sans-serif;
    top: 353px;
    left: 20px;
    color: white;
  }

  #input-belopp {
    font-family: 'Montserrat', sans-serif;
    top: 423px;
    left: 20px;
    color: white;
  }

  #input-meddelande {
    font-family: 'Montserrat', sans-serif;
    top: 489px;
    left: 20px;
    color: white;
  }

  .swisha-btn {
    width: 350px;
    cursor: pointer;
    transition: width 0.3s ease;
    position: absolute;
    bottom: 80px;
    /* 100px up from the bottom */
    left: 50%;
    /* centering horizontally */
    transform: translateX(-50%);
    /* adjust for the button's width to truly center it */
  }

  iframe {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
    z-index: 10;
    background-color: rgba(0, 0, 0, 0.8);
    /* Optional: semi-transparent black background to provide an overlay effect */
  }

  #openQRScannerBtn {
  position: absolute;
  top: 70px;
  /* adjust as needed */
  left: 50%;
  transform: translateX(-50%);
  padding: 10px 20px;
  font-size: 18px;
  cursor: pointer;
  border-color: transparent;
  background-color: transparent;
}

#closeButton {
            background: transparent;  /* Make the button transparent */
            color: transparent;
            border: none;  /* Remove the border */
            position: absolute;  /* Position absolutely within the iframe */
            top: 70px;  /* 10px from the top */
            left: 15px;  /* 10px from the left */
            padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
        }
</style>

<body>
  <div class="close-button" id="closeOverlayBtn"></div>
  <div id="inputs-container">
    <button id="openQRScannerBtn"></button>
<iframe src="QR.php" id="qrFrame" style="display:none;"></iframe>
    <input type="text" id="input-mottagare" placeholder="Mottagare (privat / företag)" required>
    <input type="text" id="input-telefonnummer" placeholder="Telefonnummer (+46 / 123)" required>
    <input type="text" id="input-belopp" placeholder="Belopp (t.ex. 100)" required>
    <input type="text" id="input-meddelande" placeholder="Meddelande (t.ex. 'Grattis!')">

    <button id="closeButton">Close</button>

  </div>
  <img src="images/swishaBtn.png" alt="Swisha Button" class="swisha-btn" id="showOverlay2Btn">
  <iframe src="auth.php" id="bankid"></iframe>
</body>
<script>

window.addEventListener('message', function(event) {
    // Add security checks here like checking event.origin
    
    if(event.data.type === 'closeIframe') {
        document.getElementById('bankid').style.display = 'none';
    } else if(event.data.type === 'phoneNumberUpdate') {
        document.getElementById('input-telefonnummer').value = event.data.value;
        document.getElementById('qrFrame').style.display = 'none';
    }
}, false);

document.getElementById('input-telefonnummer').addEventListener('keydown', function (e) {
    let input = e.target;

    if (e.key === 'Backspace' && input.selectionStart === 4) {
        e.preventDefault(); // Prevent the default behavior
        input.value = input.value.substring(4); // Remove the "+46 " prefix
    }
});

  document.getElementById('openQRScannerBtn').addEventListener('click', function() {
  document.getElementById('qrFrame').style.display = 'block';
});

window.addEventListener('message', function(event) {
  // Add security checks here like checking event.origin
  document.getElementById('input-telefonnummer').value = event.data;
  document.getElementById('qrFrame').style.display = 'none'; // Hide iframe after receiving data
});

document.getElementById('closeButton').addEventListener('click', function() {
            // Close the overlay by calling the closeOverlay function from the parent window
            window.parent.closeOverlay();
        });

  const showOverlay2Btn = document.getElementById('showOverlay2Btn');
  const inputName = document.getElementById('input-mottagare');
  const inputTelefonnummer = document.getElementById('input-telefonnummer');
  const inputBelopp = document.getElementById('input-belopp');
  const inputMeddelande = document.getElementById('input-meddelande');

  showOverlay2Btn.addEventListener('click', function () {
    const nameValue = inputName.value.trim();
    const telefonnummerValue = inputTelefonnummer.value.trim();
    const beloppValue = inputBelopp.value.trim();
    const meddelandeValue = inputMeddelande.value.trim();

    localStorage.setItem('nameValue', nameValue);
    localStorage.setItem('telefonnummerValue', telefonnummerValue);
    localStorage.setItem('beloppValue', beloppValue);
    localStorage.setItem('meddelandeValue', meddelandeValue);

    // Validation using RegEx
    const nameRegex = /^([A-Za-zÅÄÖåäö'&-]+)\s([&]|and)?\s?([A-Za-zÅÄÖåäö'&-]+)$/;

    // One name and one surname
    const telefonnummerRegex = /^\+46\s[0-9]{2}\s[0-9]{3}\s[0-9]{2}\s[0-9]{2}$/;  // Format: +46 79 339 09 37
    const beloppRegex = /^\d+(\.\d{1,2})?$/;  // Only numbers and optional two decimals

    if (!beloppRegex.test(beloppValue) || parseFloat(beloppValue) <= 0) {
      alert('Endast heltal är tillåtet som Belopp.');
      return;
    }
    
    if (!nameRegex.test(nameValue)) {
        alert('Endast "Efternamn Förnamn" / "Företag & Co".');
        return;
    }

    // If validation passes
    const url = 'auth.php';

    bankid.src = url;
    bankid.style.display = 'block';
  });

  document.getElementById('input-telefonnummer').addEventListener('input', function (e) {
    let input = e.target;
    let originalValue = input.value;
    let value = originalValue.replace(/\D/g, '');  // Remove all non-digits
    
    // Check if the original input starts with "1"
    if (originalValue.startsWith("1")) {
        // Limit to 10 digits
        if (value.length > 10) {
            value = value.slice(0, 10);
        }

        // Format: XXX XXX XX XX
        if (value.length > 3) {
            value = value.slice(0, 3) + ' ' + value.slice(3);
        }
        if (value.length > 7) {
            value = value.slice(0, 7) + ' ' + value.slice(7);
        }
        if (value.length > 10) {
            value = value.slice(0, 10) + ' ' + value.slice(10);
        }
    } else {
        // Limit total number length to 11 digits (excluding the +46 prefix)
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        
        // Ensure it starts with "46"
        if (!value.startsWith('46')) {
            value = '46' + value;
        }

        value = value.substring(2); // Remove "46"

        // Format: +46 XX XXX XX XX
        if (value.length > 2) {
            value = value.slice(0, 2) + ' ' + value.slice(2);
        }
        if (value.length > 6) {
            value = value.slice(0, 6) + ' ' + value.slice(6);
        }
        if (value.length > 9) {
            value = value.slice(0, 9) + ' ' + value.slice(9);
        }

        value = "+46 " + value;
    }

    input.value = value;
});
</script>

</html>