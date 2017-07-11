<?php
    
    class Response{
    private $content;
        private $actions;
        private $callback_id;
        private $user;
        private $action_ts;
        private $original;
        private $response;
        private $attachments;
        private $title;
        private $text;
        private $value;
        private $name;
        private $channel;
        private $imageUrl;
        private $uniqueId;
        private $shareMessage;
        public function run(){
            $this->content= json_decode(file_get_contents('php://input'));    
            $this->actions= $this->content["actions"];
            $this->name= $this->actions[0]["name"];
            $this->callback_id=$this->content["callback_id"];
            $this->user=$this->content["user"];
            $this->action_ts=$this->content["action_ts"];
            $this->original=$this->content["original_message"];
            $this->value= $this->actions[0]["selected_options"][0]["value"];
            $this->attachments= $this->original["attachments"];
            $this->title= $this->attachments[0]["title"];
            $this->text= $this->attachments[0]["text"];
            $this->channel= $this->content["channel"];
            $this->imageUrl= $this->attachments[0]["imageUrl"];
            $this->uniqueId= $this->attachments[0]["id"];
            header("HTTP/1.1 200 OK");
            if($this->name=== "decision"){
                $this->respondDecision();                 
            }else if($this->name=== "share"){
                $this->respondShare();
            }
        }
    
        private function respondDecision(){  

            /*
            你可以在这写入数据库，你可以得到uniqueID，这个是你要的六位数字
            */
            if($this->value==="like"){
                $this->response=array(
                    "attachments"=>[array(
                                "title"=>$this->title,
                                "text"=> $this->text,
                                "image_url"=>$this->imageUrl,
                                "callback_id"=> $this->callback_id,
                                "id"=>$this->uniqueId,
                                "color"=> "#3AA3E3",
                                "attachment_type"=> "default",
                                "actions"=> [
                                    array(
                                        "name"=>"decision",
                                        "text"=>"Like",
                                        "type"=>"button",
                                        "style"=>"primary",
                                        "value"=>"like"
                                        ),
                                    array(
                                        "name"=>"decision",
                                        "text"=>"Dislike",
                                        "type"=>"button",
                                        "value"=>"dislike"
                                        ),
                                    array(
                                        "name"=>"share",
                                        "text"=>"Who you want to share this news?",
                                        "type"=>"select",
                                        "data_source"=>"users"
                                        )
                                ]
            
                            )]
                        );                
                        $this->_json($this->response);
            }else{
                $this->response=array(
                    "attachments"=>[array(
                                "title"=>$this->title,
                                "text"=> $this->text,
                                "image_url"=>$this->imageUrl,
                                "callback_id"=> $this->callback_id,
                                "id"=>$this->uniqueId,
                                "color"=> "#3AA3E3",
                                "attachment_type"=> "default",
                                "actions"=> [
                                    array(
                                        "name"=>"decision",
                                        "text"=>"Like",
                                        "type"=>"button",
                                        "value"=>"like"
                                        ),
                                    array(
                                        "name"=>"decision",
                                        "text"=>"Dislike",
                                        "type"=>"button",
					                   "style"=>"primary",
                                        "value"=>"dislike"
                                        ),
                                    array(
                                        "name"=>"share",
                                        "text"=>"Who you want to share this news?",
                                        "type"=>"select",
                                        "data_source"=>"users"
                                        )
                                    ]
            
                            )]
                        );                
		        $this->_json($this->response);
            }
    
        }
        private function respondShare(){
            /*
            在这些数据库代码，存谁分享那条消息给谁
            */
            $this->response=array(
                    $this->shareMessage= "This news has been shared from ".$this->user["name"]." to ".$this->value;
                    "attachments"=>[array(
                                "title"=>$this->title,
                                "text"=> $this->text."\n".$this->shareMessage ,
                                "image_url"=>$this->imageUrl,
                                "callback_id"=> $this->callback_id,
                                "id"=>$this->uniqueId,
                                "color"=> "#3AA3E3",
                                "attachment_type"=> "default",
                                "actions"=> [
                                    array(
                                        "name"=>"decision",
                                        "text"=>"Like",
                                        "type"=>"button",
                                        "value"=>"like"
                                        ),
                                    array(
                                        "name"=>"decision",
                                        "text"=>"Dislike",
                                        "type"=>"button",
                                        "value"=>"dislike"
                                        ),
                                    array(
                                        "name"=>"share",
                                        "text"=>"Who you want to share this news?",
                                        "type"=>"select",
                                        "data_source"=>"users"
                                        )
                                ]
            
                            )]
                        );                
                        $this->_json($this->response);
        }
        private function _json($array){
              echo json_encode($array);
        }
    
    }      
    $obj= new Response();
    $obj->run();
?>
