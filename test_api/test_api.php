<?php
// test_api.php
function getWordDefinition($letter) {
    $json_file = 'test_api.json';
    if (!file_exists($json_file)) {
        return ["error" => "API JSON file not found."];
    }

    $data = json_decode(file_get_contents($json_file), true);

    $upper_letter = strtoupper($letter);
    if (array_key_exists($upper_letter, $data)) {
        return $data[$upper_letter];
    } else {
        return ["error" => "Letter not found in API."];
    }
}
?>
