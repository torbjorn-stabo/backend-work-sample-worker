<?php
    try {
        $pdo = new PDO( 
            'mysql:host=127.0.0.1;dbname=test;charset=utf8', 'test', 'test'); 
    } catch (\PDOException $e) {
        echo "Unable to connect to database: {$e->getMessage()}\n";
        exit();
    }

    if (!$pdo->beginTransaction()) {
        echo "Unable to begin transaction.\n";
        exit();
    }

    $res = $pdo->query('SELECT `id`, `url` FROM `backend_work_sample_worker` WHERE `status`="NEW" LIMIT 1 LOCK IN SHARE MODE');
    if (!$res) {
        $pdo->rollBack();
        echo "Error selecting row, aborting: {$pdo->errorInfo()[2]}\n";
        exit();
    }

    $row = $res->fetch(PDO::FETCH_ASSOC);
    if (empty($row)) {
        $pdo->rollBack();
        echo "No rows to process, leaving.\n";
        exit();
    }

    $res = $pdo->query('UPDATE `backend_work_sample_worker` SET `status`="PROCESSING" WHERE `id`='.$row['id']);
    if (!$res || $res->rowCount()!==1) {
        $pdo->rollBack();
        echo "Error while updating row, aborting: {$pdo->errorInfo()[2]}\n";
        exit();
    }

    $pdo->commit();

    echo "Processing URL {$row['url']}\n";

    $curl = curl_init($row['url']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $status = $res === false || in_array($http_code, [404]) ? 'ERROR' : 'DONE';

    $stmt = $pdo->prepare("UPDATE `backend_work_sample_worker` SET `http_code`=:http_code, `status`=:status WHERE `id`=:id");
    $res = $stmt->execute([
        'http_code' => $http_code, 
        'id' => $row['id'],
        'status' => $status
    ]);
    if (!$res) {
        echo "Error saving http_code: {$pdo->errorInfo()[2]}\n";
        exit();
    }

    echo "Received HTTP status code {$http_code} from URL {$row['url']}\n";