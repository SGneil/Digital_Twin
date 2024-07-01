<?php
    // 呼叫PYTHON API(move body)
    function call_flask_move_body_api($image, $driving, $result) {
        $url = 'http://localhost:5000/run';
        $data = array(
            'image' => $image,
            'driving' => $driving,
            'result' => $result
        );

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ),
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) {
            die('Error');
        }
        return $result;
    }

    $image = $_POST['image'];
    $driving = $_POST['driving'];
    $result = $_POST['result'];

    call_flask_move_body_api($image, $driving, $result);
?>