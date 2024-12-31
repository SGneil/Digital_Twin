<?php 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $text = $_POST['text'];
        
        $answer = call_get_gpt_text_api($text);
        echo $answer;
    }
    else {
        
        echo("你進來的方式不對~");
    }

    function call_get_gpt_text_api($msg) {
        $url = "http://localhost:5002/get_gpt_api";
        $data = array('msg' => $msg);

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
    
?>