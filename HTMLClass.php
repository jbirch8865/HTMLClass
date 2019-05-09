<?php
class ElementAttributeNotSet extends Exception{}
class ElementAttributeAlreadySet extends Exception{}
class ElementConstructorMissingParameters extends Exception{}

class HTMLElement
{
	private $attributes;
	private $type;
	private $self_closers;
	private $inner_HTML;
	
	function __construct($type) 
	{
        $this->attributes = array();
		$this->type = $type;
		$this->self_closers = array('input','img','hr','br','meta','link');
    }
	function EditAttribute($name,$value)
	{
		if(array_key_exists($name, $this->attributes))
		{
			$this->attributes[$name] = $value;
		}else
		{
			throw new ElementAttributeNotSet($name." not found in list of current attributes.  Try first creating the attribute.");
		}
	}
	function RemoveAttribute($name)
	{
		if(array_key_exists($name, $this->attributes))
		{
			unset($this->attributes[$name]);
		}else
		{
			throw new ElementAttributeNotSet($name." not found in list of current attributes.");
		}		
	}
	function CreateAttribute($name,$value)
	{
		if(array_key_exists($name, $this->attributes))
		{
			throw new ElementAttributeAlreadySet($name." is already created. use EditAttribute to modify the attribute.");
		}else
		{
			$this->attributes[$name] = $value;
		}		
	}	
	private function BuildElementString()
	{
		$HTMLString = '<'.$this->type;
		//add attributes
		if(count($this->attributes))
		{
			foreach($this->attributes as $AttributeName=>$AttributeValue)
			{
				$HTMLString.= ' '.$AttributeName.'="'.$AttributeValue.'"';
			}
		}
		
		//closing
		if(!in_array($this->type,$this->self_closers))
		{
			$build.= '>'.$this->attributes['text'].'</'.$this->type.'>';
		}
		else
		{
			$build.= ' />';
		}
		
		//return it
		return $build;
	}
	function AddHTML($HTML)
	{
		$this->inner_HTML = $HTML;
	}
	function AppendHTML($HTML)
	{
		$this->inner_HTML.=$HTML;
	}
	function ClearHTML()
	{
		$this->inner_HTML = '';
	}
	function ReturnElement()
	{
		return $this->BuildElementString();
	}
}

class HTMLtag extends HTMLElement
{
	    function __construct($HTMLContent) 
		{
			parent::__construct('html');
			$this->AddHTML($HTMLContent);
		}
}

class HEADtag extends HTMLElement
{
	    function __construct($headContent) 
		{
			parent::__construct('head');
			$this->AddHTML($headContent);
		}
}

class METAtag extends HTMLElement
{
	function __construct() 
	{
		parent::__construct('meta');

	}	
}

class SCRIPTtag extends HTMLElement
{
	function __construct($data, $src = false) 
	{
		parent::__construct('script');
		if($src)
		{
			$this->CreateAttribute('src',$data);
		}else
		{
			$this->AddHTML($data);
		}
	}	
}
	
class LINKtag extends HTMLElement
{
	function __construct($HREF,$Type) 
	{
		parent::__construct('link');
		$this->CreateAttribute('href',$HREF);
		$this->CreateAttribute('type',$Type);
	}	
}

class TITLEtag extends HTMLElement
{
	function __construct($Title) 
	{
		parent::__construct('title');
		$this->AddHTML($Title);
	}	
}

class BODYtag extends HTMLElement
{
	function __construct($bodyContent) 
	{
		parent::__construct('link');
		$this->AddHTML($bodyContent);
	}	
}

class HEADERtag extends HTMLElement
{
	function __construct($headerContent) 
	{
		parent::__construct('header');
		$this->AddHTML($headerContent);
	}		
}

class DIVtag extends HTMLElement
{
	function __construct($content) 
	{
		parent::__construct('div');
		$this->AddHTML($content);
	}	
}

class Atag extends HTMLElement
{
	function __construct($href,$text) 
	{
		parent::__construct('a');
		$this->AddHTML($text);
		$this->CreateAttribute('href',$href);
	}	
}

class LItag extends HTMLElement
{
	function __construct($content) 
	{
		parent::__construct('li');
		$this->AddHTML($content);
	}	
}

class ULtag extends HTMLElement
{
	private $lis;
	function __construct($lis) 
	{
		parent::__construct('ul');
		$this->lis = $lis;
		$this->Buildlis();
	}
	
	private function Buildlis()
	{
		if(IsConstructorParamterValid())
		{
			ForEach($this->lis as $Key => $LiObject)
			{
				$this->AppendHTML($LiObject->ReturnElement);
			}
		}else
		{
			throw new Exception("Failed to build lis");
		}
	}
	
	private function isObjectAValidListObject($object)
	{
		if($object instanceof LItag)
		{
			return true;
		}else
		{
			return false;
		}
	}

	private function AreLisAllValid()
	{
		ForEach($this->lis as $key => $value)
		{
			if($value instanceof LItag)
			{
				//Continue checking other objects
			}else
			{
				return false;
			}
		}
		return true;
	}
	
	private function IsConstructorParamterValid()
	{
		if(is_array($this->lis))
		{
			if(AreLisAllValid())
			{
				
			}else
			{
				throw new ElementConstructorMissingParameters("Lis is not a valid array of List Items Objects");
			}
		}else
		{
			throw new ElementConstructorMissingParameters("lis are not a properly formatted array of list objects");
		}		
	}
}

class NAVtag extends HTMLElement
{
	function __construct($ULObject) 
	{
		parent::__construct('nav');
		$this->AddHTML($ULObject->ReturnElement);
	}		
}


?>