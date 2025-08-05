<?php include 'test_api.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Smart Word Lookup</title>
</head>
<body>
    <h1>Get a Word from Your Name</h1>
    <form method="post" action="">
        <label for="username">Enter your name:</label><br>
        <input type="text" name="username" required><br><br>

        <input type="submit" value="Generate Word">
    </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $name_cleaned = strtoupper(preg_replace("/[^A-Za-z]/", '', $name));
    $selected_letter = null;
    $letter_source = '';
    $definition_data = [];

    // Load the JSON data once
    $json_file = 'test_api.json';
    $data = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];

    // Formula: Pick first valid letter from name that exists in test_api.json
    for ($i = 0; $i < strlen($name_cleaned); $i++) {
        $char = $name_cleaned[$i];
        if (isset($data[$char])) {
            $selected_letter = $char;
            $letter_source = "We used the first letter in your name that matches the dictionary: <strong>'$char'</strong>";
            break;
        }
    }

    if (!$selected_letter) {
        echo "<p><strong>Error:</strong> None of the letters in your name match available definitions.</p>";
    } else {
        $definition_data = getWordDefinition($selected_letter);

        echo "<h2>Letter Selected: '$selected_letter'</h2>";
        echo "<p>$letter_source</p>";

        if (isset($definition_data['error'])) {
            echo "<p><strong>Error:</strong> " . $definition_data['error'] . "</p>";
        } else {
            echo "<p><strong>Word:</strong> " . htmlspecialchars($definition_data['word']) . "</p>";
            echo "<p><strong>Definition:</strong> " . htmlspecialchars($definition_data['definition']) . "</p>";
        }

        echo "<hr><p><em>Formula used:</em> Loop through name (A-Z only), find the first letter that matches a key in our dictionary JSON.</p>";
    }
}
?>
</body>
</html>
