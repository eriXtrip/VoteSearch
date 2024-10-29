<?php 
    include 'dbh.inc.php';
    $prec = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Vote</title>
    
    <link href="SearchVote.css" rel="stylesheet" />
</head>
<body>
    <article class="container">
                <div class="left-column">
                    <h1>PRECINCT FINDER</h1>
                        <table>
                            <tr>
                                <td>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="data" class="data">
                                        <div class="form-container">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <div class="input-container">
                                                            <input type="text" id="data" name="data" required>
                                                            <button type="submit" name="search">Search</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php 
                                        if (isset($_POST["search"]) || (isset($_POST["prec"]) && isset($_POST["data"]))) {
                                            $data = isset($_POST["data"]) ? htmlspecialchars($_POST["data"]) : '';
                                            $prec = isset($_POST["prec"]) ? htmlspecialchars($_POST["prec"]) : '';
                                            $name = isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : '';

                                            // Prepare the SQL statement
                                            $stmt = $pdo->prepare("
                                                SELECT * 
                                                FROM information 
                                                WHERE 
                                                    LOWER(Name) LIKE LOWER(:data) OR 
                                                    LOWER(Address) LIKE LOWER(:data) OR 
                                                    LOWER(Prec) LIKE LOWER(:data)
                                            ");

                                            $searchTerm = "%" . $data . "%";

                                            // Bind the parameter to prevent SQL injection
                                            $stmt->bindParam(':data', $searchTerm);
                                            
                                            // Execute the query
                                            $stmt->execute();
                                            
                                            // Fetch all matching rows
                                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            
                                            // Display header
                                            echo '<table style="width: 100%;">'; // Set table width to 100%
                                            echo '<tr style="background-color: #7671FA; color: white;">';
                                            echo '<th class="headerPrecinct" colspan="1">PRECINCT</th>'; // Left align, brown background
                                            echo '<th class="headerName" colspan="5">NAME</th>'; // Left align for the Name column
                                            echo '</tr>';
                                            echo '</table>';

                                            // Use a tbody for results
                                            echo '<table class="scrollable-results" style="width: 100%;">'; // Set table width to 100%

                                            // Check if any results were found
                                            if ($results) {
                                                foreach ($results as $row) {
                                                    // Make the row clickable with JavaScript
                                                    echo '<tr class="resultFind" onclick="setHiddenValues(\'' . htmlspecialchars($data) . '\', \'' . htmlspecialchars($row['Prec']) . '\',\'' . htmlspecialchars($row['Name']) . '\', this)">';
                                                    echo '<td class="resultPrec" colspan="2">' . htmlspecialchars($row['Prec']) . '</td>'; // Center-align for Precinct
                                                    echo '<td class="resultName" colspan="8">' . htmlspecialchars($row['Name']) . '</td>'; // Left-align for Name
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="10" style="text-align: center;">No records found.</td></tr>'; // Display a message if no results
                                            }
                                            echo '<tr><td></td></tr></table>';
                                        }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="right-column">
                        <div class="floorPlan">
                            <div class="room1"></div>
                            <div class="room2"></div>
                            <div class="room3" <?php if ($prec == '0464C') echo 'style="background-color: #332ec7c5;"'; ?>></div>
                            <div class="room4" <?php if ($prec == '0466A') echo 'style="background-color: #332ec7c5;"'; ?>></div>
                            <div class="room5" <?php if ($prec == '0465B') echo 'style="background-color: #332ec7c5;"'; ?>></div>
                            <div class="room6" <?php if ($prec == '0465A') echo 'style="background-color: #332ec7c5;"'; ?>></div>
                            <div class="room7" <?php if ($prec == '0464B') echo 'style="background-color: #332ec7c5;"'; ?>></div>
                            <div class="room8" <?php if ($prec == '0464A') echo 'style="background-color: #332ec7c5;"'; ?>></div>
                            <div class="room9" <?php if ($prec == '0463A') echo 'style="background-color: #332ec7c5;"'; ?>></div>
                            <div class="room10" <?php if ($prec == '0462B') echo 'style="background-color: #332ec7c5;"'; ?>></div>
                            <div class="room11" <?php if ($prec == '0461A') echo 'style="background-color: #332ec7c5;"'; ?>></div>
                            <div class="room12"></div>
                            <div class="room13"></div>
                            <div class="room14"></div>
                            <div class="room15"></div>
                        </div>
                        <?php 
                            // Define $room based on the value of $prec
                            if (isset($_POST["prec"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
                                $prec = $_POST["prec"];

                                switch ($prec) {
                                    case '0464C':
                                        $room = 'Room 11-3';
                                        break;
                                    case '0466A':
                                        $room = 'Room 11-4';
                                        break;
                                    case '0465B':
                                        $room = 'Room 21-5';
                                        break;
                                    case '0465A':
                                        $room = 'Room 21-6';
                                        break;
                                    case '0464B':
                                        $room = 'Room 21-7';
                                        break;
                                    case '0464A':
                                        $room = 'Room 31-8';
                                        break;
                                    case '0463A':
                                        $room = 'Room 31-9';
                                        break;
                                    case '0462B':
                                        $room = 'Room 31-0';
                                        break;
                                    case '0461A':
                                        $room = 'Room 31-1';
                                        break;
                                    default:
                                        $room = 'Unknown Room';
                                }

                                // Display the table with $prec, $name, and $room
                                echo '<table>';
                                echo '<tr style="background-color: #7671FA; color: white; padding: 10px;">';
                                echo '<th style="padding-right: 20px;">'. $prec .'</th>';
                                echo '<th colspan="5" style="padding: 10px; text-align: left;">'. $name .'</th>';
                                echo '<th style="padding-right: 20px;">'. $room .'</th>';
                                echo '</tr>';
                                echo '</table>';
                            }
                        ?>
                    </div>
    </article>

    <form id="hiddenForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="data" id="hiddensearch"> <!-- Hidden field for the search value -->
        <input type="hidden" name="prec" id="hiddenprec"> <!-- Hidden field for the precinct -->
        <input type="hidden" name="name" id="hiddenname"> 
        <input type="hidden" name="action" value="information"> <!-- Action for the search -->
    </form>
    

    <script>
        function setHiddenValues(data, prec, name) {
            document.getElementById('hiddensearch').value = data; // Set the search term
            document.getElementById('hiddenprec').value = prec; // Set precinct for future use
            document.getElementById('hiddenname').value = name; 

            
            document.getElementById('hiddenForm').submit(); // Submit the hidden form for search data
        }
    </script>

</body>
</html>
