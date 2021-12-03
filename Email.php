<?php
if(!function_exists('mailer'))
{
function mailer($subject, $to, $to_Name, $content, $textContent = "", $attachment)
{
    $email = "email";
    
    $name = "Name";
    
    $body = $content;



    $headers = array(

        'Authorization: Bearer Somekey',
  
        'Content-Type: application/json'
    );

    if (isset($attachment) && !empty($attachment)) {
        
        $data = array(

            "personalizations" => array(

                array(

                    "to" => array(

                        array(

                            "email" => $to,
                            
                            "name"  => $to_Name
                        )
                    )
                )

            ),


            "from" => array(

                "email" => $email
            ),


            "subject" => $subject,
            "content" => array(

                array(

                    "type" => "text/html",
                    "value" => $body,
                    
                )
             
                
            ),//inline_image
            "attachments" => array(
                array(
                    "content" => $attachment,
                    "type" => "image/png",
                    "filename" => "comic.png",
                    "disposition" => "attachment"
                ),
                array(
                    "content" => $attachment,
                    "type" => "image/png",
                    "filename" => "comic.png",
                    "disposition" => "inline",
                    "content_id"=>"inline_image"

                )
                
                

            )


        );
    } else {
        
        $data = array(

            "personalizations" => array(

                array(

                    "to" => array(

                        array(

                            "email" => $to,
                            
                            "name"  => $to_Name
                        )
                    )
                )

            ),


            "from" => array(

                "email" => $email
            ),


            "subject" => $subject,
            "content" => array(

                array(

                    "type" => "text/html",
                    "value" => $body
                )
            )


        );
    }




    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);

    curl_close($ch);

    
}
}
?>