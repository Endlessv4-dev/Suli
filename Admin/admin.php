<?php 

    require "../Connection/config.php";
    require "../Functions/message.php";

    if (!isset($_COOKIE['admin'])) {
        header("Location: a_login.php");
    }

    if (isset($_POST['save-btn'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $tier = mysqli_real_escape_string($conn, $_POST['tier']);
        $cost = (int)$_POST['cost'];

        $icon_file = $_FILES['upload-icon']['name'];
        $tmp_icon_file = $_FILES['upload-icon']['tmp_name'];
        
        $audio_file = $_FILES['upload-audio']['name'];
        $tmp_audio_file = $_FILES['upload-audio']['tmp_name'];

        $folder = dirname(__DIR__)."\\Items\\".$name;
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $path_icon = $folder."\\".$icon_file;

        if (move_uploaded_file($tmp_icon_file, $path_icon)) {
            $audio_db_value = "-";

            if (!empty($audio_file) && $_FILES['upload-audio']['error']=== UPLOAD_ERR_OK) {
                $path_audio = $folder."\\".$audio_file;
                if (move_uploaded_file($tmp_audio_file, $path_audio)) {
                    $audio_db_value = $audio_file;
                }
                else {
                    Error("Error while trying to upload audio");
                }
            }

            $effects = $_POST['effect_types'];
            $description = $_POST['description'];
            $name = $conn->real_escape_string($name);
            $effects = $conn->real_escape_string($effects);
            $description = $conn->real_escape_string($description);
            $icon_file = $conn->real_escape_string($icon_file);
            $audio_db_value = $conn->real_escape_string($audio_db_value);

            $conn->query("INSERT INTO items VALUES (id, '$name', '$tier', $cost, '$effects', '$description', '$audio_db_value','$icon_file')");

            $stmt = "SELECT * FROM items WHERE name = '$name'";
            $item = $conn->query($stmt)->fetch_assoc();

            $names = $_POST['stat_names'];
            $values = $_POST['stat_values'];
            $types = $_POST['stat_types'];

            for ($i = 0; $i < count($names); $i++) {
                if (!empty($names[$i]) && !empty($values[$i]) && !empty($types[$i])) {
                    $conn->query("INSERT INTO item_stats VALUES($item[id], '$names[$i]', $values[$i], '$types[$i]')");
                }
            }
        }
        else {
            Error("Error while trying to upload icon.");
        }

        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Admin Panel</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="../index.php" class='nav-item' style="color: #4d669b;">Home</a></li>
            <li><a href="../leaderboard.php" class='nav-item'>Leaderboard</a></li>
            <li><a href="../games/background/hatteres.php" class='nav-item'>Classic</a></li>
            <li><a href="../games/description/leiras.php" class='nav-item'>Description</a></li>
            <li><a href="../games/iconguesser/iconguesser.php" class='nav-item'>Icon</a></li>
            <li><a href="../games/soundguesser/soundguesser.php" class='nav-item'>Sound</a></li>
            <li><a href="../games/stats/statok.php" class='nav-item'>Stats</a></li>
            <li><a href="../games/timer/idore.php" class='nav-item'>Time Attack</a></li>
            <?php if(isset($_COOKIE['userid'])){ ?>
                <li><a class='reglog' href="../logout.php" style="color: #ff0000;">Logout</a></li>
                <?php if($_COOKIE['adminid'] == 1){ ?>
                    <li><a class='admin' href="../Admin/admin.php" style="color: #ff6060;">Admin</a></li>  
                <?php } ?>
            <?php } else { ?>
                <li><a class='reglog' href="../reglog.php" style="color: #008000">Login</a></li>
            <?php } ?>
        </ul>
    </nav>
    <div class="container auth-containter">
        <form method="post" enctype="multipart/form-data">
            <div class="auth-group">
                <label for="audio" class="custom-file-upload">Audio</label>
                <input type="file" name="upload-audio" id="audio" >
            </div>
            <div class="auth-group">
                <label for="icon" class="custom-file-upload">Icon</label>
                <input type="file" name="upload-icon" id="icon" required>
            </div>
            <div class="auth-group">
                <input type="text" name="name" placeholder="Name" class="auth-input" required>
            </div>
            <div class="auth-group">
                <input type="text" name="tier" placeholder="Tier" class="auth-input" required>
            </div>
            <div class="auth-group">
                <input type="number" name="cost" placeholder="Cost" class="auth-input" required>
            </div>
            <div class="auth-group">
                <textarea name="description" placeholder="Description" class="auth-input" ></textarea>
            </div>
            <div class="auth-group">
                <select name="effect_types" class="auth-input" >
                    <option value="none">None</option>
                    <option value="passive">Passive</option>
                    <option value="active">Active</option>
                    <option value="passive_active">Passive / Active</option>
                </select>
            </div>

            <h3>Stats</h3>
            <div id="stat-container">
                <div class="stat-row">
                    <div class="auth-group">
                        <select name="stat_names[]" class="auth-input">
                            <option value="ability_haste">Ability Haste</option>
                            <option value="ability_power">Ability Power</option>
                            <option value="armor">Armor</option>
                            <option value="armor_penetration">Armor Penetration</option>
                            <option value="attack_damage">Attack Damage</option>
                            <option value="attack_speed">Attack Speed</option>
                            <option value="base_health_regen">Base Health Regen</option>
                            <option value="base_mana_regen">Base Mana Regen</option>
                            <option value="critical_strike_chance">Critical Strike Chance</option>
                            <option value="gold_income">Gold Income</option>
                            <option value="heal_and_shield_power">Heal and Shield Power</option>
                            <option value="health">Health</option>
                            <option value="lethality">Lethality</option>
                            <option value="life_steal">Life Steal</option>
                            <option value="magic_penetration">Magic Penetration</option>
                            <option value="magic_resistance">Magic Resistance</option>
                            <option value="mana">Mana</option>
                            <option value="movement_speed">Movement Speed</option>
                            <option value="omnivamp">Omnivamp</option>
                            <option value="slow_resist">Slow Resist</option>
                            <option value="summoner_spell_haste">Summoner Spell Haste</option>
                            <option value="tenacity">Tenacity</option>
                            <option value="ultimate_haste">Ultimate Haste</option>
                        </select>
                    </div>
                    <div class="auth-group">
                        <input type="number" step="0.1" name="stat_values[]" placeholder="Value"  class="auth-input" required>
                    </div>
                    <div class="auth-group">
                    <select name="stat_types[]" class="auth-input">
                        <option value="flat">Flat</option>
                        <option value="%">Percentage (%)</option>
                    </select>
                    </div>
                </div>
            </div>

            <button type="button" onclick="addStat()">+ Add Another Stat</button>
            <input type="submit" name="save-btn" value="Save Item" class="auth-btn-login" style="max-width: 200px;">
        </form>
    </div>

    <script>
        function addStat() {
            const container = document.getElementById('stat-container');
            const newRow = document.querySelector('.stat-row').cloneNode(true);
            newRow.querySelector('input').value = "";
            container.appendChild(newRow);
        }
    </script>
</body>
</html>