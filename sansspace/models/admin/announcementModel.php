<?php

class announcement extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'announcement';
	}

	public function rules()
	{
		return array(
		);
	}

	public function relations()
	{
		return array(
			
		);
	}

	public function attributeLabels()
	{
		return array(
            'id'=>'Id',
            'content'=>'Content',
            'status'=>'Status',
            'more'=>'More',
            'message'=>'Message',
		);
	}
    
    public function getStatus(){
        $a = $this->status;
        return $a;
    }
    
    public function getMessage(){
        return($this->message);
    }
    
    public function getMore(){
        return($this->more);
    }    
    
    public function getContent(){ 
        $string = "<div id='full-announce-" .$this->id. "' class='full-announce'>
                        <div id='announce-overlay-" .$this->id. "' class='announce-overlay'></div><div id='close-holder-" .$this->id. "' class='close-holder'>
                            <div id='remove-alert-" .$this->id. "' class='announce-check' onclick='removeAlert(this)'></div>
	                   </div>	
	                   <div id='announcement-" .$this->id. "' class='announcement'>
                            <div id='announcement-icon-" .$this->id. "' class='announcement-icon'></div>
                            <div id='announcement-text-" .$this->id. "' class='announcement-text'>
                                <span id='simple-message-" .$this->id. "'>" .$this->message. "</span>";
        if($this->more){$string .= "<a id='announcement-url-" .$this->id. "' href='" .$this->more. "' target'_blank' > Read More!</a>";}
                            $string .= "</div>
                      </div>
                    </div>";
        return $string;
    }
    
    public function getSampleContent(){ 
        $string = "<div id='full-announce-sample-0" .$this->id. "' class='full-announce'>
                        <div id='announce-overlay-sample-0" .$this->id. "' class='announce-overlay'></div><div id='close-holder-sample-0" .$this->id. "' class='close-holder'>
                            <div id='remove-alert-sample-0" .$this->id. "' class='announce-check'></div>
	                   </div>	
	                   <div id='announcement-sample-0" .$this->id. "' class='announcement'>
                            <div id='announcement-icon-sample-0" .$this->id. "' class='announcement-icon'></div>
                            <div id='announcement-text-sample-0" .$this->id. "' class='announcement-text'>
                                <span id='simple-message-sample-0" .$this->id. "'>" .$this->message. "</span>";
        if($this->more){$string .= "<a id='announcement-url-sample-0" .$this->id. "' href='" .$this->more. "' target'_blank' > Read More!</a>";}
                            $string .= "</div>
                      </div>
                    </div>";
        return $string;
    }
}

