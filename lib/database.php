<?php
function db_connect(array $config) : mysqli
{
    $db = mysqli_connect(
        $config['host'],
        $config['username'],
        $config['password'],
        $config['database'],
        $config['port'] ?? 3306
    );

    mysqli_set_charset($db, $config['charset'] ?? 'utf8mb4');

    if (mysqli_connect_errno()) {
        trigger_error("DB Error: " . mysqli_connect_error(), E_USER_ERROR);
    }

    return $db;
}


function db_disconnect()
{
    global $database;

    return mysqli_close($database);
}


function db_query(string $sql)
{
    global $database;

    $result = mysqli_query($database, $sql);

    if (mysqli_errno($database)) {
        trigger_error("DB Error : " . mysqli_error($database), E_USER_ERROR);
    }

    return $result;
}
