<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rust images</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background-color: #2c2c2c;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            flex-direction: column;
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: flex-start;
        }

        .search-wrapper {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #searchBar {
            flex: 1;
            padding: 12px;
            background-color: #333;
            color: white;
            border: 1px solid #444;
            border-radius: 4px;
            font-size: 1em;
        }

        .button-wrapper {
            display: flex;
            gap: 10px;
        }
        .copy-all-btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1.2em;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        .copy-btn:hover,
        .copy-all-btn:hover {
            background: #337936;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #444;
        }

        th {
            background-color: #444;
            font-weight: bold;
        }

        td img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            max-height: 100px;
        }

        .path-input-wrapper {
            position: relative;
            width: 100%;
        }

        .path-input {
            width: calc(100% - 60px);
            padding: 5px;
            font-size: 0.9em;
            background-color: #333;
            color: white;
            border: 1px solid #444;
            border-radius: 4px;
            pointer-events: none;
            user-select: all;
            height: 40px;
            margin-left: 50px;
        }

        .copy-btn {
            position: absolute;
            left: 0;
            top: 0;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 0 15px;
            font-size: 1.2em;
            cursor: pointer;
            border-radius: 4px 0 0 4px;
            height: 52px;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-message {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: none;
            font-size: 1em;
            z-index: 1000;
        }

        .disclaimer {
            background-color: #333;
            color: #bbb;
            padding: 20px;
            border-radius: 5px;
            font-size: 0.9em;
            text-align: center;
            margin-top: 20px;
            border: 1px solid #444;
        }

        .disclaimer strong {
            color: white;
        }
    </style>
</head>

<body>
    <h1>Rust Images</h1>
    <div class="container">
        <div class="disclaimer">
            <p><strong>Disclaimer:</strong> All images and image links on this website are provided for use under the understanding that they are owned by Facepunch Studios, the creators of Rust. You are free to use the images and links for any personal or commercial purpose, but the intellectual property rights remain with Facepunch Studios. We are not affiliated with, endorsed by, or in any way connected to Facepunch Studios or Rust.</p>
        </div>
        <div class="search-wrapper">
            <div class="button-wrapper">
                <button id="copyAllBtn" class="copy-all-btn">
                    <i class="fas fa-copy"></i> Copy All Links
                </button>
            </div>
            <input type="text" id="searchBar" onkeyup="filterTable()" placeholder="Search for items..." pattern="[a-zA-Z0-9\s]* " maxlength="50" title="Only letters, numbers, and spaces are allowed">
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Image</th>
                    <th>Image Link</th>
                </tr>
            </thead>
            <tbody id="itemTable">
                <?php
                $webpDir = 'webp';
                $imgDir = 'img';

                if (is_dir($webpDir)) {
                    $images = glob("$webpDir/*.webp");

                    foreach ($images as $image) {
                        $imageName = basename($image);
                        $imageNameWithoutExtension = pathinfo($imageName, PATHINFO_FILENAME);
                        $displayName = htmlspecialchars(str_replace('-', ' ', $imageNameWithoutExtension));
                        $imageUrlWebp = "/$webpDir/$imageName";
                        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
                        $domain = $protocol . "://" . $_SERVER['HTTP_HOST']; 
                        $imageUrlImg = "$domain/$imgDir/$imageNameWithoutExtension.png"; 

                        echo "<tr>";
                        echo "<td><strong>" . $displayName . "</strong></td>";
                        echo "<td><a href='" . $imageUrlImg . "' target='_blank'> 
                <img src='" . $imageUrlWebp . "' alt='" . $displayName . "' loading='lazy'>
              </a></td>";
                        echo "<td>
                <div class='path-input-wrapper'>
                    <button class='copy-btn' data-clipboard-text='" . $imageUrlImg . "'><i class='fas fa-copy'></i></button>
                    <input type='text' class='path-input' value='" . $imageUrlImg . "' readonly>
                </div>
              </td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="success-message" id="successMessage">Copied to clipboard!</div>

    <script>
        var clipboard = new ClipboardJS('.copy-btn');

        clipboard.on('success', function(e) {
            showMessage('Copied to clipboard!');
            e.clearSelection();
        });

        clipboard.on('error', function() {
            showMessage('Failed to copy!', true);
        });

        function showMessage(text, isError = false) {
            var message = document.getElementById('successMessage');
            message.innerText = text;
            message.style.backgroundColor = isError ? '#dc3545' : '#28a745';
            message.style.display = 'block';

            setTimeout(function() {
                message.style.display = 'none';
            }, 2000);
        }

        function sanitizeInput(input) {
            return input.replace(/[^a-zA-Z0-9\s]/g, '');
        }

        function filterTable() {
            var input = document.getElementById('searchBar');
            input.value = sanitizeInput(input.value);
            var filter = input.value.toLowerCase();
            var table = document.getElementById('itemTable');
            var rows = table.getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                if (cells.length > 0) {
                    var itemName = cells[0].textContent || cells[0].innerText;
                    if (itemName.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }

        document.getElementById('searchBar').addEventListener('input', function(e) {
            this.value = sanitizeInput(this.value);
        });

        document.getElementById('copyAllBtn').addEventListener('click', function() {
            var links = [];
            var rows = document.getElementById('itemTable').rows;

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                if (cells.length > 0) {
                    var imageLink = cells[2].getElementsByTagName('input')[0].value;
                    links.push(imageLink);
                }
            }

            var allLinks = links.join('\n');
            navigator.clipboard.writeText(allLinks).then(function() {
                showMessage('All links copied to clipboard!');
            }).catch(function() {
                showMessage('Failed to copy links!', true);
            });
        });
    </script>
</body>

</html>