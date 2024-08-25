<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        #barcodeContainer {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #barcode {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .barcode-item {
            margin: 10px;
            text-align: center;
        }
        .barcode-text {
            font-size: 56px;
            font-weight: bold;
            margin-top: 5px;
            letter-spacing: 10px; /* Increase letter spacing */
        }
        .input-group {
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="number"], input[type="text"] {
            width: 150px;
            padding: 5px;
            margin-right: 10px;
        }
        button {
            margin-top: 10px;
        }
    </style>
    <!-- Include the jsPDF library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script>
</head>
<body>

<div id="barcodeContainer">
    <h1>Barcode Generator</h1>
    <div class="input-group">
        <label for="barcodeData">Data:</label>
        <input type="text" id="barcodeData" placeholder="Enter data for barcode" />
    </div>
    <div class="input-group">
        <label for="barcodeWidth">Width (in mm):</label>
        <input type="number" id="barcodeWidth" placeholder="Width" value="30" step="0.1" />
    </div>
    <div class="input-group">
        <label for="barcodeHeight">Height (in mm):</label>
        <input type="number" id="barcodeHeight" placeholder="Height" value="15" step="0.1" />
    </div>
    <div class="input-group">
        <label for="barcodeCopies">Number of Copies:</label>
        <input type="number" id="barcodeCopies" placeholder="Copies" value="150" />
    </div>
    <button onclick="generateBarcode()">Generate Barcode</button>
    <button id="downloadButton" onclick="downloadPDF()" disabled>Download as PDF</button>
    <div id="barcode">
        <!-- Barcodes will be displayed here -->
    </div>
</div>

<script>
    async function generateBarcode() {
        const data = document.getElementById('barcodeData').value;
        const width = document.getElementById('barcodeWidth').value;
        const height = document.getElementById('barcodeHeight').value;
        const copies = document.getElementById('barcodeCopies').value;
        const barcodeDiv = document.getElementById('barcode');

        // Clear any previous barcodes
        barcodeDiv.innerHTML = '';

        // Generate the specified number of barcodes
        for (let i = 0; i < copies; i++) {
            const barcodeUrl = `https://barcode.orcascan.com/?type=code128&data=${encodeURIComponent(data)}&width=${width}&height=${height}`;

            // Create container div for barcode and text
            const barcodeItem = document.createElement('div');
            barcodeItem.classList.add('barcode-item');

            // Create image element
            const img = document.createElement('img');
            img.src = barcodeUrl;
            img.alt = 'Generated Barcode';
            img.style.marginTop = '10px';

            // Create text element
            const text = document.createElement('div');
            text.innerText = data;
            text.classList.add('barcode-text');

            // Append image and text to the container div
            barcodeItem.appendChild(img);
            barcodeItem.appendChild(text);

            // Append the container div to the barcodeDiv
            barcodeDiv.appendChild(barcodeItem);
        }

        document.getElementById('downloadButton').disabled = false;
    }

    async function downloadPDF() {
        const { jsPDF } = window.jspdf;

        try {
            const data = document.getElementById('barcodeData').value;
            const copies = parseInt(document.getElementById('barcodeCopies').value);

            const doc = new jsPDF({
                orientation: 'p',
                unit: 'mm',
                format: 'letter'
            });

            const colsPerRow = 15; // Number of columns
            const rowsPerPage = 10; // Number of rows
            const barcodeWidth = 30; // Width in mm
            const barcodeHeight = 15; // Height in mm
            const margin = 5; // Margin in mm
            const textHeight = 8; // Height of the text below the barcode
            const textFontSize = 14; // Font size for the barcode text
            const letterSpacing = 0.5; // Letter spacing in mm

            let xOffset = margin; // Starting x offset
            let yOffset = margin; // Starting y offset

            for (let i = 0; i < copies; i++) {
                const barcodeUrl = `https://barcode.orcascan.com/?type=code128&data=${encodeURIComponent(data)}&width=${barcodeWidth * 10}&height=${barcodeHeight * 10}`;

                // Fetch the image as a blob
                const imageBlob = await fetch(barcodeUrl).then(res => {
                    if (!res.ok) throw new Error('Failed to fetch image');
                    return res.blob();
                });

                const imageDataUrl = await convertToPng(imageBlob);

                // Add image and text to PDF
                if (xOffset + barcodeWidth > doc.internal.pageSize.getWidth() - margin) {
                    xOffset = margin; // Reset xOffset
                    yOffset += barcodeHeight + textHeight + margin; // Move to next row
                }
                if (yOffset + barcodeHeight + textHeight > doc.internal.pageSize.getHeight() - margin) {
                    doc.addPage(); // Add a new page if needed
                    xOffset = margin; // Reset xOffset
                    yOffset = margin; // Reset yOffset
                }

                // Add the barcode image
                doc.addImage(imageDataUrl, 'PNG', xOffset, yOffset, barcodeWidth, barcodeHeight);

                // Add the barcode text with letter spacing
                doc.setFontSize(textFontSize);
                doc.setFont('helvetica', 'bold');
                let currentX = xOffset + barcodeWidth / 2 - (data.length * (textFontSize * 0.35)) / 2; // Adjust starting X to center text
                const adjustedLetterSpacing = letterSpacing * doc.internal.scaleFactor; // Adjust letter spacing for scale
                for (let char of data) {
                    doc.text(char, currentX, yOffset + barcodeHeight + 6, { align: 'left' });
                    currentX += doc.getTextWidth(char) + adjustedLetterSpacing; // Increment X position
                }

                xOffset += barcodeWidth + margin; // Move to next column
            }

            // Save the PDF document
            doc.save('barcodes.pdf');
        } catch (error) {
            console.error('Error generating PDF document:', error);
        }
    }

    async function convertToPng(imageBlob) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onloadend = () => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);
                    resolve(canvas.toDataURL('image/png'));
                };
                img.onerror = reject;
                img.src = reader.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(imageBlob);
        });
    }
</script>

</body>
</html>
