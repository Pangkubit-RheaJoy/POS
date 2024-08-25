<?php
session_start();
require 'vendor/autoload.php';

// Adjust paths if necessary, based on the library's directory structure
require_once 'php-barcode-generator/src/BarcodeGenerator.php';
require_once 'php-barcode-generator/src/BarcodeGeneratorPNG.php';

// No need to manually include BarcodeException.php as it's used internally
// require_once 'php-barcode-generator/src/Exceptions/BarcodeException.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

if (isset($_POST['text'])) {
    $text = $_POST['text'];

    try {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($text, $generator::TYPE_CODE_128);

        header('Content-Type: image/png');
        echo $barcode;
        exit;
    } catch (Exception $e) {
        // Handle exceptions if necessary
        echo 'Error generating barcode: ', $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Generator</title>
</head>
<body>
    <h1>Code 128 Barcode Generator</h1>
    <form method="post">
        <label for="text">Enter Text:</label>
        <input type="text" id="text" name="text" required>
        <button type="submit">Generate Barcode</button>
    </form>

    <?php if (isset($_POST['text'])): ?>
        <h2>Generated Barcode:</h2>
        <img src="barcode_generator.php?text=<?php echo urlencode($_POST['text']); ?>" alt="Barcode">
    <?php endif; ?>
</body>
</html>
