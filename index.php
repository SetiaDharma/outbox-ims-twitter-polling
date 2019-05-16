<?php
    require "./db_connect.php";
    require "./config.php";

    $result = $mysqli->query("SELECT * FROM tb_outbox WHERE tb_outbox.`flag` = '1' ORDER BY tb_outbox.`id_outbox` DESC");
    
    while($data = $result->fetch_assoc()){
        $id = $data['id_outbox'];
        $message = $data['out_msg'];
        $type = $data['type'];
        $receiver_id = $data['receiver_id'];
        
        if($type == 'msg' || $type == 'loc'){
            $data = [
                'event' => [
                    'type' => 'message_create',
                    'message_create' => [
                        'target' => [
                            'recipient_id' => $receiver_id    
                        ],
                        'message_data' => [
                            'text' => $message    
                        ]
                    ]
                ]
            ];
            
            $connection->post('direct_messages/events/new', $data, true);
        } elseif($type == 'file' || $type == 'img'){
            $twitter_client = new TwitterAPIExchange($settings);

            $message = explode(" ", $message);
            
            $url = $message[count($message) - 1];
            $filename = basename($url);
            $content = null;
            
            do {
                $content = $twitter_client->buildOauth($url, 'GET')->performRequest();
            } while(!boolval($content));
            
            file_put_contents($filename, $content);
            
            if($type == 'img'){
                $media = $connection->upload('media/upload', ['media' => "./$filename"]);
            } else {
                $media_type = explode(".", $filename);
                $media_type = $media_type[count($media_type) - 1];
                
                $media = $connection->upload('media/upload', ['media' => "./$filename", "media_type" => "video/$media_type"], true);    
            }
            
            array_pop($message);
            
            $message = implode(" ", $message);
            $message = empty($message) ? "Berikut merupakan media yang telah Anda kirimkan sebelumnya" : $message;
            
            $data = [
                'event' => [
                    'type' => 'message_create',
                    'message_create' => [
                        'target' => [
                            'recipient_id' => $receiver_id    
                        ],
                        'message_data' => [
                            'text' => $message,
                            'attachment' => [
                                'type' => 'media',
                                'media' => [
                                    'id' => $media->media_id_string    
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            
            $connection->post('direct_messages/events/new', $data, true);
            
            unlink($filename);
        }
        
        $mysqli->query("UPDATE tb_outbox SET tb_outbox.`flag` = '2' WHERE tb_outbox.`id_outbox` = '$id'");
    }
?>