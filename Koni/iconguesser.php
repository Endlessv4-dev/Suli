<link rel="stylesheet" href="css/style.css">
<script src="js/script.js"></script>

<?php

    require '../Connection/config.php';


    $lekerdezes = "SELECT * FROM items ORDER BY RAND() LIMIT 1";
    $talalt_sor = $conn->query($lekerdezes);
    $item = $talalt_sor->fetch_assoc();
    $counter = 5;
    echo "<div class='item-container'>";
        echo "<h1>Guess the item!</h1>";
        echo "<img id='item-image' src='../Items/$item[name]/$item[icon]'> <br>";
        echo "<style>.item-container img {filter: grayscale(100%) blur(10px); transform: rotate(90deg);}</style>";
        if(isset($_POST['submit-btn'])) {
                if($item['name'] == $_POST['guess']) {
                echo "<h2>Correct!</h2>";
                echo "<style>.item-container img {filter: blur(0px); transform: rotate(0deg);}</style>";
            }else if ($counter > 4) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: blur(10px); transform: rotate(90deg);}</style>";
            }else if ($counter > 3) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: blur(8px); transform: rotate(90deg);}</style>";
            }else if ($counter > 2) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: blur(6px); transform: rotate(90deg);}</style>";
            }else if ($counter > 1) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: blur(4px); transform: rotate(90deg);}</style>";
            }else if ($counter > 0) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: blur(2px); transform: rotate(90deg);}</style>";
            }else {
                echo "<h2>Wrong! The correct answer was: " . $item['name'] . "</h2>";
                echo "<style>.item-container img {filter: blur(0px); transform: rotate(0deg);}</style>";
            }
        }

    echo "</div>";
    echo $counter;
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Icon guesser</title>
</head>
<body>
    <form method = "post" class = "guess-form">
        <input type="text" name = "guess" placeholder="Guess the item name" class = "guess-input" >
        <button type="button" onclick = "submitGuess()" id = 'submit-btn' class = "submit-btn">Submit Guess</button>
    </form>
</body>
</html>
<style>
    
</style>
<script>
    const random = Math.random(1, 4);
    const anyad = random*90;
    document.getElementById("submit-btn").addEventListener("click", function() {
        document.getElementById("item-image").style.filter = "blur(10px)";
        document.getElementById("item-image").style.transform = "rotate(" + anyad + "deg)";
    });

        
</script>



